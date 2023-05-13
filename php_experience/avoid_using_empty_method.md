
# from  [https://localheinz.com/articles/2023/05/10/avoiding-empty-in-php/](https://localheinz.com/articles/2023/05/10/avoiding-empty-in-php/)

# Tránh sử dụng empty(). 
Hàm cấu trúc ngôn ngữ empty() xuất hiện khá linh hoạt. Nó  giống như 1 con dao quân đội Thụy Sỹ với hàng ngàn lưỡi, sẵn sàng làm đau bạn nếu bạn cầm nó sai cách. Hoặc giống như bạn biết nhiều thứ, nhưng không giỏi bất cứ điều gì. Hầu hết các trường hợp, empty() là 1 công cụ giao tiếp kém.  

Tôi nhận ra empty() trong các dự án mã nguồn đóng gần đây. Tôi phát hiện ra empty() trong các  dự án mã nguồn đóng thưong hiệu mới từ tuần trước. Và tôi đã gặp nó lịa trong các dự án mã nguồn mở với hàng triệu lượt tải. 

Vậy vấn đề là gì với hàm empty() khi quá nhiều người sử dụng nó.?


## Vấn đề 

Nếu bạn tham khảo tài liệu của hàm empty(), bạn sẽ thấy những điều sau: 

    Xác định một biến được cân nhắc là trống hay không. Một biến được xem là trống nếu nó không tồn tại hoặc nếu giá trị của nó tương ứng với false. Hàm empty() không sinh ra cảnh báo nếu biến không tồn tại. 

    [PHP manual: empty()](https://www.php.net/manual/en/function.empty.php)

 Nếu bạn bỏ qua các khai triển bên trong và có thể các vấn đề hiệu năng, sử dụng `empty()` là tương tự sử dụng `isset()` và nó là 1 so sánh lỏng lẻo với false. 

```php 
 <?php

 declare(strict_types=1);

-if (empty($value)) {
+if (!isset($value) || $value == false) {
     // ...
 }
```

Khi bạn đã biết rằng 1 biến, một thuộc tính, tham số hàm hay phương thức, và 1 hàm hoặc một phương thức trả về giá trị được định nghĩa, tại sao bạn muốn sử dụng `isset()`


Khi bạn đã biết rằng 1 biến, một thuộc tính, 1 đối số hàm  và 1 hàm trả về các giá trị giả sử như nhiều kiểu loại, tại sao bạn muốn sử dụng so sánh lỏng lẻo hoặc không xử lý cho từng kiểu có thể chấp nhận và các giá trị riêng rẽ? 

Khi bạn đã biết rằng một biến, một thuộc tính, một đối số của hàm hay phương thức và một hàm hoặc phương thức trả về giá trị giả sử là 1 kiểu đơn, tại sao bạn muốn sử dụng kiểu lỏng lẻo thay vì so sánh chặt chẽ.? 

Việc sử dụng `empty(), isset()` hoặc so sánh lỏng lẻo là một công viêc mơ hồ. Đó là cách mà bạn muốn làm?

Như đã đề cập, tôi thường thấy làm `empty()` trong các code cũ. Một vài dự án đó chạy các phiên bản đã hết hạn của PHP và hoàn toàn không biết về khai báo thuộc tính, tham số và khai báo kiểu tra về. Trong các dự án đó, bạn  thường không thể tìm thấy `DocBlocks` cho các thuộc tính, hàm và các tham số phương thức hoặc kiểu trả về. 

Khi bạn nhận bảo trì các dự án đó, và bắt đầu sửa lỗi, cập nhập phiên bản PHP và cuối cùng hiện đại hóa chúng, bạn sẽ phát hiện ra nó  khó khăn để hiểu cái mà tác giả ban đầu dự định làm khi họ sử dụng `empty()`. 

Tác giả đầu tiên có nhận thức được điều kỳ quặc của    `empty()`? Các tác giả có ý định 1 thuộc tính, tham số của các 1 hàm hoặc 1 phương thức chấp nhận tất cả các kiểu và giá trị được nhận? Các tác giả có kế hoặc để trả về tất cả các kiểu và các giá trị từ các hàm hoặc phương thức? Các tác giả, người mà đã lâu không xuất hiện và làm mở các chủ dự án,thực sự nghĩ việc sử dụng thông qua empty()?

Tác giả ban đầu có thể là bạn.  Người bảo trì có thể là bạn, nhận các dự án sau nhiều năm. Có lẽ, bạn bị đột quỵ hoặc gặp tai nạn trong thời gian ý nghĩa đó làm cho bạn mất khả năng nhận thức. Bây giờ bạn gặp khó khăn để hiểu cái mà tác giả ban đầu định làm.  Có lẽ tác giả và người bảo trì là những người hoàn toàn khác nhau. Có lẽ bạn là 1 người có sức khoẻ hoàn hỏa và cũng vẫn khó khăn để hiểu ý định của các tác giả ban đầu. 

Trong khi bạn viết mã cho máy tính để thực thi, bạn - như là tác giả - cũng viết mã cho những người bảo trì nó sau này. Bằng việc cận thận nhiều hoặc ít việc lựa chọn các đặc tính ngôn ngữ, bạn  hướng dẫn máy tính nhưng bạn cũng trao đổi những dự định của bạn với người bảo trì. 

     Mấy thằng ngốc có thể viết mã để máy tính hiểu. Các lập trình viên tốt viết mã con người có thể hiểu. 

    Martin Fowler, Refactoring

 Hãy xem tất cả các trường hợp khi hàm `empty()` trả về true và khám phá các thay thế tốt hơn các ý định và nhận thức tình huống của bạn. 


## các ứng viên 

- undefined variable
- undefined instance property
- undefined static property
- inaccessible instance property
- inaccessible static property
- null
- array
- bool
- float
- int
- string
- SimpleXMLElement

## undefined variable 

empty() trả về `true` khi biến chưa xác định 

```php
<?php

declare(strict_types=1);

var_dump(empty($value)); // (bool)true
```
Các tình huống mà bạn đang sử dụng empty() để xác định xem 1 biến có là `undefined`?

##  Tình huống: Mong chờ 1 tệp bao gồm để khai báo 1 biến 

 Bạn `include ` 1 tệp cái mà bạn mong chờ để khai báo 1 biến. Tệp có thể không tồn tại hoặc có thể không khai báo biến. 
Thay vì sử dụng empty() để xác minh xem 1 tệp đã bao gồm có tồn tại và khai báo 1 biến không, thiết lập biến để với 1 giá trị mặc định phù hợp và  bap gồm tệp nếu nó tồn tại và xác định xem boeens có 1 kiểu và giá trị có thể chấp nhận. 

```php
 <?php

 <?php

 declare(strict_types=1);

-include __DIR__ . '/file.php';
+$file = __DIR__ . '/file.php';

-if (empty($value)) {
-    // ....
-}
+
+$value = [];
+
+if (is_file($file)) {
+    $value = include $file;
+
+    if (!is_array($value)) {
+        // ...
+    }
+}

```
>💡 Tránh viết mã cái mà dựa trên việc bao gồm các tệp khai báo biến hoặc trả về các giả trị. 

## Undefined instance property

`empty()` trả về `true` khi các đối số là 1 thuộc tính thực thể không xác định. 

```php 
<?php

declare(strict_types=1);

$object = new stdClass();

var_dump(empty($object->value)); // (bool)true
```
Như 1 hiệu ứng phụ, `empty()`cũng gọi đến hàm magic `__isset()` khi bạn tham chiếu 1 thuộc tính thực thể không xác định của 1 đối tượng cái mà khai báo 1 phương thức `__isset()`

```php 

<?php

declare(strict_types=1);

$object = new class() {
    public function __isset(string $name): bool
    {
        echo '👋';

        return true;
    }
};

var_dump(empty($object->value)); // 👋(bool)true
```
Các trường hợp mà bạn đang sử dụng empty() và làm việc với các thuộc tính thực thể không xác định? 

## Undefined static property

Hàm `empty()` trả về `true` khi mà đối số là 1 thuộc tính tĩnh không xác định. 

```php 
<?php

declare(strict_types=1);

$object = new stdClass();

var_dump(empty($object::$value)); // (bool)true
```
Các trưởng hợp mà bạn sử dụng `empty()` và làm việc với 1 thuộc tính tĩnh không xác định? 

## Inaccessible instance property

`empty()` trả về `true` khi mà đối số là 1 thuộc tính thực thể không thể truy cập. 
 Như 1 hiệu ứng phụ, hàm sẽ gọi phương thức magic `__isset()` khi nó tồn tại. 

```php 
<?php

declare(strict_types=1);

$object = new class() {
    private $value = 9000;
    protected $otherValue = 9001;

    public function __isset(string $name): bool
    {
        echo '👋';

        return true;
    }
};

var_dump(empty($object->value)); // 👋(bool)true
var_dump(empty($object->otherValue)); // 👋(bool)true
```

## Inaccessible static property
`empty()` trả về `true` khi tham số là 1 thuộc tính tĩnh không thể truy cập 
```php 
<?php

declare(strict_types=1);

$object = new class() {
    private static $value = 9000;
    protected static $otherValue = 9000;
};

var_dump(empty($object::$value)); // (bool)true
var_dump(empty($object::$otherValue)); // (bool)true
```


## TRường hợp: Khai báo các biến trong các tệp và `includong` 

### Null 
`empty()` trả về `true` khi tham số là `null`
```php
<?php

declare(strict_types=1);

$value = null;

var_dump(empty($value)); // (bool)true
```

## Scenario: Instance or static property could be null

Bạn có 1 `class` với `instance` hoặc một `static property` và đang sử dụng `empty()` để xác định giá trị của thuộc tính. 

Thay vì sử dụng empty() để xác định giá trị của thuộc tính khi thuộc tính có thể giả sử có nhiều kiểu,  so sánh thực thể hoặc các thuộc tính tĩnh với null  và xử lý mỗi trường hợp có thể một cách riêng lẻ. 

```php 
<?php

 declare(strict_types=1);

 final class Foo
 {
     private $value;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === null) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
 

```

 Thay vì sử dụng `empty()` để xác minh giá trị của thuộc tính khi thuộc tính có thể là null hoặc 1 kiểu được biết, thêm 1 khai báo thuộc tính có thể null  và so sánh giá trị với null

```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    private $value;
+    private ?string $value = null;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === null) {
             // ...
         }

         // ...
     }
 }
```

> Tránh viết các lớp với các thực thể hoặc các thuộc tính tĩnh cái mà chấp nhận nhiều kiểu. Thêm khai báo kiểu thuộc tính hoặc DocBlock tới  tài liệu kiểu thuộc tính 

## Scenario: Function or method parameter could be null

Bạn có 1 hàm hoặc phương thức với 1 tham số có thể là `null`

Thay vì sử dụng `empty()` để xác minh giá trị của giá trị tham số khi mà các tham số có thể giả sử là nhiều kiểu dữ liệu, so sánh các tham số với null  và sử lý mỗi trường hợp có thể xảy ra. 

```php 
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar($value)
     {
-        if (empty($value)) {
+        if ($value === null) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }

```
 Thay vì sửu dụng empty() để xác minh giá trị các tham số khi mà các tham số có thể null hoặc là 1 kiểu được biết, thêm  khai báo kiểu tham số có thể null  và so sánh tham số với null. 

```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar($value)
+    public function bar(?string $value)
     {
-        if (empty($value)) {
+        if ($value === null) {
             // ...
         }

         // ...
     }
 }
```

> 💡 Tránh viết các hàm hoặc phương thức với các tham số chấp nhận nhiều kiểu. Thêm khai báo kiểu hoặc DocBlocks tới tài liệu kiểu tham số phương thức hoặc hàm. 

##  Trường hợp: Hàm hoặc phương thức có thể trả về một mảng trống. 

 Bạn có 1 hàm hoặc phương thức với 1 giá trị trả về có thể là 1 mảng rỗng. 
Thay vì sử dụng `empty()` để xác minh giá trị trả về ở cuộc gọi khi mà giá trị trả về có thể nhiều kiểu, so sánh giá trị trả về với mảng rỗng và xử lý các trường hợp riêng lẻ. 

```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar()
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === []) {
     // ...
 }

+// handle other possible types and values
```
 Thay vì sử dung empty() để xác minh giá trị trả về ở cuộc gọi khi giá trị trả về có thể giả sử là mảng rỗng và thê, 1 kiểu khai báo trả về mảng  và so sánh giá trị với mảng rỗng. 

```php 
<?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar()
+    public function bar(): array
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === []) {
     // ...
 }
 ```

>💡 Tránh viết cac hàm hoặc phương thức cái mà trả về các giá trị của nhiều kiểu. Thêm khai báo kiểu tar về hoặc DocBlock tới tài liệu kiểu trả về của hàm hoặc phuong thức. 



## Boolean 

`empty()` trả về `true` khi mà đối số là 1 bool với giá trị là `fasle`.  
```php 
<?php

declare(strict_types=1);

$value = false;

var_dump(empty($value)); // (bool)true
```
## Trường hợp: Thuộc tính tĩnh hoăc thực thể có thể là false 

 Bạn có 1 class với một thuộc tính tĩnh hoặc thuộc tính thực thể và hiện tại bạn sử dụng empty() để xác định giá trị của thuộc tính. 

Thay vì sử dụng empty() để xác minh giá trị  của thuộc tính khi thuộc tính giả sử có thể nhiều kiểu, so sánh thuộc tính với false  và xử lý các trường hợp có thể xảy ra, 

```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
     private $value;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === false) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
```
Thay vì sử dụng empty() để xác minh giá trị thuộc tính khi thuộc tính có thể giả sử là 1 `bool`, thêm khai báo thuộc tính  bool và sử dụng biểu thức logic. 

```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    private $value;
+    private bool $value = false;

     public function bar()
     {
-        if (empty($this->value)) {
+        if (!$this->value) {
             // ...
         }

         // ...
     }
 } 
```

> 💡 Tránh viết class với các thuộc tính chấp nhận nhiều kiểu. Thêm khai báo kiểu thuộc tính hoặc DocBlock tới tài liệu kiểu thuộc tính. 

##  Trường hợp: đối số hàm hoặc phương thức có thể faslse. 
 Bạn có hàm hoặc phương thức với tham số có thể là 1 mảng rỗng 
Thay vì sử dụng empty() để xác minh giá trị khi tham số có thể giả sử là nhiều kiểu, so sánh tham số với false và xử lý từng trường hợp có thể riêng lẻ. 

```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar($value)
     {
-        if (empty($value)) {
+        if ($value === false) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
 ```
 Thay vì sử dụng empty() để xác minh giá trị tham số khi các tham số có thể giả sử là 1 bool, thêm khai báo kiểu tham số bool và sử dụng biểu thức logic. 
```php 
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar($value)
+    public function bar(bool $value)
     {
-        if (empty($value)) {
+        if (!$value) {
             // ...
         }

         // ...
     }
 }
 ```

>💡 Thay vì viết các hàm hoặc các phương thức với các tham số chấp nhận nhiều kiểu Thêm khai báo kiểu hoặc DocBlock tới tài liệu kiểu tham số của phương thức hoặc hàm. 

## Trường hớp: hàm hoặc phương thức có thể trả về false. 

Bạn có 1 hàm hoặc 1 phương thức với giá trị trả về cái mà có thể là bool với giá trị false. 
Thay vì sử dụng empty() để xác định giá trị trả về ở vị trí gọi khi giá trị trả về có thể là nhiều kiểu, so sánh giá trị trả về với false và sử lý mỗi trường hợp riêng lẻ. 

```php 
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar()
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === false) {
     // ...
 }

+// handle other possible types and values
```
 Thay vi sử dụng empty() để xác minh giá trị trả về ở ví trí cuộc gọi khi giá trị trả về có thể là bool, thêm khai bóa kiểu trả về bool và sử dụng 1 biểu thức logic. 

```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar()
+    public function bar(): bool
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if (!$foo->bar()) {
     // ...
 }
```
> 💡 Tránh sử dụng các hàm hoặc các phương thức cái mà giá trị tra về có thể là nhiều kiểu. Thêm khai báo kiểu trả về hoặc DocBlock tới tài liệu kiểu trả về của hàm hoặc phương thức. 

## Float
empy() trả về true khi mà 1 biến là 1 float với giá trị là `0.0` hoặc `-0.0`

```php
<?php

declare(strict_types=1);

$value = 0.0;

var_dump(empty($value)); // (bool)true
```

##  Trường hợp: Thuộc tính tĩnh hoặc thực thể là 0.0

 Bạn có 1 lớp với thuộc tính tĩnh hoặc thực thể và hiện sử dụng `empty()` để xác định giá trị thuộc tính.
Thay vì sử dụng `empty()` để xác định giá trị thuộc tính khi thuộc tính có thể có nhiều kiểu, so sánh thuộc tính với 0.0 hoặc -0.0 và xử lý các trường hợp có thể riêng biệt.

```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     private $value;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === 0.0) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
```
Thay vì dùng empty() để xác định giá trị của thuộc tính khi thuộc tính có thể là `float`,  thêm 1 khai báo thuộc tính float và so sánh thuộc tính với 0.0 hoặc -0.0.

```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    private $value;
+    private float $value = 0;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === 0.0) {
             // ...
         }

         // ...
     }
 }

```
> Tránh sử dụng class với các thuôc tính có thể nhận nhiều kiểu. Thêm khai báo thuộc tính kiểu hoặc DocBlock tới tài liệu kiểu thuộc tính.

## Trường hợp: Tham số hàm hoặc phương thức có thể là 0.0

 Bạn có một hàm hoặc 1 phương thức với 1 tham số cái mà có thể là 1 số thực với giá trị là 0.0 hoặc -0.0.

Thay vì sử dụng `empty()` để xác định giá trị tham số khi tham số có thể là đa kiểu, so sánh tham số với 0.0 hoặc -0.0. và xử lý các trường hợp có thể xảy ra riêng.

```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar($value)
     {
-        if (empty($value)) {
+        if ($value === 0.0) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
Instead of using empty() to verify the parameter value when the parameter can assume a float, add a float parameter type declaration and compare the parameter with 0.0 or -0.0.

 <?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar($value)
+    public function bar(float $value)
     {
-        if (empty($value)) {
+        if ($value === 0.0) {
             // ...
         }

         // ...
     }
 }
```
> tránh sử dụng các hàm hoặc phương thức với tham số nhận nhiều kiểu. Thêm khai báo kiểu tham số hoặc DocBlock.



## Trường hợp: Hàm có thể trả về 0.0 hoặc -0.0
 Bạn có hàm có thể trả vè giá trị có thể là `float` với giá trị là 0.0 hoặc -0.0
Thay vì dùng `empty()` để xác định giá trị  trả về ở vị trí gọi, khi trả về giá trị có thể là nhiều kiểu, so sánh với 0.0 hoặc -0.0  và xử lý các trường hợp có thể rieeng lẻ.
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar()
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === 0.0) {
     // ...
 }

