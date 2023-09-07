## from [https://jump24.co.uk/journal/the-power-of-static-analysis/?ref=laravelnews](https://jump24.co.uk/journal/the-power-of-static-analysis/?ref=laravelnews)


# The power of static analytic

Là nhà phát triển PHP, tất cả chúng ta muốn viết code **sạch**, **dễ bảo trì** cái mà không phá vỡ sản phẩm. Nhưng với dự án lớn hơn, việc theo dõi các vấn đề tiền năng và các bugs có thể ngày càng khó hơn. Điều này là nơi mà các công cụ như *static analysis* có thể hữu ích. Bằng cách phân tích code của bạn mà không cần thực sự thực thi nó, **static analysis** có thể bắt được các lỗi nguy cơ và cải thiện chất lượng code trước khi nó tới sản phẩm. 


Ở Jump24, chúng tôi đã nhận ra rằng **static analysis** là một công cụ mạnh mẽ để cải thiện chất lượng code với tư cách là một nhóm. Trong bài đăng này, chúng ta sẽ thảo luận chúng ta sử dụng PHPStan cho **static analysis** như nào trong công việc phát triển hàng ngày của chúng ta, và nó giúp chúng ta như thế nào để phát hiện các vấn đề tiền ẩn một cách dễ dàng trong quá trình phát triển. 

# Chọn công cụ phân tích tĩnh phù hợp

 Việc chọn đúng công cụ **static analysis** là quan trọng trong bất kỳ dự án nào. Chúng tôi chọn PHPStan như một công cụ để thực hiện phân tích tĩnh code của chúng tôi. PHPStan là một công cụ phân tích tĩnh PHP mã nguồn mở cái mà kiểm tra các lỗi tiền ẩn trong mã của bạn bằng cách phân tích files PHP.

