# Guzzle Promises

Triển khai [Promises/A+](https://promisesaplus.com/) cái mà xử lý các  vòng lặp  chuỗi `promise` và giải pháp, cho phép tạo ra chuỗi lời hứa `vô tận` 
mà vẫn giữ cho kich thước của `stack` không đổi. Đọc [this blog post](https://blog.domenic.me/youre-missing-the-point-of-promises/) cho các hướng dẫn chung về promises.


 

- [Dặc tính](#features)
- [Hướng dẫn nhanh ](#quick-start)
- [Chờ đợi đồng bộ ](#synchronous-wait)
- [Hủy](#cancellation)
- [API](#api)
    - [Promise](#promise)
    - [FulfilledPromise](#fulfilledpromise)
    - [RejectedPromise](#rejectedpromise)
- [Tương tác promise](#promise-interop)
- [Các ghi chú khai triển ](#implementation-notes)


## Đặc tính

- [Promises/A+](https://promisesaplus.com/) khai triển 
- Giải quyết và xâu chuỗi `promise`  được xử lý lặp đi lặp lại, cho phép tạo ra chuỗi lời hứa `vô tận`.
- Lời hứa có 1 phương thức `wait` đồng bộ.
- Các lời hứa có thể bị `hủy`. 
- Làm việc với bất kỳ đối tượng nào có phương thức `then`
- Phong cách `C#` async/await coroutine promises bằng cách sử dụng `GuzzleHttp\Promise\Coroutine::of()`.
  


## Hướng dẫn nhanh 
 Một *promise* đại diện cho kết quả cuối cùng của 1 thao tác bất đồng bộ. Cách cơ bản tương tác với 1 `promise` là thông qua phương thức `then` của nó.,
cái mà đăng ký các `callbacks` để nhận về hoặc là giá trị cuối cùng của của lời hứa hoặc lý do tại sao  lời hứa không được hoàn thành. 


### Callbacks  
Callbacks đưuọc đăng ký với `then` bằng cách cung cấp  tuỳ chọn `$onFullFilled` được theo sau bởi 1 tùy chọn `onRejected`. 



```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$promise->then(
    // $onFulfilled
    function ($value) {
        echo 'The promise was fulfilled.';
    },
    // $onRejected
    function ($reason) {
        echo 'The promise was rejected.';
    }
);
```

*Giải quyết* một lời hứa cso nghĩa là bạn hoặc hoàn thành lời hứa với 1 *giá trị** hoặc từ chối 1 lời hứa với 1 *lý do*. Giải quyết 1 lời hứa khích hoạt 
các callbacks được đăng ký với phương thức `then` của `promise`. Những callbacks được kích hoạt chỉ 1 lần trong thứ tự chúng được thêm. 


### Giải quyết 1 lời hứa 

Các lời hứa được giải quyết bằng phương  thức `resolve($value)`. Việc giải quyết 1 lời hứa với bất kỳ giá trị nào khác với `GuzzleHttp\Promise\RejectedPromise`
sẽ khích hoạt tất cả các callback `oonFullFilled`( giải quyết lời hứa với 1 lời hứa bị từ chối sẽ từ chối lời hứa và kich hoạt `$onReject` callbacks). 


```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$promise
    ->then(function ($value) {
        // Return a value and don't break the chain
        return "Hello, " . $value;
    })
    // This then is executed after the first then and receives the value
    // returned from the first then.
    ->then(function ($value) {
        echo $value;
    });

// Resolving the promise triggers the $onFulfilled callbacks and outputs
// "Hello, reader."
$promise->resolve('reader.');
```

### Chuyển tiếp lời hứa 

Các lời hứa có thể được nối với nhau. Mỗi cái sau trong chuỗi là 1 lời hứa mới. Kết quả trả về của 1 lời hứa là caí mà được chuyển tiếp tới lời hứa tiếp theo trong chuỗi. 
Việc trả về 1 lời hứa trong callback`then` sẽ gây ra các chuỗi con trong chuỗi chỉ hoàn  thành khi các promise được trả về được `fullfilled`. Lời 
hứa tiếp thep trong chuỗi sẽ được gọi với giá trị được giải quyết của lời hứa. 


```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$nextPromise = new Promise();

$promise
    ->then(function ($value) use ($nextPromise) {
        echo $value;
        return $nextPromise;
    })
    ->then(function ($value) {
        echo $value;
    });

// Triggers the first callback and outputs "A"
$promise->resolve('A');
// Triggers the second callback and outputs "B"
$nextPromise->resolve('B');
```

### Từ chối lời hứa. 

Khi 1 lời hứa bị `từ chối`, các callbacks `onRejected` được gọi vơi 1 lý do từ chối. 



```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$promise->then(null, function ($reason) {
    echo $reason;
});

$promise->reject('Error!');
// Outputs "Error!"
```

### Chuyển tiếp từ chối 

 Nếu 1 ngoại lệ được ném trong các callback `$onRejected`, các callbacks `onRejected` tiếp theo sẽ được gọi với lỗi ngoại lệ được ném ra như 1 lý do. 


```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$promise->then(null, function ($reason) {
    throw new Exception($reason);
})->then(null, function ($reason) {
    assert($reason->getMessage() === 'Error!');
});

$promise->reject('Error!');
```

bạn cũng có thể chuyển hướng 1 từ chối xuống chuỗi lời hứa bằng cách trả về 1
`GuzzleHttp\Promise\RejectedPromise` trong  `$onFulfilled` hoặc 
`$onRejected` callback.

```php
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\RejectedPromise;

$promise = new Promise();
$promise->then(null, function ($reason) {
    return new RejectedPromise($reason);
})->then(null, function ($reason) {
    assert($reason === 'Error!');
});

$promise->reject('Error!');
```

Nếu 1 ngoại lê không đưọc ném ra trong 1 callback `$onRejected` và callback không trả về 1 lời hứa bị từ chối, các callbacks `$onRejected` hạ nguồn được gọi 
bằng cách sử dụng giá trị được trả về từ `$onRejected` callbacjk. 

```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise();
$promise
    ->then(null, function ($reason) {
        return "It's ok";
    })
    ->then(function ($value) {
        assert($value === "It's ok");
    });

$promise->reject('Error!');
```


## Synchronous Wait


Bạn có thể bắt buộc các lời hứa để thực hiện 1 cách đồng bộ bằng cách sử dụng 1 phương thức `wait`. Khi tạo ra 1 lời hứa, bạn có thể cung cấp 1 hàm đợi cái 
được sử dụng để bắt buộc 1 lời hứa thực hiện 1 cách đồng bộ. Khi 1 hàm đợi được gọi, nó được thực thi để vận chuyển 1 gía trị tới 1 lời hứa hoặc từ chối lời hứa. 
Nếu hàm đợi không phát tán 1 gía trị, sau đó 1 ngoại lệ được ném ra. Hàm đợi cung cấp 1 người khởi tạo lời hứa được gọi khi hàm `wait` của lời hứa được gọi. 

```php
$promise = new Promise(function () use (&$promise) {
    $promise->resolve('foo');
});

// Calling wait will return the value of the promise.
echo $promise->wait(); // outputs "foo"
```

Nếu 1 ngoại lệ được phát hiện trong khi đang gọi hàm đợi của 1 lời hứa, lời hứa được hủy với ngoại lệ và ngoại  lệ được ném ra, 

```php
$promise = new Promise(function () use (&$promise) {
    throw new Exception('foo');
});

$promise->wait(); // throws the exception.
```

Việc gọi `wait` trên 1 lời hứa cái mà được thực hiện sẽ không kích hoạt hàm đợi. Nó sẽ đơn giản trả về kết quả được giải quyết trước đó. 
```php
$promise = new Promise(function () { die('this is not called!'); });
$promise->resolve('foo');
echo $promise->wait(); // outputs "foo"
```

Việc gọi `wait` trên lời hứa cái mà đã bị từ chối sẽ ném ra 1 ngọai lệ. Nếu nguyên nhân từ chối là 1 thực thể của `\Exception` được ném ra. 
Ngược lại, 1 `GuzzleHttp\Promise\RejectionException` được ném ra và nguyên nhân có thể lấy được bằng cách gọi phương thức `getReason` của ngoại lệ. 

```php
$promise = new Promise();
$promise->reject('foo');
$promise->wait();
```

> PHP Fatal error:  Uncaught exception 'GuzzleHttp\Promise\RejectionException' with message 'The promise was rejected with value: foo'

### Mở gói 1 lời hứa. 

Khi đang chờ đợi đồng bộ trên 1 lời hứa, bạn đang tham gia vào trạng thái của lời hứa trong trạng thái hiện hiện tại của thực thi ( trả về gía trị 
của lời hứa nếu nó được thực hiện hoặc nem ra 1 ngoại lệ nếu nó bị từ chối). Điều này được gọi là `unwrapping` lời hứa. Chờ đợi trên 1 lời hứa sẽ mặc định 
mở trạng thái của lời hứa. 

Bạn có thể bắt lời hứa để giải quyết và *không* unwrap trạng thái của lời hứa bằng cách truyền `false` tới tham số đầu tiên của `wait`: 
```php
$promise = new Promise();
$promise->reject('foo');
// This will not throw an exception. It simply ensures the promise has
// been resolved.
$promise->wait(false);
```

Khi mà `unwrapping` 1 lời hứa, giá trị được giải quyết của lời hứa sẽ được đợi cho tới khi giá trị `unwrapped` không phải là lời hứa. Điều này có nghĩa là 
nếu bạn gỉai quyết 1 lời hứa A với 1 lời hứa B và unwrap lời hứa A, giá trị được trả về bời hàm đợi sẽ là giá trị đưọc phát tán tới lời hứa B. 
When unwrapping a promise, the resolved value of the promise will be waited
upon until the unwrapped value is not a promise. This means that if you resolve
promise A with a promise B and unwrap promise A, the value returned by the
wait function will be the value delivered to promise B.

**Note**:Khi bạn không `unwrap` lời hứa, không có giá trị nào đưowjc trả về. 


## Hủy bỏ 

Bạn có thể hủy 1 lời hứa cái mà chưa được thực hiện bằng cách sử dụng `cancel()` 
của lời hứa. Khi tạo 1 lời hứa, bạn có thể cung cấp 1 hàm hủy tùy chọn cái mà gọi hành động hủy của việc tính toán giải quyết của lời hứa. 


## API

### Lời hứa 

khi tạo 1 đối tượng lời hứa, bạn có thể cung cấp 1 tùy chọn `$waitFn` và `$cancelFn`. `$waitFn` là 1 hàm được gọi với không tham số và được mong chờ 
để giải quyết lời hứa. `$cancelFn ` là hàm không tham số được mong chờ để hủy sự tính tóan của 1 lời hứa. Nó được gọi khi 
`cancel()` của 1 lời hứa được gọi. 

`cancel()` method of a promise is called.

```php
use GuzzleHttp\Promise\Promise;

$promise = new Promise(
    function () use (&$promise) {
        $promise->resolve('waited');
    },
    function () {
        // do something that will cancel the promise computation (e.g., close
        // a socket, cancel a database query, etc...)
    }
);

assert('waited' === $promise->wait());
```

Một lời hưa có những phương thức sau: 

- `then(callable $onFulfilled, callable $onRejected) : PromiseInterface`
  Nối các trình xử lý từ chối và xử lý tới lời hứa, và trả về 1 lời hứa mới  giải quyết giá trị trả về của trình xử lý được  gọi 

- `otherwise(callable $onRejected) : PromiseInterface`

Nối callback trình xử lý từ chối tới lời hứa, và trả về 1 lời hứa mới giải quyết gía trị trả về của callback nếu nó được gọi hoặc giá trị gốc được thực hiện của chính nó nếu lời hứa được thực hiên thay vào đó. 

- `wait($unwrap = true) : mixed`

Chờ đợi đồng bộ trên phương thức để hoàn thành. 

  `$unwrap` kiểm soát xem có hay không gia trị của lời hứa được trả về cho 1 lời hứa được thực hiện hoặc nếu 1 ngoại lệ được ném nếu lời hứa bị từ chối. 
Được thiết lập mặc định là `true`
  
- `cancel()`

  Cố gắng để hủy lời hứa nếu có thể. Lời hứa đang được hủy và tổ tiên lớn nhất cái mà chưa được giải quyết cũng sẽ bị hủy. Bất kỳ lời hứa nào đang đợi trên lời hứa đã bị hủy để giải quyêt cũng sẽ bị Hủy. 
  

- `getState() : string`
 Trả về trạng thái của lời hứa . Một của `pending`, `fullfilled` hoặc `rejected`. 
  

- `resolve($value)`

  Thực hiện lời hứa với giá trị `$value` được cấp 

- `reject($reason)`

 Từ chối lời hứa với `$reason` được cung cấp. 



### FulfilledPromise

1 lời hứa được thực hiện có thể được tạo để đại diện 1 lời hứa cái mà được thực hiện
A fulfilled promise can be created to represent a promise that has been
fulfilled.

```php
use GuzzleHttp\Promise\FulfilledPromise;

$promise = new FulfilledPromise('value');

// Fulfilled callbacks are immediately invoked.
$promise->then(function ($value) {
    echo $value;
});
```


### RejectedPromise


Một lời hứa bị từ chối có thể được tạo ra để đại diện cho lời hứa bị từ chối. 

```php
use GuzzleHttp\Promise\RejectedPromise;

$promise = new RejectedPromise('Error');

// Rejected callbacks are immediately invoked.
$promise->then(null, function ($reason) {
    echo $reason;
});
```


## Promise Interoperability


Thư viện nàu làm việc với các lời hứa ngoài có `then`. Điều này có nghĩa là bạn ó thể sử dụng 
promises Guzzle với [React promises](https://github.com/reactphp/promise). Khi 
1 lời hứa bên ngoài được trả về bên trong của 1 callback  phương thức `then`, giải quyết lời 
hứa sẽ xẩy ra 1 cách đệ quy 

```php
// Create a React promise
$deferred = new React\Promise\Deferred();
$reactPromise = $deferred->promise();

// Create a Guzzle promise that is fulfilled with a React promise.
$guzzlePromise = new GuzzleHttp\Promise\Promise();
$guzzlePromise->then(function ($value) use ($reactPromise) {
    // Do something something with the value...
    // Return the React promise
    return $reactPromise;
});
```

Làm ơn chú ý rằng chuỗi đợi và hủy là không còn có thể khi mà chuyển hướng 1 lời hứa `ngoại quốc`,
Bạn sẽ cần để gói 1 lời hứa của bên thứ 3 với 1 lời hứa Guzzle theo đúng thứ tự để có thể sử dụng 
`wait` và `cancel` với các lời hứa `ngoại`. 


### Tích hợp vòng lặp sự kiện 

Để giữ cho kích thước của stack cố định, lời hứa Guzzle được giải quyết bất đồng bộ bằng cách sử 
dụng hàng đợi tác vụ. Khi đợi trên 1 lời hứa đồng bộ, hàng đợi tác vụ sẽ tự động chạy để đảm bảo 
rằng khối lời hứa và bất kỳ lời hứa chuyển hướng nào được giải quyết. Khi sử dụng các lời hứa bất đồng bộ trong 
1 sự kiện vòng lặp, bạn sẽ cần để chạy hàng đợi tác vụ trên mỗi đánh dấu của vòng lặp. 
Nếu bạn không chạy tác vụ hàng đợi sau đó lời hứa sẽ không được giải quyết. 

Bạn có thể chạy tác vụ hàng đợi với phưong thưc `run() ` của thực thể hàng đợi tác vụ toàn cục 
instance.

```php
// Get the global task queue
$queue = GuzzleHttp\Promise\Utils::queue();
$queue->run();
```

ví dụ, bạn có thể sử dụng lời hứa của React bằng cách sử dụng bộ đếm thời gian định kỳ. 

```php
$loop = React\EventLoop\Factory::create();
$loop->addPeriodicTimer(0, [$queue, 'run']);
```

*TODO*: Có lẽ thêm 1 `futureTick()` trên mỗi dấu sẽ nhanh hơn?


## Các chú ý khai triển 



### Chuỗi và giải quyết lời hứa được xử lý lặp đi lặp lại 

Bằng cách xáo trộn các trình xử lý đang chờ xử lý từ chủ sở hữu này sang chủ sỏ hữu khác , các lời hứa 
được giải quyết lặp đi lặp lại cho phep tạo ra chuỗi vô tận. 

```php
<?php
require 'vendor/autoload.php';

use GuzzleHttp\Promise\Promise;

$parent = new Promise();
$p = $parent;

for ($i = 0; $i < 1000; $i++) {
    $p = $p->then(function ($v) {
        // The stack size remains constant (a good thing)
        echo xdebug_get_stack_depth() . ', ';
        return $v + 1;
    });
}

$parent->resolve(0);
var_dump($p->wait()); // int(1000)

```

Khi 1 lời hứa được thực hiện hoặc từ chối với 1 giá trị không phải lời hứa, lời hứa sau đó nhận 
các quyền sở hữu các trình xử lý của mỗi lời hứa con và phân phối cac giá trị xuống chuỗi mà không cần dùng đệ quy. 

Khi 1 lời hứa được giải quyết với 1 lời hứa khác, lời hứa gốc chuyển tất cả các trình xử lý đang chờ 
xử lý tới lời hứa mới. Khi  lời hứa mới cuối cùng được thực hiện tất cả các trình xử ly đang chờ giải quyết được 
gửi giá trị chuyển tiếp. 


### Một lời hứa là sự trì hoãn 


Nhiêu thư viện lời hứa triển khai các lời hứa bằng cách sử dụng 1 đối tượng trì hoãn để đại 
diện cho 1 phép tính và 1 đối tượng lời hứa để đại diện cho việc phân phối kết quả của phép tính. 
Điều này là 1 sự tách biệt tốt của phép tính và phân phối bởi vì người tiêu dùng của lời hứa không 
điều chỉnh giá trị cái mà cuối cùng sẽ được phân phối. 

Một tác dụng phụ của việc có thể thực hiện giải quyết lời hứa và xâu chuỗi lặp đi lặp
lại là bạn cần có khả năng để một lời hứa đạt được trạng thái của một lời hứa khác
để xáo trộn quyền sở hữu của các trình xử lý. Để đạt được điều này mà không làm cho 
trình xử lý của một lời hứa có thể thay đổi công khai, một lời hứa cũng là giá
trị hoãn lại, cho phép các lời hứa của cùng một lớp cha tiếp cận và sửa đổi các 
thuộc tính riêng của các lời hứa cùng loại. Mặc dù điều này cho phép người tiêu dùng có giá
trị sửa đổi độ phân giải hoặc từ chối giá trị bị hoãn lại, 
nhưng đó là một cái giá nhỏ phải trả để giữ kích thước ngăn xếp không đổi.

```php
$promise = new Promise();
$promise->then(function ($value) { echo $value; });
// The promise is the deferred value, so you can deliver a value to it.
$promise->resolve('foo');
// prints "foo"
```


## Nâng cấp từ hàm API 

API tĩnh lần đầu tiên được giới thiệu trong 1.4.0, nhằm giảm thiểu sự cố với các chức năng xung đột giữa các bản sao toàn cầu và cục bộ của gói. API chức năng sẽ bị xóa trong 2.0.0. Một bảng di chuyển đã được cung cấp ở đây để thuận tiện cho bạn:

| Original Function | Replacement Method |
|----------------|----------------|
| `queue` | `Utils::queue` |
| `task` | `Utils::task` |
| `promise_for` | `Create::promiseFor` |
| `rejection_for` | `Create::rejectionFor` |
| `exception_for` | `Create::exceptionFor` |
| `iter_for` | `Create::iterFor` |
| `inspect` | `Utils::inspect` |
| `inspect_all` | `Utils::inspectAll` |
| `unwrap` | `Utils::unwrap` |
| `all` | `Utils::all` |
| `some` | `Utils::some` |
| `any` | `Utils::any` |
| `settle` | `Utils::settle` |
| `each` | `Each::of` |
| `each_limit` | `Each::ofLimit` |
| `each_limit_all` | `Each::ofLimitAll` |
| `!is_fulfilled` | `Is::pending` |
| `is_fulfilled` | `Is::fulfilled` |
| `is_rejected` | `Is::rejected` |
| `is_settled` | `Is::settled` |
| `coroutine` | `Coroutine::of` |


