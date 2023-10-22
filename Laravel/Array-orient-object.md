## from [https://wendelladriel.com/blog/avoid-the-aop-array-oriented-programming](https://wendelladriel.com/blog/avoid-the-aop-array-oriented-programming)


# Summary 


Arrays là 1 cách thuận tiện cho việc tổ chức và truyền dữ liệu trong các ứng dụng PHP. Nhưng chúng ta nên sử dụng nó như nào. 
Bài viết này nói  về những nhược điểm của của nảng kết hợp và cách thay thế nó. 

1. Mảng kết hợp là gì

Mảng kết hợp trong PHP là một loại mảng sử dụng cặp key-value cho việc lưu dữ liệu trong 1 định dạng cấu trúc. Những mảng này cho phép quản lý dữ liệu 1 cách hiệu quả với các `key`  thay vì các quy ước giá trị chỉ mục như 1,2,3... trực quan hơn và dễ đọc hơn. 

Mỗi key trong mảng kết hợp trong PHP là duy nhất và có thể chỉ tham chiếu tới 1 giá trị duy nhất. Những key này có thể được đặt tên là bất cứ kỳ tên gì, hoặc là chuoxi hoặc là 1 số, cho phép 1 phương thức linh hoạt để truy cập và thao tác dữ liệu. 

2. Lợi ích của mảng kết hợp. 

Mảng kết hợp trong PHP có nhiều lợi ích: 

- Chúng có tính linh hoạt cao vì chúng cho phép sử dụng chuỗi cho các chi mục đặc biệt, làm cho code dễ đọc hơn và truy cập hơn vì các nhà phát triển ó thể sử dụng các tên có ý nghĩa hoặc các mô tả như keys. 
- CHúng có thể đơn giản việc tìm kiếm dữ liệu và quản lý vì bạn có thê truy cập các giá trị trực tiếp thông qua các key đặc biệt, loại bỏ việc phải lặp qua các phần tử. 
- Bên cạnh đó, PHP cung cấp nhiều các hàm dựng  sắn có thể xử lý mảng kết hợp hiệu quả. 
Besides, PHP provides a plethora of built-in functions that can handle associative arrays effectively, thus accelerating the process of sorting, merging, dividing, and filtering arrays.

3. AOP - Array-Oriented Programming là gì

Đây không phải là 1 khái niệm thực, nhưng nó là khá phổ biến trong nhiều ứng dụng PHP. Gọi AOP khi codebase đang sử dụng Mảng kết hợp trong hầu hết các phần. 


Ví dụ về việc sử dụng Mảng kết hợp có thể gây hại cho mã của bạn khi nó bắt đầu lớn hoặc team của bạn bắt đầu lớn hơn. 


Nếu bạn đã từng làm việc với các frame PHP bạn có lẽ quen với việc sử dụng thường xuyên dữ liệu từ HTTP request thường đươcc nhận như 1 mảng khi chúng ta cần validate/ sử dụng nó. 



```php 
// ProductController.php

final class ProductController extends Controller
{
    public function __construct(private ProductService $service)
		{
		}
	
    public function index(ProductsListRequest $request): JsonResponse
		{
		    return response()->json($this->service->productsList($request->validated()));
		}
}

// ProductService.php

final class ProductService
{
    public function productsList(array $filters): Collection
		{
		    $query = Product::query()
				    ->select(['id', 'name', 'price']);
			
		    if (isset($filters['search'])) {
				    $query->where('name', 'LIKE', "%{$filters['search']}%");
				}
			
		    if (isset($filters['min_price'])) {
				    $query->where('price', '>=', $filters['min_price']);
				}
			
	      if (isset($filters['max_price'])) {
				    $query->where('price', '<=', $filters['max_price']);
				}
		}
}
```

Đầu tiên nhìn có vẻ không có lỗi gì với mã ở trên, và thực tế là nó không sai với team nhỏ, tuân theo 1 quy trình làm việc tiêu chuẩn và mã cơ sở không lớn.
Mặc dù vậy, DX không phải là thứ tốt nhất, vì nếu ai đó cần thay đổi logic của phương thức ProductList, thì nhà phát triển cũng cần phải kiểm tra lớp ProductListRequest và đảm bảo rằng nó được cập nhật với tất cả các thay đổi xảy ra với các bộ lọc được truyền qua giao diện người dùng.