PHPStan là một sự lựa chọn tuyệt vời cho chúng tôi bởi vì nó có lượng quy tắc lớn cái mà có thể bắt các vấn đề tiền ẩn trong mã của chung tôi. Ngoài ra, chúng tôi đã tạo ra các **rules** tùy chỉnh để phù hợp với các yêu cầu cụ thể của chúng tôi. ví dụ, chúng tôi đã tạo ra các quy tắc tùy chỉnh cái mà tập trung vào các tiêu chuẩn mã bên trong và ngăn cản các kiểu lỗi cụ thể cái mà chúng tôi thấy thường xuyên trong cơ sở mã, cái mà chúng tôi tiếp quản và bảo trì. Khi chúng tôi so sánh PHPStan và [Psalm](https://psalm.dev/) chúng tôi đã nhận ra rằng PHPStan có những hỗ trợ tốt hơn cho Laravel nhờ có [Larastan](https://github.com/nunomaduro/larastan), vì vạy bất cứ khi nào chúng tôi sử dung PHPStan, chúng tôi cũng cài thêm Larastan.


# Sử dụng PHPStan trong phát triển hàng ngày. 

 Việc sử dụng PHPstan trong tiến trình phát triển hàng ngày đóng vai trò quan trọng trong việc bắt các lỗi tiền ẩn trước khi chúng xảy ra với sản phẩm. Để đạt được này, có một vài bước chúng ta cần làm đầu tiên để có thể sử dụng PHPStan trong dự án. 


## Cài đặt PHPStan 

Đầu tiên bạn cần cài đặt PHPStan, có một giải thích chi tiết về cách để cài đặt ở [đây](https://phpstan.org/user-guide/getting-started) : 

````php
composer require --dev phpstan/phpstan

````
Sau khi cài đặt, bạn có thể hoặc chạy comman với các tham số yêu cầu hoặc như chúng tôi đã làm, tạo ra 1 tệp phpstan.neon chứa các cấu hình cho phân tích tĩnh của bạn. 

ví dụ về tệp phpstan.neon.: 
```php

parameters:
    level: 9
    paths:
        - app
        - config
        - routes
        - database/factories
    reportUnmatchedIgnoredErrors: true
    checkMissingIterableValueType: false
    checkModelProperties: true
    checkUnusedViews: false

    excludePaths:
        - tests
        - vendor
        - _ide_helper.php
        - _ide_helper_models.php
        - .phpstorm.meta.php
        - node_modules

```

 Như bạn thấy trong file này, chúng toi đã thiết lập mức phân tích  mà chúng tôi muốn chạy trên cơ sở mã ( cao nhất là 9), các đường dẫn chúng tôi muốn côn cụ phân tích và chúng tôi đã thiết lập vài thành phần cấu hình nhỏ cái mà chúng tôi muốn chọn để có các thiết lập mặc định trên cở sở mã. Cũng có phần **excludePaths** cho phép chúng tôi nói với công cụ rằng không phân tích các file, thư mục được liệt kê ở đây. 

Ngoài ra, nó có thể để file phpstan.neon của bạn chứa các file khác. ở Jump24, chúng tôi có 1 tệp phpstan.neon, cai mà tồn tại trong các gói tiêu chuẩn của chúng tôi, cái mà chúng tôi sử dụng trong tất cả các dự án laravel. Tệp này thiết lập 1 số lượng các rules co bản cái mà chúng tôi muốn xuất hiện trong quá trình phân tích cơ sở mã.

Một khi chúng tôi đã cài đặt dược gói và thêm các file liên quan, sau đó, chúng ta sử dụng lệnh composer đơn giản để chạy PHPStan trong mã cơ sở: 

Đầu tiên, cập nhật file **composer.json** bằng cách thêm mã sau vào trong vùng scripts:


```php 

"scripts": {
      "phpstan": [
          "Composer\\Config::disableProcessTimeout",
          "vendor/bin/phpstan analyse --memory-limit=4G"
      ],
  }
  
  
```
 Sau đó chạy command sau trong terminal để phân tích code của bạn: 


```php 

composer phpstan

```

Lệnh này chạy PHPStan trong tất cả cacs tệp PHP trong dự án của bạn , cái mà bạn đã định nghĩa trong tệp cấu hình ở trên, và xuất ra một báo cáo của bất kỳ các vấn đề tiềm ẩn nào nó tìm thấy. 

Một lợi ích của việc sử dụng PHPstan là nó có thể bắt các lỗi tiềm ẩn với  gợi ý kiểu và an toàn kiểu. Ví dụ, cân nhắc hàm rất đơn giản này để cộng hai số nguyên với nhau: 


```php 

function addTwoNumbers(int $numberOne, int $numberTwo): int {
  return $numberOne + $numberTwo;
}

$result = addTwoNumbers(1, '2');
```
 

code này chạy mà không có bất kỳ lỗi nào, nhưng nó rõ ràng sai vì chúng ta đã sử dụng sai kiểu cho tham số thứ hai được truyền cho phương thức addTwoNumbers, chúng ta đã truyền chuỗi khi à phương thức yêu cầu int. Do PHP không phải là một ngôn ngữ nghiêm nghặt theo mặc định, code này vẫn chạy và ban vẫn sẽ nhận được kết quả đúng. Bây giờ nếu bạn thực hiện những thau đổi dưới đây với mã ở trên: 

```php 
declare(strict_types=1);

function addTwoNumbers(int $numberOne, int $numberTwo): int {
  return $numberOne + $numberTwo;
}

$result = addTwoNumbers(1, '2');
```
 Sau dó thử lại và chạy mã trên, bạn sẽ nhận được lỗi sau: 


```php 
Fatal error: Uncaught TypeError: Argument 2 passed to addTwoNumbers() must be of the type int, string given

```
Nếu bạn chay mã này thông qua công cụ phân tích tĩnh của bạn, bạn sẽ nhận được lỗi tương tự với điều này: 
```php 
Parameter #2 $numberTwo of function addTwoNumbers() expects int, string given.

```
 Mặc dù điều này là một ví dụ đơn giản, nó thể hiện cho ta thấy việc sử dụng công cụ như này quan trọng như nào và các lỗi kiểu dễ xảy ra thế nòa. Ngay khi bạn thêm `strict_types=1` vào cơ sở mã của bạn, bạn có thể bắt đầu nhìn thấy nhiều hơn các lỗi kiểu này xảy ra, đặc biêt khi làm việc với cơ sở mã cũ, cái mà bạn đang cố hiện đại nó. đây là lúc mà các công cụ như [Rector](https://github.com/rectorphp/rector) trở nên vô cùng hữu ích. 


## Thêm vào dự án đã có 

 nếu bạn tiêp nhank 1 dự án hoặc bạn có 1 dự án cũ cái mà bạn muốn thêm PHPstan nhưng bạn sợ số lượng lỗi bạn có thể gặp khi bạn chạy lệnh, đừng băn khoăn, bạn có thể bắt đầu với 1 tệp cơ sở cái mà có các lỗi hiện tại và đặt chúng vào tệp này, cái mà bạn sẽ thêm vào thêm phpstan.neon như dưới đây: 


```php 
includes:
    - phpstan-baseline.neon

parameters:
    level: 9
    paths:
        - app
        - config
        - routes
        - database/factories
    reportUnmatchedIgnoredErrors: true
    checkMissingIterableValueType: false
    checkModelProperties: true
    checkUnusedViews: false

    excludePaths:
        - tests
        - vendor
        - _ide_helper.php
        - _ide_helper_models.php
        - .phpstorm.meta.php
        - node_modules
```

Bằng cách thêm tệp này vào lần tiếp theo bạn chạy công cụ phân tích, PHPstan sẽ không cảnh báo bạn về các lỗi hiện tại và chỉ các lỗi mới trong code mới của bạn viết và thay đổi. Điều này cho bạn khả năng để nhanh chóng thêm nó vào những dự án và cải thiện chúng qua thời gian. Hãy tìm hiểu PHPstan dóc để hiểu thêm về [baseline](https://phpstan.org/user-guide/baseline).


## Tiếp tục tich hợp PHPStan


Chạy công cụ phân tích tĩnh trong suốt vòng đời phát triển của bạn là rất quan trongj để giúp bạn nhận ra các vấn đề sớm và giữ cho vòng lặp phản hồi nhỏ. Nó cũng quan trọng để đảm bảo rằng tool CI của bạn cũng chạy cùng 1 phân tích mà của bạn để đảm bảo không có gì lọt qua. Chúng tôi cơ bản sử dụng Github cho các kho mã để  chúng tôi sử dụng **Github Actions**  thiết lập chạy phân tích trên mỗi PR mà nhà phát triển đưa vào. 

Đây là ví dụ cơ bản của quy trình làm việc Github Action cái mà chạy PHPstan: 

```php 

name: PHPStan
on: pull_request

jobs:
  phpstan:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2

    - name: Install dependencies
      run: composer install

    - name: Run PHPStan
      run: composer run phpstan
```
 Bằng cách để CI của chúng tôi chạy phân tích, chúng tôi sẽ ngăn chặn các vấn đề xâm nhập vào bản phát hành của mình cái mà giúp chung toou bảo trì các tiêu chuẩn mã cao hơn 


# Cải thiện chất lương mã như 1 nhóm: 

Tổng quan, việc sử dụng các công cụ như PHPstan làm cho cuộc chơi thay đổi cho các nhóm. Bnawfg cách bắt các lỗi tiền ẩn sớm trong tiến trình phát triển, chúng tôi đã có thể tránh các lỗi tiềm ẩn trong sản phẩm và đảm bảo rằng mã của chúng tôi là kiểu mạnh hơn, sau tất cả , ai không thích 1 cơ sở mã có kiểu mạnh mẽ?




