### from [https://muhammedsari.me/unorthodox-eloquent](https://muhammedsari.me/unorthodox-eloquent)


# Tóm tắt 
- Eloquent là một công cụ mạnh mẽ cho phép thao tác với CSDL, triển khai mô hình [Active record](https://muhammedsari.me/unorthodox-eloquent).
- Giống như bất kỳ các công cụ nào, ELoquent cũng có những đánh đổi, chúng ta nên nhận thức được những thứ chúng ta đang đánh đổi.
## 1. Tappable scope 

 Theo truyền thống, các "query scope " được tái sử dungj được định nghĩa trong chính các model bằng cách sử dụng phương thức magic "scopeXXXX", marcos hoặc BUilder. 
 Vấn đề của các cách tiếp cận trên là : 
 - Phụ thuộc vào thời gian chạy lhoong rõ ràng của các phương thức magic, không thể nhận được lợi ích từ IDE. 
 - Tệ hơn, có thể xảy ra xung đột tên trong trường hợp đăng ký bằng `marco`.
 - Ngữ nghĩa không rõ ràng. 

Tuy nhiên với cách tiếp cận "tappable scopes" sẽ khắc phục những vấn đề trên. 

Ví dụ: 
```php 

public function index(): Collection
{
    return Company::query()
        ->oldest()
        ->whereNull('user_id')
        ->whereNull('verified_at')
        ->get();
}
```
 Trong ví dụ trên , chúng ta đã áp dụng các điều kiện vào `Builder`. Đây là một cách làm hoàn toàn hợp lệ 
 nhưng nó làm rò rỉ nội dung bên trong và mệnh đề `where` không cung cấp cho chúng ta bất kỳ ngữ nghĩa gì. 
 
Cách tiếp cận bằng "Tappable scopes": 
```php
public function index(): Collection
{
    return Company::query()
        ->oldest()
        ->tap(new Orphan())
        ->tap(new Unverified())
        ->get();
}
```
Khi đọc truy vấn trên, chúng ta có thể ngầm hiểu được yêu cầu của nó. "Tìm ra các công ty lâu đời nhất, bị bỏ rơi và chưa được đăng ký.".
đây là những gì trong `Orphan` : 
```php
final readonly class Orphan
{
    public function __invoke(Builder $builder): void
    {
        $builder->whereNull('user_id');
    }
} 
```
Cách tiếp cận này cho phép chúng ta tùy ý tạo ra các truy vấn theo bất kỳ cách thức, hình dạng nào mà cần sử dụng các traits mà không sợ làm `ô nhiễm` model của bạn. 


nếu bạn là 1 tác giả gói và muốn chia sẻ "scope" tái sử dụng cùng gói của mình. Tappable đặc biệt hữu ích, giúp tránh xung dột giữa tên phương thức và scope. 


## 2. Global scope nhưng không hoàn toàn là Gloabal scope.
 

Tâm lý chung luôn là " global is bad, local is good". Lý do là do tài liệu của Laravel làm cho "global scope" chỉ có duy nhất một cách áp dụng. 
 
Nó không cần thiết để bắt buộc phải khai báo "global scope" trong phương thức `boost` của AppServiceProvider. 
Thực tế là không có bất kỳ giới hạn nào, có thể là trong Controller, Middleware, ... Theo tác giả, trường hợp sử dụng tốt nhất là trong 
kết hợp với Middleware. 

Giải thich bằng ví dụ: 
Giả sử bạn đang làm trên ứng dụng giống như [IMDb](https://www.imdb.com/) cái mà có cả trang web public và cả trang quản trị nội bộ. Một trong các yêu cầu có thể là các bộ phim cụ thể chỉ nên xuất hiện với người dùng nếu quốc gia của họ có sẵn trong danh sách trắng cụ thể, nếu không nó nên coi như bộ phim đó hoàn toàn không tồn tại. Bnạ phải phân vùng dữ liệu dựa trên quốc gia. Tuy nhiên, sự giới han này chỉ nên áp dụng với trang web công cộng, kkhoong phải là kênh quản trị nội bộ.  Một cách không cần nỗ lực để triển khai yêu cầu này là bằng cách tận dụng khái niệm 'not-sp-global global scope'. 

Đầu tiên bạn tạo ra phạm vi toàn cầu mà bạn muốn: 
```php 
final readonly class CountryRestrictionScope implements Scope
{
    public function __construct(private Country $country) {}

    public function apply(Builder $builder, Model $model): void
    {
        // pseudocode: do the actual country-based filtering here
        $builder->isAvailableIn($this->country);
    }
}
```
sau đó tạo ra 1 middleware chịu trách nhiệm gán cho từng model bạn muốn giới hạn: 
    
```php 
final readonly class RestrictByCountry
{
    public const NAME = 'country.restrict';

    public function __construct(private Repository $geo) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $scope = new CountryRestrictionScope($this->geo->get());

        Movie::addGlobalScope($scope);
        Rating::addGlobalScope($scope);
        Review::addGlobalScope($scope);

        return $next($request);
    }
}
```
sau đó, áp dụng trên các router bạn muốn thực hiện: 
```php 

$router->group([
    'middleware' => ['web', RestrictByCountry::NAME],
], static function ($router) {
    $router->resource('movies', Site\MovieController::class);
    $router->resource('ratings', Site\RatingController::class);
    $router->resource('reviews', Site\ReviewController::class);
	
    // Front-facing public website routes...
});

$router->group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'admin',
], static function ($router) {
    $router->resource('movies', Admin\MovieController::class);
    $router->resource('ratings', Admin\RatingController::class);
    $router->resource('reviews', Admin\ReviewController::class);
	
    // Admin routes...
});
``` 

Middleware chỉ được áp udngj trên router trang web được public mà không trên trang admin. Điều này có ý nghĩa sau: 

- bất cứ khi nào người dùng vào bất kỳ trang web nào , nội dung của nó sẽ được tư đông lọc theo quốc gia. Điều này có thể dẫn đến trang 404. 
- Bất kỳ router mới nào cần được thêm do các yêu cầu mới, các nhà phát triển không cần nhớ sự thật là mỗi model cần được filtered theo quốc gia của người dùng. 
- Bất cứ nhà phát triển nào sử dung REPL như tinker, khoog cần bất ngờ vì một phạm vi toàn cầu khó chịu đang ẩn giấu, cái mà đang thay đổi ngầm các truy vấn. Nhớ, phạm vi toàn cầu của chúng ta không quá toàn cầu. 
- Bất cứ khi nào 1 quản trị viên vào kênh quản trị viên, họ luôn thấy tất cả các nội dụng không quan tâm nguồn gốc quốc gia của họ. 

Chúng ta có thể sử dụng cho "Tappable scope" như này : 
```php 
final readonly class FileScope implements Scope
{
public function __invoke(Builder $builder): void
{
$this->apply($builder, File::make());
}

    /** @param File $model */
    public function apply(Builder $builder, Model $model): void
    {
        $builder
            ->where($model->qualifyColumn('model_type'), 'directory')
            ->where($model->qualifyColumn('collection_name'), 'file');
    }
}

```

##  Thuộc tính ảo: 

Trong thực tế khi làm việc, có những trường hợp chúng ta cần hiển thị sô lượng lớn csac điểm đánh dấu trên các map như GG maps, Leaflet or Mapbox. Những map tương tác này yêu cầu 1 list các kiểu địa lý tuân theo GeoJSON spec. Một kiểu "Point", cái mà tôi cần, phải cung cấp các thuộc tính tọa độ với một cặp giá trị của nó (lat, lon) tương ứng. Vấn đề là tọa độ đó là một giá trih tổng hợp,trong khi dữ liệu được làm phẳng như "address:id, lattitude, longtitude" trong CSDL. Có thể giải quyết vấn đề bằng Eloquent Resource. Nhưng có cách giải quyết khác đó là dùng "Thuộc tính ảo". 

1. Giải thich bằng ví dụ: 
Đầu tiên chúng ta tạo ra 1 "valueObject" đại diện cho địa chỉ tọa độ: 
```php 
final readonly class Coordinates implements JsonSerializable
{
    private const LATITUDE_MIN  = -90;
    private const LATITUDE_MAX = 90;
    private const LONGITUDE_MIN = -180;
    private const LONGITUDE_MAX = 180;

    public float $latitude;

    public float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        Assert::greaterThanEq($latitude, self::LATITUDE_MIN);
        Assert::lessThanEq($latitude, self::LATITUDE_MAX);
        Assert::greaterThanEq($longitude, self::LONGITUDE_MIN);
        Assert::lessThanEq($longitude, self::LONGITUDE_MAX);

        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function jsonSerialize(): array
    {
        return [$this->latitude, $this->longitude];
    }

    public function __toString(): string
    {
        return "({$this->latitude},{$this->longitude})";
    }
}
```
 sau đó định nghĩa 1 "AsCoordinates" cast: 
 ```php 
 final readonly class AsCoordinates implements CastsAttributes
{
    public function get(
        $model, 
        string $key,
        $value, 
        array $attributes,
    ): Coordinates {
        return new Coordinates(
            (float) $attributes['latitude'], 
            (float) $attributes['longitude'],
        );
    }

    public function set(
        $model, 
        string $key,
        $value, 
        array $attributes,
    ): array {
        return $value instanceof Coordinates ? [
            'latitude' => $value->latitude,
            'longitude' => $value->longitude,
        ] : throw new InvalidArgumentException('Invalid value.');
    }
}
 ```

sau đó chúng ta gắn trong model: 
```php 
final class Address extends Model
{
    protected $casts = [
        'coordinates' => AsCoordinates::class,
    ];
}
```

sau dó sử dụng đơn giản trong Eloquent Resource: 
```php
final class FeatureResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'geometry' => [
                'type' => 'Point',
                'coordinates' => $this->coordinates,
            ],
            'properties' => $this->only('name', 'phone'),
            'type' => 'Feature',
        ];
    }
} 
```
 - Coordinates bây giờ hoàn toàn chiụ trách nhiệm cho khái nhiệm Coordinates( đại hiện cho  hai điểm hai chiều trên trái đất). 
 - CHúng ta không cần phải gọi `jsonSerialize()` bởi vì giao diện đã được triển khia. Nó sẽ được thực hiện cho chúng ta do Laravel đã gọi `json_encode` trong vài nơi của cuộc gọi ngăn xếp. 

 - Nếu có gì đó thay đổi về Coordinates, nó sẽ đơn giản để tìm ra tọa độ đang được sử dụng. 

2. Giải thích băng ví dụ khác: 
Một cách sử dụng khác của thuộc tính ảo là giúp bạn kết suất template. Nếu bạn muốn hiển thị địa chỉ ra HTML, bạn cần mã như sau: 
```php 
<address>
  <span>{{ $address->line_one }}</span>
  @if($two = $address->line_two)<span>{{ $two }}</span>@endif
  @if($three = $address->line_three)<span>{{ $three }}</span>@endif
</address>
```
Các quốc gia khác nhau sẽ có cách hiển thị địa chỉ khác nhau. 
Điều gì sẽ xảy ra nếu tôi muốn viết như này: 
```php 
<address>
  @foreach($address->lines as $line)
  <span>{{ $line }}</span>
  @endforeach
</address>
```
Trông khá ổn, chúng ta sẽ không phải quan tâm về sự phức tạp do các quốc gia khác nhau có quy định khác nhau. 
Thuộc tính ảo chịu trách nhiệm cho việc này sẽ như này: 
```php 
final readonly class AsLines implements CastsAttributes
{
    public function get(
        $model, 
        string $key,
        $value, 
        array $attributes,
    ): array {
        return array_filter([
            $attributes['line_one'],
            $attributes['line_two'],
            $attributes['line_three'],
        ]);
    }

    public function set(
        $model, 
        string $key,
        $value, 
        array $attributes,
    ): never {
        throw new RuntimeException('Set the lines explicitly.');
    }
}
```
 Nếu phải hoán đổi "line_two" và "line_one" cho Châu phi, chúng ta sẽ điều chỉnh trog AsLine và không phải điều chỉnh mãau. Suy nghĩ sáng tạo có thể đơn giản quá hơn nhiều cách chúng ta có thể kết suất UI và ngăn chúng ta khỏi việc sáng tạo ra các UI quá thông minh cái làm chúng ta khó chịu và bị xem là phản mẫu. 


3. Một điểm chú ý có sẵn trong tài liệu: 

Những thuộc tính này không được ánh xạ trực tiêp trong CSDL và có thể so sánh với Accessor và Mutator. Tài liệu gọi chúng là"value object casting", nhưng điều này có thể dẫn đến hiểu lầm mạnh mẽ vi nó không yêu cầu phải **cast** nó sang "valueObject" với cách tiếp cận này. 

```php 
final readonly class AsProductNumber implements CastsAttributes
{
    public function get(
        $model, 
        string $key,
        $value, 
        array $attributes
    ): string {
        return implode('-', [
            $attributes['number_country'],
            Str::padLeft($attributes['number_department'], 2, '0'),
            Str::padLeft($attributes['number_sequence'], 5, '0'),
        ])
    }

    public function set(
        $model, 
        string $key,
        $value, 
        array $attributes,
    ): array {
        [$country, $dept, $sequence] = explode('-', $value, 2);

        return [
            'number_country' => $country,
            'number_department' => (int) $dept,
            'number_sequence' => (int) $sequence,
        ];
    }
}
```
Thuộc tính ảo chịu trách nhiệm cho việc sinh ra các chuỗi gía trị và không phỉa là 1 "ValueObject". 

## 4. Fluent query object. 

 Như đã đề cạp tới các Lớp Builder tùy chỉnh trong phần đầu tiên. Tronbg khi chúng là 1 bước đầu tiên tốt hướng tới khả năng dễ đọc dễ bảo trì, tôi nghĩ rằng chúng sẽ nhanh chóng là 1 thất bại khi thêm nhiều các ràng buộc cần được thêm vào các lớp tùy chỉnh. Cũng có nhiều rắc rối dể đưa IDE tới nơi nó bắt đầu giúp bạn các đề xuất. Bạn cũng có thể tạo Kho lưu trữ dành riêng cho mô hình của mình. Nhưng theo tôi, một Repository và Eloquent là loại trừ lẫn nhau. Nếu bạn hiểu [lý do tại sao "Repository " và "Eloquent" xuất hiện](https://muhammedsari.me/repositories-and-their-true-purpose), bạn sẽ hiểu: 

Một giải pháp khác là sử dụng cái được gọi là QueryObject. Nó có thể 1 một object chịu trách nhiệm cho việc tổng hợp và thực thi một loại query đơn. Trong khi diều này không hoàn toàn tuân theo định nghĩa của [Marin Fowler](https://www.martinfowler.com/eaaCatalog/queryObject.html), có gần như thế và tôi nghĩ đó là mượn các ý tưởng cho mục địch cụ thể này là tốt. Nếu bạn có đăng ký Laracasts, bạn có thể đã xem bài học về chủ đề này. Mặc dù triết lý và cách suy nghĩ giống hệt nhau, tôi muốn giới thiệu một API thay thế dễ sử dụng hơn nhiều: Fluent query object.



1. Giả thích bằng ví dụ:

Tưởng tượng bạn có 1 trang SPA, được hõ trợ bởi HTTP JSON API, có một cái chuông thông báo ở trên. Phía back-end hiển thị 1 điềm cuối cái mà chúng ta có thể truy xuất các thông báo chưa đọc của một người udngf đã đăng nhập. Controller chiu trách nhiệm cho việc lấy các thông báo chưa đọc: 

```php 
public function index(Request $request): AnonymousResourceCollection
{
    $notifications = Notification::query()
        ->whereBelongsTo($request->user())
        ->latest()
        ->whereNull('read_at')
        ->get();

    return NotificationResource::collection($notifications);
}
```
Một chuyện đều đơn giản cho đến khi chúng tôi nhận được yêu cầu mới rằng chúng tôi tạp ra 1 trang quản lý tất cả các thông bóa: chưa đọc, đã đọc, kiểu thông báo...Để làm cho các dev front-end dễ thở hơn, chúng tooi đã quyết định tạo ra 1 đầu cuối dành riêng cho từng loại view. Một trong số chúng chịu trách nhiệm cho truy xuất các thông báo đã đọc, như sau:  

```php
public function index(Request $request): AnonymousResourceCollection
{
    $notifications = Notification::with('notifiable')
        ->whereBelongsTo($request->user())
        ->latest()
        ->whereNotNull('read_at')
        ->get();

    return NotificationResource::collection($notifications);
}
```
Mọi thứ có vẻ giống nhau, chỉ khác là "whereNotNul" và một quan hệ "notifiable" 

```php 
public function index(Request $request): AnonymousResourceCollection
{
    $notifications = Notification::query()
        ->whereBelongsTo($request->user())
        ->latest()
        ->where('data->type', '=', $request->type)
        ->get();

    return NotificationResource::collection($notifications);
}
```
Việc này lặp lại qúa nhiều lần.Và cần làm gỉ đó để giải quyết điều này, "Fluent query object". Đầu tiên chúng ta cần tạo class chịu trách nhiệm lấy notification: 

```php 
final readonly class GetMyNotifications
{
}
```

chúng ta sẽ chuyển truy vấn cơ sở (các điều kiện cần được áp dụng mọi lúc) sang hàm tạo của đối tượng hoàn toàn mới của chúng ta:

```php 
final readonly class GetMyNotifications
{
    private Builder $builder;

    private function __construct(User $user)
    {
        $this->builder = Notification::query()
            ->whereBelongsTo($user)
            ->latest();
    }

    public static function query(User $user): self
    {
        return new self($user);
    }
}
```
sau đó sử dụng "ForwardsCalls" để tận dụng khả năng :
```php 
final readonly class GetMyNotifications
{
    use ForwardsCalls;

    // omitted for brevity

    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardDecoratedCallTo(
            $this->builder, 
            $name, 
            $arguments,
        );
    }
}
```

Quan sát:
- "ForwardsCalls" cho phép chung ta cư xử với claass như là nó là 1 phần của "base class" \Illuminate\Database\Eloquent\Builder  mặc dù không có sự kế thừa ở đây.
- Chú thích @mixin sẽ giúp IDE cung cấp cho chúng ta những gợi ý tự động hoàn thành hữu ích.
- Bạn có thể chọn để thêm các "Conditionable" để có API mượt hơn, nhưng nó hoàn toàn không cần thiết ở đây do chúng ta đã chọn kiểu thiết kế. 

Điểm duy nhất còn lại là thêm các ràng buộc tùy chỉnh: 

```php 
final readonly class GetMyNotifications
{
    // omitted for brevity
	
    public function ofType(NotificationType ...$types): self
    {
        return $this->whereIn('data->type', $types);
    }

    public function read(): self
    {
        return $this->whereNotNull('read_at');
    }

    public function unread(): self
    {
        return $this->whereNull('read_at');
    }
	
    // omitted for brevity
}
```
sau đó tái cấu trúc lại "Controller" : 
```php 
public function index(Request $request): AnonymousResourceCollection
{
    $notifications = GetMyNotifications::query($request->user())
        ->read()
        ->with('notifiable')
        ->get();

    return NotificationResource::collection($notifications);
}
```
Bạn có thể vẫn sử dụng tất cả các phương thức của  Illuminate\Database\Eloquent\Builder trong khi cũng có thể có khả năng để gọi các cuộc gọi nang cao, đặc biệt là các phương thức dành riêng cho truy vấn đặc biệt này. 

4. Sharing eager-load

Ví dụ, nếu chúng ta có một mô hình Product với nhiều MediaCollections khác nhau, chẳng hạn như images, videos và documents, thì việc eager load toàn bộ mối quan hệ media sẽ tải về tất cả các hình ảnh, video và tài liệu được đính kèm cho sản phẩm đó. Điều này có thể dẫn đến việc tải về một lượng dữ liệu lớn, đặc biệt là nếu sản phẩm có nhiều MediaCollections.
Nếu trang chỉ cần mỗi "thumnail", việc tải toàn bộ mối quan hệ "media" là không cần thiết. 
Để giải quyết vấn đề này sử dụng một điều kiện lọc truy vấn để chỉ eager load các ảnh thumbnail. Điều này sẽ giúp chúng ta tải về ít dữ liệu hơn và cải thiện hiệu suất của ứng dụng.

```php 
public function index(): View
{
    $products = Product::with([
        'categories',
        'media' => static function (MorphMany $query) {
            $query->where('collection_name', 'thumbnail')
        },
        'variant.media' => static function (MorphMany $query) {
            $query->where('collection_name', 'thumbnail')
        },
    ])->tap(new Available())->get();

    return $this->view->make('products.index', compact('products'));
}
```
vấn đề overfetching đã được gaiarir quyết nhưng trông khoog đẹp. Trường hợp bạn dùng nó ở nhiều nơi, bạn sẽ phải lặp lại nhieuf lần . 
vì vậy hẫy tạo ra 1 class đại diện cho các constraint của bạn: 
```php 
final readonly class LoadThumbnail implements Arrayable
{
    public function __invoke(MorphMany $query): void
    {
        $query->where('collection_name', 'thumbnail');
    }

    public function toArray(): array
    {
        return ['media' => $this];
    }
}
```

5. Invokable accessors
  Khi sử dụng phantom property, lỗ hổng lớn nhất là chúng yêu cầu định nghĩa `set` cast ngay cả khi không cần dùng và cũng k có cơ chế cho tự động ghi nhớ kết quả tính toán.
Nhưng có thể khắc phục với "Invokable accessors"
```php 
final class File extends Model
{
    protected function stream(): Attribute
    {
        return Attribute::get(new StreamableUrl($this));
    }
}
```
6. Multiple read models for the same table

7. WithoutRelations for queue performance