Vấn đề chính ở đây và ở tất cả những nơi chúng tôi sử dụng Mảng kết hợp đều giống nhau: bạn không có bất kỳ thông tin nào về dữ liệu mà mảng chứa. Nó có thể là bất cứ thứ gì và thậm chí tệ hơn, mỗi mục trong mảng có thể là thứ gì đó hoàn toàn khác nhau! Dữ liệu không có ngữ cảnh nào cả.

Một ví dụ khác về việc lạm dụng mảng kết hợp trog giá trị trả về của phương thức: 

```php 
public function getUserContactInfo(int $userId): array
{
    $user = User::query()->firstOrFail($userId);
	
	   // LOGIC HERE
	
    return [
		    'street' => 'Foo',
			  'number' => 1,
			  'zip' => '123456',
			  'phone' => '123456789',
		];
}
```
Tương tự như ví dụ trên, không có gì sai nếu tuân thủ theo đúng quy trình và mã cơ sở nhỏ. Nhưng tưởng tưởng, nếu 1 thành viên nào đó xóa key 'phone' đi 
và các thành viên khác nghĩ nó vẫn còn và vẫn tiếp tục sử dụng và dẫn đến lỗi. 


4. Nhược điểm khác: 

ột điều khác cần lưu ý và đó là mối quan tâm hàng đầu khi xử lý Mảng kết hợp là việc sử dụng bộ nhớ. Chúng có thể khá tốn nhiều bộ nhớ, đặc biệt với các tập dữ liệu lớn, vì chúng lưu trữ thông tin bổ sung (khóa) ngoài các giá trị. Do đó, hiệu suất có thể bị ảnh hưởng xấu trong các ứng dụng không được mã hóa tối ưu.

Ngoài ra, các mảng trong PHP không được gõ mạnh, điều đó có nghĩa là bạn có thể vô tình lưu trữ các loại giá trị khác nhau trong cùng một mảng, dẫn đến các lỗi tiềm ẩn hoặc việc sử dụng bộ nhớ không hiệu quả.
5. Hiểu mã của bạn

vấn đề chính gây ra tranh cãi với việc sử dụng mảng kết hợp là nó làm cho mã cở sở mơ hồ.Nó đòi hỏi chúng ta cần phải bỏ thời gian 
để tìm hiểu các đoạn mã khác để hiểu dữ liệu trong mang là loại gì. 

Cách khắc phục là gì? Đơn giản: hãy sử dụng DTO( data transfer object)


```php 
final readonly class ProductFilters(
    public ?string $search = null,
    public ?float $min_price = null,
    public ?float $max_price = null,
) {}
```

```php 
final class ProductService
{
    public function productsList(ProductFilters $filters): Collection
		{
		    $query = Product::query()
				    ->select(['id', 'name', 'price']);
			
		    if (! blank($filters->search)) {
				    $query->where('name', 'LIKE', "%{$filters->search}%");
				}
			
		    if (! blank($filters->min_price)) {
				    $query->where('price', '>=', $filters->min_price);
				}
			
	      if (! blank($filters->max_price)) {
				    $query->where('price', '<=', $filters->max_price);
				}
		}
}
```

Bản thân mã không thay đổi nhiều, nhưng ngữ cảnh của dữ liệu trong mã đó hoàn toàn khác . Bây giờ bạn có một đối tượng ProductFilters là một DTO bao bọc các bộ lọc bạn cần và cung cấp ngữ cảnh cho chúng. Nếu bất kỳ ai cần cập nhật bất kỳ điều gì theo phương pháp này: khắc phục sự cố hoặc mở rộng sự cố bằng bộ lọc mới thì đó là một nhiệm vụ khá đơn giản và dễ hiểu vì bạn có tất cả các bộ lọc có thể được liệt kê và bạn biết chính xác loại dữ liệu mà mỗi bộ lọc chứa.

```php 
final readonly class UserContactInfo(
    public string $street,
	  public int $number,
	  public ?string $zip,
	  public ?string $phone,
) {}
```

```php 
public function getUserContactInfo(int $userId): UserContactInfo
{
    $user = User::query()->firstOrFail($userId);
	
	   // LOGIC HERE
	
    return new UserContactInfo(
		    street: 'Foo',
			  number: 1,
			  zip: '123456',
			  phone: '123456789',
		);
}
```