+// handle other possible types and values

```
Thay vì sử dụng empty() để xác định giá trị trả về ỏ vị trí gọi khi giá trị trả về có thể là float, thêm khai báo giá trị trả về float và so sánh giá trị trả về với 0.0 hoặc -0.0

```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar()
+    public function bar(): float
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === 0.0) {
     // ...
 }
```

## int 
empty() trả về `true` khi mà đối số là int với giá trị 0
```php
<?php

declare(strict_types=1);

$value = 0;

var_dump(empty($value)); // // (bool)true
```

### Trường hợp: Thuộc tính có thể là 0
 Bạn có `class` vơi thuộc tính tĩnh hoặc thực thể và hiện đang sử dụng `empty()` để xác định giá trị thuộc tính.
Thay vì sử dụng `empty()` để xác định giá trị thuộc tính khi thuộc tính có thể nhiều kiểu, so sánh thuộc tính với 0 và xử lý các trường hợp có thể riêng lẻ. 
```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
     private $value;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === 0) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
```
 Thay vì dùng empty() để xác định giá trị khi thuộc tính có thể giả sử là `int`, thêm khai báo thuộc tính kiểu int và so sánh thuộc tính với 0.
```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    private $value;
+    private int $value = 0;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === 0) {
             // ...
         }

         // ...
     }
 }

