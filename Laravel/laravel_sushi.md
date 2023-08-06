### from [https://laravel-news.com/laravel-sushi](https://laravel-news.com/laravel-sushi)


#  Học Laravel Sushi - Trình điều khiển mảng cho Eloquent. 

 Tuần trước, tôi đã đăng 1 bài xây dựng ứng dụng mẫu bằng cách sử dụng [Volt và Folio](https://laravel-news.com/livewire-volt-and-folio). Trong ví dụ đó, Tôi đã sử dụng `gói của Caleb Porzio`,[Sushi](https://github.com/calebporzio/sushi) cho dữ liệu mẫu. Điều đó gây to mò cho tôi về người ta đang dùng Sushi để làm gì, vì vậy tooi đã [tweet](https://twitter.com/jasonlbeggs/status/1684597460805574657)  để hỏi mọi người đnag dùng Sushi để làm gì. 
Trong bài viết này, tôi sẽ bao gồm những khái niệm cơ bản của Sushi và một vài ví dụ cách chúng ta có thể sử dụng nó. 

# Laravel Sushi là gì, và cách nó hoạt động? 
 Theo README của gói, Sushi là 1 trình điều khiển mảng còn thiếu của Eloquent. Nói cách khác, nó cho phép bạn tạo ra model Eloquent từ các nguồn dữ liệu khác không chỉ là từ 1 CSDL. Cách đơn giản nhất để sử dụng nó là cung cấp dữ liệu của bạn như 1 mảng cứng ngay trong file Model, thiết lập thuộc tính `$rows`. Ngòai ra, bạn có thể sử dụng `getRows` để cung cấp các dữ liệu linh hoạt - từ nguồn API, file CSV, hoặc bất kì đâu bạn chọn.

Vậy nó thực sự hoạt động như nào? Sushi nhận dữ liệu bạn cung cấp cho nó, tạo ra các model Eloquent, sau đó `cạche` chúng trong CSDL `sqlite`. Sau đó, bạn có thể truy vấn dữ liệu giống như bất kỳ model Eloquent tiêu chuẩn nào. 

Đây là một ví dụ rất cơ bản của model Sushi: 

```php
<?php
 
namespace App\Models;
 
use Sushi\Sushi;
 
class Role extends Model
{
    // Add the trait
    use Sushi;
 
    // Provide the data as a hardcoded array
    protected $rows = [
        ['id' => 1, 'label' => 'admin'],
        ['id' => 2, 'label' => 'manager'],
        ['id' => 3, 'label' => 'user'],
    ];
}


```
 # Laravel Sushi states

 Hãy đi sâu vào các ví dụ trong đời thực, tôi và  những người khác đã sử dụng. Những thứ cơ bản nhất tôi sẽ giới thiệu là tạo ra 1 danh sách hoặc tạo ra 1 bảng các `states`. [Ken](https://twitter.com/Xewl/status/1684708115340500992) và [Facundo](https://twitter.com/fotrino/status/1684605179461500929) đã 
đề cập trường hợp sử dụng này, cá nhân tôi cũng đã sử dụng nó: 
```php 
<?php
 
namespace App\Models;
 
use Sushi\Sushi;
 
class Role extends Model
{
    use Sushi;
 
    protected $rows = [
        [
            'id' => 1,
            'name' => 'Alabama',
            'abbreviation' => 'AL',
        ],
        [
            'id' => 2,
            'name' => 'Alaska',
            'abbreviation' => 'AK',
        ],
        [
            'id' => 3,
            'name' => 'Arizona',
            'abbreviation' => 'AZ',
        ],
        [
            'id' => 4,
            'name' => 'Arkansas',
            'abbreviation' => 'AR',
        ],
        [
            'id' => 5,
            'name' => 'California',
            'abbreviation' => 'CA',
        ],
        // ...
    ];
}

```

 > Chú ý: cột "id" ;à tùy chọn. Sushi có thể tạo ra các IDS tự động tăng cho mỗi Item, nhưng nếu items thay đổi ( và 'cache` đã bị hỏng ), bạn không đảm bảo các items sẽ nhận cùng IDs chúng có trước đây. Nếu bạn đang liên kết dữ liệu khác với model Sushi, nó tốt nhât bạn nên  để cung cấp 1 cột ID tĩnh cho mỗi item. 

 # Laravel Sushi cho Blogs, course và thông tin sản phẩm. 

Một trường hợp hữu dụng khác là các khóa học và blogs đơn giản. Thỉnh thoảng, như 1 nhà phát triển, tôi cần lưu vài trang cho Blog hoặc Khóa học, nhưng tôi không cần một CMS đầy đủ . Tôi muốn giữ nó nhẹ hơn, dồng thời có tất cả các nội dung của tôi được lưu trực tiếp trong mã vì vậy nó có thể đồng bộ qua Git.  

[Aaron](https://twitter.com/aarondfrancis/status/1684604174166523915) đã đề cập rằng anh ấy sử dụng kiểu thiết lập này cho các blog trên [aaronfrancis.com](https://aaronfrancis.com/). [Caled](https://twitter.com/calebporzio/status/1684638383547596801) đã dề cập  nền tảng `screencasts ` Livewire v2 sử dụng những thứ tương tự như này: 


```php 
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
 
class Series extends Model
{
    use Sushi;
 
    public function screencasts()
    {
        return $this->hasMany(Screencast::class);
    }
 
    public function getRows()
    {
        return [
            ['id' => 1, 'order' => 1, 'title' => 'Getting Started'],
            ['id' => 2, 'order' => 2, 'title' => 'A Basic Form With Validation'],
            //...
        ];
    }
}
```
```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
 
class Screencast extends Model
{
    use Sushi;
 
    public function series()
    {
        return $this->belongsTo(Series::class);
    }
 
    public function getNextAttribute()
    {
        return static::find($this->id + 1);
    }
 
    public function getPrevAttribute()
    {
        return static::find($this->id - 1);
    }
 
    public function getDurationInSecondsAttribute()
    {
        // ...
    }
 
    protected $rows = [
        [
            'title' => 'Installation',
            'slug' => 'installation',
            'description' => "Installing Livewire is so simple, this 2.5 minute video feels like overkill. Composer require, and two little lines added to your layout file, and you are fully set up and ready to rumble!",
            'video_url' => 'https://vimeo.com/...',
            'code_url' => 'https://github.com/...',
            'duration_in_minutes' => '2:32',
            'series_id' => 1,
        ],
        [
            'title' => 'Data Binding',
            'slug' => 'data-binding',
            'description' => "The first and most important concept to understand when using Livewire is "data binding". It's the backbone of page reactivity in Livewire, and it'll be your first introduction into how Livewire works under the hood. Mandatory viewing.",
            'video_url' => 'https://vimeo.com/...',,
            'code_url' => 'https://github.com/...',
            'duration_in_minutes' => '9:11',
            'series_id' => 1,
        ],
        // ...
    ];
}
```
 Như bạn nhìn thấy trong ví dụ này, bởi vì chúng là các model Eloquents thực, bạn có thể thêm các relationships, getters, và các phương thức helper như bạn có thể sử dụng trong các model thông thường.
 Với những model này, bạ có thể truy vấn chúng trong `Controller` hoặc Livewire components như bạn muốn làm với model database-driven: 

```php
$series = Series::with(['screencasts'])->orderBy('order')->get();
```
 Sau đó, bạn có thể lặp chúng trong Blade của bạn: 

```php 
<div>
    @foreach($series as $s)
        <div>
            <h2>{{ $series->title }}</h2>
            <div>
                @foreach($series->screencasts as $screencast)
                    <div>
                        <h3>{{ $screencast->title }}</h3>
                        <p>{{ $screencast->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
```
 Bạn có thể thậm chí sử dụng các ràng buộc model định tuyến của Laravel để tự động truy vấn các models sushi: 
```php 
Route::get('/screencasts/{screencast:slug}');
```
 Caled và tôi sử dụng một cách tiếp cận rất đơn giản để lưu các components cho các [Components Aplineơ](https://alpinejs.dev/component). Chúng tôi đã sử dụng các ràng buộc model route cho các routes, sau đó Blade sẽ xem dể thể hiện chi tiết cho từng components.

Trong các Blade Views, chúng tôi đã lặp qua các biến thể của component và sử dụng `@include($variant->view)` để thêm các Blade views đã được mã hóa cứng riêng biệt cái mà thực sự mã hóa cho Component. 
```php 
<?php
 
namespace App\Models;
 
use App\Enums\ComponentType;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;
 
class Component extends Model
{
    use Sushi;
 
    protected $casts = [
        'variants' => 'collection',
        'requirements' => 'collection',
        'type' => ComponentType::class,
    ];
 
    public function getRows()
    {
        return [
            [
                'title' => 'Dropdown',
                'slug' => 'dropdown',
                'description' => 'How to build a dropdown component using Alpine.js.',
                'screencast_id' => 111,
                'variants' => json_encode([
                    ['view' => 'patterns.dropdown'],
                ]),
                'type' => ComponentType::COMPONENT->value,
                'is_public' => true,
                'is_free' => true,
                'requirements' => json_encode([
                    [
                        'name' => 'alpinejs',
                        'version' => 'v3.x',
                        'url' => 'https://alpinejs.dev/installation',
                    ],
 
                ]),
            ],
            [
                'title' => 'Modal',
                'slug' => 'modal',
                'description' => 'How to build a modal component using Alpine.js.',
                'screencast_id' => 222,
                'variants' => json_encode([
                    ['view' => 'patterns.modal'],
                ]),
                'type' => ComponentType::COMPONENT->value,
                'is_public' => true,
                'is_free' => false,
                'requirements' => json_encode([
                    [
                        'name' => 'alpinejs',
                        'version' => 'v3.x',
                        'url' => 'https://alpinejs.dev/installation',
                    ],
                    [
                        'name' => '@alpinejs/focus',
                        'version' => 'v3.x',
                        'url' => 'https://alpinejs.dev/plugins/focus',
                    ],
                ]),
            ],
            // ...
        ];
    }
}
```
 Như bạn thấy trong ví dụ này, chúng ta đã sử dụng hàm `getRows` thay vì sử dụng thuộc tính `$rows`. Điều này là để chúng ta có thể sử dụng hàm `json_encode` và sử dụng cột 'JSON' cho các biến thể và các cột yêu cần trên từng model. Bạn cũng có thể nhìn thấy rằng Sushi hỗ trợ thuộc tính `casting` cho các kiểu khác nhau như laravel làm.

# Nguỗn dữ liệu API. 
 Một trường hợp sử dụng thực tế khác là lấy dữ liệu từ các nguồn API. [Raúl](https://twitter.com/raullgdev/status/1684613578030972933), [Marcel](https://twitter.com/marcelpociot/status/1684701530111295488), [Adam](https://twitter.com/awcodes1/status/1685054738503491585),
 và Caleb đã đề cập đến các nguồn API khác mà họ đã sử dụng: 

Caleb gửi các yêu cầu tới Github Sponsors API để xác định ai có thể truy cập vào ScreenCast v2 Livewire., sau đó ánh xạ qua những kết quả này để bắt các thuộc tính anh ây cần và định dạng chúng trong 1 lược đồ đẹp cho 1 model. Điều này là 1 phiên bản đơn giản cho model Sponsor từ Livewire v2.

```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Sushi\Sushi;
 
class Sponsor extends Model
{
    use Sushi;
 
    protected $keyType = 'string';
 
    public function user()
    {
        return $this->hasOne(User::class, 'github_username', 'username');
    }
 
    public function getsScreencasts()
    {
        // If they sponsor for more than $8, they get access to screencasts.
        return $this->tier_price_in_cents > 8 * 100;
    }
 
    public function getRows()
    {
        return Cache::remember('sponsors', now()->addHour(), function () {
            return collect($this->fetchSponsors())
                ->map(function ($sponsor) {
                    return [
                        'id' => $sponsor['sponsorEntity']['id'],
                        'username' => $sponsor['sponsorEntity']['login'],
                        'name' => $sponsor['sponsorEntity']['name'],
                        'email' => $sponsor['sponsorEntity']['email'],
                        // ...
                    ];
                });
        });
    }
 
    public function fetchSponsors()
    {
        return Http::retry(3, 100)
            ->withToken(
                config('services.github.token')
            )->post('https://api.github.com/graphql', [
                'query' => 'A big ugly GraphQL query'
            ]);
    }
}
```
# Kết luận 
 Sushi thực sự là một gói rất thú vị với những trường hợp sử dụng thú vị. Tôi chắc rằng tôi đã chạm không đủ trong bài viết này. Nếu bạn đã sử dụng gói này, hãy cho tôi biết bạn sử dụng như nào trên Twitter. 

