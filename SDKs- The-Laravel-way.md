# SDKS, The Laravel Way

Với những kiến thức cơ bản từ phần I, bây giờ chúng ta có thể tiếp tục mở rộng các chủ đề như phân trang, mối quan hệ,
bộ giới hạn tỉ lệ...

![images](https://www.eloquentarchitecture.com/content/images/size/w1200/2022/12/request-anatomy-part-ii@2x.png)

Bạn có biêt những bộ công cụ nhỏ đó cái mà đi kèm với đồ nội thât của Thụy Điển sản xuất chứ? Một số chúng trong túi
nhựa. Một số khác chỉ với 1 công cụ. Không có 1 bộ công cụ để thống trị tất cả chúng khi nói đến để thực hiện nhiệm vụ
xây dựng các đồ nội thất. Điều này cũng giống với SDKs.

💡
tldr; Với những thứ cơ bản ở phần 1, chúng ta có thể tiếp tục để mở rộng các chủ đề như phân trang, mối quan hệ, bộ giới
hạn tỷ lệ,...

## Tạo ,cập nhật và xóa bản ghi

Phần I bắt đặt nền móng cho xây dựng CRUD. Có lẽ, bạn đã nghĩ về cái phần còn lại sẽ trông như nào? Nó có lẽ là phần dễ
nhất của nỗ lực này.

ConnectWise API ( cái mà SDK của tôi làm  ) theo những mẫu này cho tất cả các thao tác CRUD.

- **POST** /tickets - Tạo mới 1 ticket
- **GET** /tickets - Liệt kê tất cả các tícket
- **GET** /tickets/:id - 1 ticket cụ thể
- **PUT** /tickets/:id - cập nhật 1 tickets
- **DELETE** /tickets/:id - xóa 1 ticket

với những mẫu điểm cuối này trong đầu, kết thúc phần còn lại của các phương thức CRUD nên khá dễ dàng.

  ```php

class RequestBuilder
{
    public function create(array $attributes)
    {
        return tap(new $model($attributes), function ($instance) {
            $instance->save();
        });
    }
    
    public function get(): Collection
    {
        return new Collection(
            $this->request->get($this->model->url())->json()
        );
    }
    
    public function find(int|string $id): ?Model
    {
        return new static(
            $this->request->get("{$this->url()}/{$id}")->json()
        );
    }
    
    public function update(array $attributes): bool
    {
        $response = $this->request->put(
            "{$this->url()}/{$id}",
            $attributes
        )->json();
        
        return $response->successful();
    }
    
    public function insert(array $attributes): bool
    {
        $response = $this->request->post(
            $this->url(),
            $attributes
        )->json();
        
        return $response->successful();
    }
}
  ```  

```php
abstract class Model
{
    public function __construct(
        public array $attributes = [],
    ) {
    }
    
    public function save(): bool
    {
        return $this->exists
            ? $this->update($this->attributes)
            : $this->insert($this->attributes);
    }
}
```


Các phương thức **create** và **save** là những phương thức tiện lợi, như là trong Eloquent. 

```php

Ticket::create([
    'summary' => 'Printer not working',
]);

// or

(new Ticket([
    'summary' => 'Printer not working',
]))->save();
```

# Các hàm phổ biến 

```php


class RequestBuilder
{
    public function first(): ?Model
    {
        return $this->get()->first();
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    public function missing(): bool
    {
        return ! $this->exists();
    }
}

```
```php

Ticket::open()->first();

if (Ticket::escalated()->exists()) {
    // send email
}

if (Ticket::open()->missing()) {
    // take the day off?
}
```

# Phân trang 

Các lớp lõi của Laravel được thiết kế để modul hóa và có thể mở rộng, cho phép bạn có thể mở rộng và ghi đè những cách xử lý của nó nếu cần. Đây là sự tồn tại có thể để tái sử dụng các cặp lớp mạnh mẽ. Chúng ta sẽ lấy lớp của Laravel **Illuminate\Pagination\Paginator** and **Illuminate\Pagination\LengthAwarePaginator** và bao bọc chúng vào lớp **RequestBuilder**. 


**paginator** cần 1 danh sách những thứ, tổng  những thứ để show, và trang hiện tại. Không quá tệ trong cách của sự phụ thuộc này.

```php

use Illuminate\Pagination\Paginator;

class RequestBuilder
{
    public function page($page): static
    {
        $this->request->withOptions([
            'query' => [
                'page' => $page,
            ],
        ]);

        return $this;
    }

    public function pageSize($size): static
    {
        $this->request->withOptions([
            'query' => [
                'pageSize' => $size,
            ],
        ]);

        return $this;
    }

    public function simplePaginate($pageSize = 25, $page = null): Paginator
    {
        $this->page($page)->pageSize($pageSize);

        return new Paginator($this->get(), $pageSize, $page);
    }
}

```
 # Phân trang nhận biết độ dài 

Trước khi chúng ta triển khai phân trang nhận biết độ dài, chúng ta sẽ cần một vài cách để đếm tổng số lượng bản ghi. Có 1 sự khác biệt duy nhất giwax hai khai triển phân trang này.. 

```php

use Illuminate\Pagination\LengthAwarePaginator;

class RequestBuilder
{
    // public function page($page): static
    // public function pageSize($size): static
    // public function simplePaginate($pageSize, $page): Paginator
    
    public function count(): int
    {
        return (int) data_get($this->request->get("{$this->url()}/count", $this->options), 'count', 0);
    }
    
    public function paginate($pageSize = 25, $page = null): LengthAwarePaginator
    {
        $total = $this->count();

        $results = $total ? $this->page($page)->pageSize($pageSize)->get() : collect();

        return new LengthAwarePaginator($results, $total, $pageSize, $page, []);
    }
}
```

# Chia đoạn 

Việc sử dụng phương thức này trên chuỗi truy vấn builde, bạn có thể chỉ rõ 1 callback để xử  lý mỗi đoạn dữ liệu vì nó được truy suất từ Database. Điều này cho phép bạn có thể xử lý dữ liệu trong từng mảnh nhở hơn,dễ quản lý hơn, hơn là tải mọi thứ vào bộ nhớ 1 lần 

```php

class RequestBuilder
{
    // public function page($page): static
    // public function pageSize($size): static
    // public function paginate($pageSize, $page): LengthAwarePaginator
    // public function simplePaginate($pageSize, $page): Paginator

    public function chunk($count, callable $callback, $column = null): bool
    {
        $page = 1;
        $model = $this->newModel();

        $this->orderBy($column ?: $model->getKeyName());

        do {
            $clone = clone $this;

            $results = $clone->pageSize($count)
                ->page($page)
                ->get();

            $countResults = $results->count();

            if ($countResults == 0) {
                break;
            }

            if ($callback($results, $page) === false) {
                return false;
            }

            unset($results);

            $page++;
        } while ($countResults == $count);

        return true;
    }
}
```

    Bạn cũng có thể sử dụng trình tạo hoặc LazyCollection để khai triển ChunkById(). 

# Using the queue

Tương tự như phân đoạn, xử lý phân trang bên trong 1 hàng đợi có thể giúp bạn xử lý số lượng lớn dữ liệu hiệu quả hơn bằng cách chia nó thành cách công việc bộ nhớ hiệu quả. Điều này là đặc biệt hữu ích khi làm việc với số lượng lớn dữ liệu cái mà có thể chậm để xử lý. 

Một tuần trước, chúng ta dã thấy những lơi ích quan trọng và những phương thức để xử lý phân trang với hàng đợi 


# Relationships
 Xây dựng 1 mối liên hệ giữa các requests của API có chút khác với truy vấn. Vì vậy chúng tôi phải đi khác một chút so với Eloquent. Nhiều lúc tôi thấy chúng dường như được mô tả bởi 1 url hoàn chỉnh để tìm nạp các thông tin liên quan 
vì bạn có thể thiết kế tất cả các phương thức relationship để trả về RequestBuilder nơi mà url là của các dữ liệu liên quan .

```php
abstract class Model
{
    use ForwardsCalls;

    abstract public function url(): string;
    
    public function belongsTo($model, $path): RequestBuilder
    {
    	return $this->newRequest(
            new $model
        )->url($this->getAttribute($path));
    }
    
    // newRequest()
    // __call()
    // __callStatic()
}
```
Một khi tất cả các phương thức quan hệ được tạo, bạn có thể bắt đầu sử dụng chúng bằng cách truyền vào đường đẫn của giá trị cái sử dụng ký tự `.` của laravel 

```php
class Ticket extends Model
{
    // public function url(): string
    
    public function board(): RequestBuilder
    {
    	return $this->belongsTo(Board::class, 'board._info.board_href');
    }
}
```

Sử dụng các mối quan  hệ mới trông khá thân thiện 

```php

Ticket::first()->board()->first();
```
Với việc sử dụng một phương pháp ma thuật khác, điều này trở nên khá đơn giản.

```php

class Ticket extends Model
{
    // public function board(): RequestBuilder
    
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->getRelationValue($name);
        }
    }
    
    public function getRelationValue($key): Collection
    {
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        return tap($this->$method()->get(), function ($results) use ($method) {
            $this->relations[$relation] = $results;
        });
    }
}
```

# Xử lý hết hạn token 

ConnectWise không sử dụng các mã có thời hạn, vì diều này là không thực sự được chỉ định cho chúng, Cho những cái đó, xử lý những hết hạn với những cố gắng có thể là cach để thực hiện 
```php

public function boot()
{
    Http::macro('connectwise', function () {
        $settings = config('services.connectwise');
        
        return Http::baseUrl($settings['url'])
            ->withToken($this->getToken())
            ->retry(1, 0, function ($exception, $request) {
                if ($exception instanceof TokenExpiredException) {
                    $request->withToken($this->getNewToken());
                    
                    return true;
                }

                return false;
            })
            ->asJson()
            ->acceptJson()
            ->withHeaders(['clientId' => $settings['client_id']])
            ->throw();
    });
}
```
Điều này không phải lúc nào cũng có thể. Đối với một số dịch vụ, người dùng sẽ được chuyển hướng đến trang đăng nhập để nhận mã thông báo mới. Nó sẽ hữu ích cho những người hoạt động giống như tích hợp hoặc máy khách với máy chủ.

# Tỷ lệ giới hạn 

Giống như mã hết hạn, giới hạn tỷ lệ có thể đưọc sử lý trong marco Http. Nếu đó là phần trung gian duy nhất bạn cần, nó có thể là tùy chọn đơn giản nhất. 

Ngoài ra, bạn có thể thêm nó vào model cơ sở và bao gồm bất kì các middleware nào ở đó. Tùy chọn ghi đề những thứ đó trong model của bạn 

```php

abstract class Model
{
    public function middleware(): array
    {
        return [];
    }
}
```
```php

class Ticket extend Model
{
    public function middleware(): array
    {
        return [
            new ThrottleRequests(
                key: 'tickets',
                maxAttempts: 30,
            ),
        ];
    }
}
```
     Các chi tiết cụ thể về giới hạn tốc độ được ghi lại đầy đủ trong Laravel. Đọc nó sẽ cung cấp cho bạn một số định hướng tốt về lớp ThrottlesRequests này có thể trông như thế nào.

Nếu bạn đi theo lộ trình này, các phương thức CRUD của RequestBuilder sẽ cần được cập nhật để áp dụng phần mềm trung gian cho PendingRequest trước khi gửi yêu cầu.

# Kết luận 

Có nhiều cách để viết SDK và cách tiếp cận tốt nhất cho một dự án hoặc tổ chức cụ thể sẽ phụ thuộc vào các yêu cầu và mục tiêu cụ thể của nó. Rất tiếc, không có một SDK nào có thể thống trị tất cả.

Tôi thích tạo SDK theo cách này vì tiền gốc đầu tư:

> Lau sạch lớp bụi cho các đặc tính hàng tuần hàng tháng hàng năm , tôi muốn giảm số lần vò đầu cho tới khi tôi hiểu 
> được cái gì mà tôi đang thấy. SỬ dụng Laravel trong nhiều năm tối nhận thấy các APIs lõi dễ để quay lại. Vì vậy, thiết kế những thứ trong cách mà giống với cách mà Laravel làm là 
> 1 đầu tư cho tương lai 


Một lợi ích bổ sung khác là khi đến lúc giới thiệu nó với một thành viên mới trong nhóm, một người đã quen thuộc với Eloquent, họ sẽ ngay lập tức biết cách sử dụng nó. Cung cấp một API nhất quán và có thể dự đoán để họ sử dụng cho phép họ nhanh chóng làm việc hiệu quả mà không cần phải nắm tay nhiều