```
> Tránh sử dụng class với các thuộc tính có thể nhận nhiều kiểu. Sử dụng khai báo kiểu thuộc tính hoặc DocBlock.


##  Trường hợp: Tham số hàm hoặc method có thể là 0
Bạn có hàm hoặc phương thức với 1 tham số có thể là int với giá trị 0.

Thay vì sử dụng empty() để xác định giá trị tham số khi tham số có thể giả sử là nhiều kiểu, so sánh tham số với 0 và xử lý các trường hợp có thể xảy ra riêng lẻ. 
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar($value)
     {
-        if (empty($value)) {
+        if ($value === 0) {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }

```
 Thay vì sử dụng empty() để xác định giá trị tham số khi tham số có giả sử là int, thêm khai báo kiểu của tham số int và so sánh tham số với 0.
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar($value)
+    public function bar(int $value)
     {
-        if (empty($value)) {
+        if ($value === 0) {
             // ...
         }

         // ...
     }
 }
💡

```
 > Tránh sử dụng function với tham số có thể nhận nhiều kiểu. Thêm khai báo kiểu tham số hoặc DocBlock.

## Trường hợp: Hàm trả về giá trị có thể là 0.
Bạn có hàm hoặc phương thức có thể trả về giá trị có thể là int với giá trị 0
Thay vì sử dụng empty() để xác minh giá trị trả về ở ví trí gọi, khi giá trị trả về có thể là nhiều kiểu, so sánh với 0 và xử lý các trường hợp có thể xảy ra. 
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar()
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === 0) {
     // ...
 }

+// handle other possible types and values
```
 Thay vì sử dụng empty() để xác minh giá trị trả về ở vị trí gọi khi giá trị trả về giả định là int, thêm khai báo kiểu trả về int và so sánh với 0.

```php
<?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar()
+    public function bar(): int
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === 0) {
     // ...
 }
```
# String 
Empty() trả về true nếu đối số là `string` với giá trị `''`.
```php
<?php

declare(strict_types=1);

$value = '';

var_dump(empty($value)); // // (bool)true

```
### Trường hợp: Thuộc tính có thể là 1 chuỗi rỗng. 
Bạn có class với thuộc tính  và hiện đang sử dụng empty() để xác định giá trị thuộc tính. 
Thay vì sử dụng empty() để xác định giá trị thuộc tính khi thuộc tính có thể là nhiều kiểu, so sánh với `''`hoặc `'0'`  và xử lý các trường hợp có thể riêng lẻ. 

```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     private $value;

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === '') {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
```
  Trường hợp giá trị thuộc tính giả sử là string, thay vì sử dụng `empty()` thêm 1 khai báo giá trị thuộc tính với string và so sánh thuộc tính với '' hoặc '0'.
```php
 <?php

 declare(strict_types=1);

 final class Foo
 {
-    private $value;
+    private string $value = '';

     public function bar()
     {
-        if (empty($this->value)) {
+        if ($this->value === '') {
             // ...
         }

         // ...
     }
 }
```

>💡 Tránh viết class với các thuộc tính có thể chấp nhận nhiều kiểu. Thêm khai báo kiểu thuộc tính hoặc DocBlock tới tài liệu kiểu thuộc tính. 

## TRường hợp: Tham số hàm hoặc phương thức có thể là 1 chuỗi rỗng. 
Bạn có thể có  hàm hoặc phương thức có tham số có thể là 1 chuỗi với giá trị rỗng `''` hoặc (`'0'`).
Thay vì dùng empty() để xác định giá trị tham số khi tham số có thể là nhiều kiể, so sánh với `''` hoặc `'0'` và xử lý các trường hớp có thể riêng lẻ. 
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar($value)
     {
-        if (empty($value)) {
+        if ($value === '') {
             // ...
         }

+        // handle other possible types and values
+
         // ...
     }
 }
```
 Thay vì sử dụng empty() để xác định giá trị của tham số khi tham sso giả định là string, thêm khai báo kiểu tham số string  và so sánh với `''` hoặc `'0'`. 
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar($value)
+    public function bar(string $value)
     {
-        if (empty($value)) {
+        if ($value === '') {
             // ...
         }

         // ...
     }
 }

```
 > Tránh viết các hàm với giá trị tham số có thể nhân nhiều kiểu. Sử dung khai báo kiểu tham số hoặc DocBlock.

## Trường hợp: Hàm trả về giá trị có thể là 1 chuỗi rỗng 
 Bcạn có hàm hoặc phương thức trả về giá trị có thể là string với giá trị là '' hoặc (`'0'`). 

Thay vì sử  dụng empty() để xác định giá trị trả về ở vị trí gọi, khi giá trị  trả về có thể nhiều kiểu, so sánh với '' hoặc ('0') và sử lý các trường hớp có thể riêng lẻ. 

```php
<?php

 declare(strict_types=1);

 final class Foo
 {
     public function bar()
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === '') {
     // ...
 }

+// handle other possible types and values
```
Nếu giả định giá trị trả về là chuỗi, thêm khai báo kiểu tra về của hàm và so sánh giá trị trả về với '' hoặc '0'.
 
```php
<?php

 declare(strict_types=1);

 final class Foo
 {
-    public function bar()
+    public function bar(): string
     {
         // ...

         return $value;
     }
 }

-if (empty($foo->bar()) {
+if ($foo->bar() === '') {
     // ...
 }
```
 > Tránh sử dụng hàm, phương thức với giá trị trả về có thể nhiều kiểu. Thêm khai báo kiểu trả về hoặc DocBlock.

# SimpleXMLElement

empty() Trả về `true` khi đối số là 1 thực thể của SimpleXMLElement được hình thành từ 1 chuỗi XML đại diện 1 phần tử mà không có thuộc tính và các con. 
```php
<?php

declare(strict_types=1);

$value = new SimpleXMLElement('<foo></foo>');

var_dump(empty($value)); // (bool)true
```

# Kết luận 

Không sử dụng `empty()`. Không viết mã cái mà cho phép 1 biến, 1 thuôc tính, 1 tham số hoặc 1 giá trị trả về nhận nhiều kiểu. Sử dụng kiểu so sánh an toàn ('===').









