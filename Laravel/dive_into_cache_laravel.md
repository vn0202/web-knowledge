### from [https://www.linkedin.com/pulse/laravel-deep-dive-series-tackling-complex-caching-strategies/](https://www.linkedin.com/pulse/laravel-deep-dive-series-tackling-complex-caching-strategies/)

# Summary 

 Trong bài viết này, chúng ta sẽ khám phá những công nghệ tiên tiến và  chuyên sâu để triển khai chiến lược lưu bộ nhớ đệm phức tạp trong Laravel, cho phép bnạ tối ưu hiệu suất và cung cấp trải nghiệm người dùng đáng mong đợi. 

🔍 Understanding Caching in Laravel:

`Bộ nhớ đệm` là 1 khía cạnh quan troọng trong tối ưu hóa hiệu năng của ứng dụng web. Nó liên quan đến việc lưu các dữ liệu được truy cập thường xuyên vào trong bộ nhớ để giảm thiểu các phép tính lặp lại hoặc các truy vấn CSDL.
✨ Tackling Complex Caching Strategies:

Trong khi kỹ thuật lưu bộ nhớ đệm cơ bản khá dễ dàng, việc sử lý các ngữ cảnh lưu bộ nhớ đệm phức tạp đòi hỏi 1 sự hiểu biết sâu rộng về `khả năng lưu bộ đệm của Laravel`. Chúng ta sẽ cung nhau khám phá những chiến lược lưu bộ đệm nâng cao và các kỹ thuật giúp bạn vượt qua nút thắt về hiệu năng và cung cấp các phản hồi chớp nhoáng  cho người dùng của bạn. CHiến lược lưu bộ đệm phức tạp thường được sử dụng trong các ứng dụng hàng đầu để giảm tải CSDL, đẩy nhanh tốc độ phản hồi API và cung cấp các trải nghiệm người dùng chớp nhoáng. 

- Content Delivery Networks(CDNs): CDNs lưu các tài nguyên tĩnh như ảnh, css, Js qua nhiều máy chủ ở nhiều nơi trên toàn thế giới. Điều này cho phép user có thể truy cập các tài nguyên này từ các máy chủ gần nhất, giảm độ trễ và cải thiện thời gian tải trang. 
- Database Query Caching: Các ứng dụng thường lưu bộ đệm các kêt quả của các chuỗi truy vấn CSDL thường được thực hiện. Thay vì phải gọi tới CSDL nhiều lần, kết quả được lưu trong bộ nhớ đệm được sử dụng, giảm tải trên CSDL và cải thiện thời gian phản hồi. Ví dụ, bao gồm việc lưu bộ nhớ đệm kết quả danh sách các sản phẩm hoặc lý lịch user. 
- Lưu bộ đệm phản hồi API: APIs có thể dùng caching để lưu phản hồi cho 1 khoảng thời gian cụ thể, chon phép các yêu cầu sau nó cho cùng dữ liệu được lấy từ Cache. Điều này đặc biệt hữu ích cho các điểm cuối cần tìm nạp dữ liệu mà ít thay đổi, như đài báo thời tiết hoặc gía chứng khoán. 
- Lưu bộ nhớ đệm tòan trang: các trang web hay ứng dụng wbe có thể lưu bộ  nhớ đệm cho toàn bộ trag để phục vụ cho các khách truy cập tiếp theo mà không cần thực hiện bất kỳ logic basckend nào. Điều này hiệu quả cho các trang có nội dung tĩnh hoặc nội dung không thay đổi thường xuyên. Nền tảng thương mại điện tử thường sử dụng `full-page caching` cho danh sách các sản phẩm, kết quả tìm kiếm hoặc các trang tĩnh. 

  - Bộ nhớ đệm biên: được sử dụng để lưu các nội dung gần với người dùng cuối hơn, đặc biệt là ở các máy chủ biên của 1 CDN. Điều này đảm bảo rằng các nội dung được truy cập thường xuyên được `cached` ở các địa điểm chiến lược, giảm độ trễ và cải thiện tổng quan hiệu suất. 
- Bộ nhớ đệm phân đoạn: Hơn là phải caching toàn bộ 1 trang, `bộ đệm phân đoạn` tập trung vào lưu các đoạn đặc biệt hoặc các components của 1 trang cái mà cần nhiều tài nguyên để sinh ra. Ví dụ, lưu bộ đệm sidebar của 1 blog hoặc phần comment của 1 bài viết có thể cải thiện thời gian phản hồi trong khi vần cho phép các nội dung dynamic ở các vùng khác của trang. 
- Caching HTTP: tận dụng tiêu đề bộ đệm HTTP< các ứng dụng có thể hướng dẫn trình duyệt và máy chủ lưu rữ bộ nhớ đệm trung giản để lưu bộ đệm cac tài nguyên cụ thể, như hình ảnh, file css hoặc phản hồi API. Điều này giảm việc lặp lại các request và cải thiện hiệu suất 1 cách tổng quan. 

** Bằng các lưu bộ đệm dữ liệu 1 cách thông minh ở các lớp khác nhau của ngăn xếp ứng dụng, chúng có thể giảm tải trên hệ thống backend, tối thiểu thời gian phản hồi và cung cấp trải nghiệm người dùng liền mạch. 

Để caching trong Laravel, hãy đảm bảo rằng caching đã được bật trong ứng dụng của ban trong tệp `config/cache.php`


### Các kỹ thuật caching nâng cao: 
1. 1️⃣ Cache Tags:

Trong laravel, bạn có thể tận `tags` bộ nhớ đệm để quản lý và tổ chức lưu bộ nhớ đệm 1 cách hiệu quả. Gắn thẻ cache cho phép bạn gắn 1 hoặc nhiều tags cho các phần tử được cached, làm cho nó dễ hơn để quản lý và huy nó như 1 nhóm. 

```php 
// Storing data with cache tag
Cache::tags(['users', 'profile'])->put('user:1', $userData, $minutes);


// Retrieving data using cache tags
$userData = Cache::tags(['users', 'profile'])->get('user:1');


// Clear all cache items with the 'users' tag
Cache::tags('users')->flush();


// Invalidating cache using cache tags
Cache::tags(['users'])->forget('user:1');s
```


Việc sử dụng gắn thẻ cho cache cho phép bạn nhóm các phần được cached liên quan với nhau và hủy chúng như 1 nhóm khi cần. It provides a convient way to manage and organize data. 

2. Cache Locking:


Trong laravel, việc khóa caching cho phép bạn quản lý các dữ liệu được cached một cách hiêu quả bẳng cách ngăn chặn đa tiến trình từ việc update đồng thời cho cùng 1 phần tử được cache. Điều này đặc biệt hữu ích khi bạn có các vùng mã đặc biệt cần độc quyền truy cập tới dữ liệu được cache. 
Để tận dụng `cache locking ` trong laravel, bạn có thẻ sử dụng phương thức `lock` được cung cấp bởi hệ thống, 

```php 
$cacheKey = 'my_data_key'
$lockKey = 'my_data_lock';

// Attempt to acquire the cache lock
$lockAcquired = Cache::lock($lockKey, 10)->get(function () use ($cacheKey) {
    // Check if the data is already cached
    $cachedData = Cache::get($cacheKey);
    if ($cachedData !== null) {
        // Data is already cached, no need to update it
        return true;
    }

    // If the data is not cached, perform some expensive operation to retrieve it
    $data = retrieveDataFromDatabase();


    // Store the data in the cache
    Cache::put($cacheKey, $data, $minutes);

    // Return false to release the lock
    return false;
});

if (!$lockAcquired) {
    // Another process has acquired the lock, wait or handle the situation
    // ...
};
```

Trong đoạn mã trên, chúng ta sử dụng caching `locking` để đảm bảo rằng chỉ có 1 tiến trình duy nhất có thể câp nhật phần tử cache được chỉ định bởi `cachedKey' ở một thời điểm. 

- Ban đầu, chúng ta định nghĩa key Cache cho dữ liệu cần lưu trong cache và 1 khóa 'lock' riêng cho `cache lock`.
- sau đó cố gắng đạt được `cache lock` bằng cách gọi ` Cache::lock($lockKey, 10)->get(function () use ($cacheKey) { ... })`,Mã này cố gắng để giành được `lock` trong  thời gian tối đa 10s. Bao đóng bên trong hàm get() là  nơi bạn thực hiện các hoạt động liên quan đến cached. 
Bên trong bao đóng, đầu tiên kiểm tra nếu dữ liệu đã được cached bằng cách sử dung Cache::get($cacheKey). Nếu có, sẽ trả về true để chỉ ra rằng khóa đã giành được thành công và không cần update cache.
Nếu dữ liệu không được cache, chúng ta sẽ thực hiện các phép toán để truy xuất nó ( lấy dữ liệu từ CSDL...) Một khi lấy được dữ liệu, chúng ta lưu nó vào trong cache bằng cách `Cache::put($cacheKey, $data, $minutes`

Cuối cùng trả về `false` để giải phóng khóa và để thông báo các tiến trình khác có thể giành được nó. 
Sau khi giành được khóa, nếu get() trả về `true`, nó có nghĩa là cache đã thực sự được updated bởi một tiến trinh khác, và bạn có thể thực thi tiếp sau đó. Nếu false, nó có nghĩa là khóa đã giành được và bạn có thể updated cache. 

Nếu khóa k giành được, bạn có thể sử lý những trường hợp tương ứng ( đợi và thử lại haowcj thực thi các hành động thay thế). 
Cache locking giúp đảm bảo rằng dữ liệu được chính xác và ngăn cản tình trạng cạnh tranh khi có nhieuefu tiến trình cố gắng update cùng 1 cache đồng thời. cho phép bạn quản lý các dữ liệu được cache hiệu quả và an toàn hơn. 
 

3. Cache Busting : 

Chặn bộ nhớ đệm là  1 kỹ thuật được sử dụng để ép trình duyệt tìm nạp phiên bản mới nhất của 1 file bằng cách nối thêm 1 trình xác thực duy nhất vào url của nó. Trong ứng dụng Laravel, bạn có thể sử dụng hàm `mix` đươc cung cấp bởi laravel mix để tự động chặn truy xuất bộ nhớ đệm cho tài nguyên của bạn. laravel Mix tích hợp với Webpack, một module bundlder, để sinh ra các file duy nhất đăt tên cho các tài nguyên của bạn trong suốt quá trình build.
```php 
// webpack.mix.j

mix.js('resources/js/app.js', 'public/js')

  .sass('resources/sass/app.scss', 'public/css')

  .version();s
```
Trong files webpack.mĩx.js file, bạn định nghĩa các task biên dich các tài sản của bạn bằng cách sử dụng các API của Laravel Mix.Phươn thức version() được sử dụng để kích hoạt chặn truy xuất bộ đệm. Nó sinh ra 1 mã băm duy nhất cho mỗi file được biên dịch và gắn vào tên file. 
Trong tệp HTML hoặc Blade, bạn có thể tham chiếu tài sản bằng các sử dụng mix(), tự động giải quyết các url đúng với các tham số chuỗi truy vấn chặn truy cập bộ nhớ đệm: 


```php 
<link rel="stylesheet" href="{{ mix('css/app.css') }}"

<script src="{{ mix('js/app.js') }}"></script>>
```

### 📊 Performance Considerations:

 Chiến lược tối ưu hóa bộ nhớ đệm yêu cầu cân nhắc cẩn thận các nhân tối như kich thước bộ đệm, chính sách hết hạn của cache, và theo dõi tốc độ truy cập bô đệm. Chúng tôi sẽ cung cấp các thông tin chi tiết trong phần cân nhắc hiệu suất và các thưc hành tốt nhất để tinh chỉnh cấu hình bộ đệm của bạn để đạt được tối ưu hóa hiệu suất. 

Khi cân nhắc vấn đề hiệu suất trong phát triển web, có những khía cạnh quan trọng cần được tính toán. Đây là những cân nhắc về hiệu năng: 

- Tối thiểu: Tối giảm code bao gồm việc xóa các kí tự không cần thiết như khoảng trống, comments. và các dấu ngắt dòng từ file css, js, HTML. Điều này giảm kích thước file và cải thiện thời gian load, vì trình duyệt tải ít dữ liệu hơn, phân tích ít hơn.   

- Nén: Nén files của bạn, đặc biệt là các tài nguyên văn bản như Css, js có thể giảm đáng kể kích thước file. Công nghệ như Gzip hoặc Brotli có thể được áp dụng cho mức độ server để nén file trước khi gửi chúng tới trính duyệt và kết quả là tải nhanh hơn và giảm sử dụng băng thông. 
- lưu bộ đệm: Việc triển khai lưu bộ đệm đúng đắn có thể cải thiện hiệu suất 1 cách tuyệt vời. Bằng cách thiết lập cac tiêu đề bộ đệm thích hợp, bạn cho phép trình duyệt lưu các tài nguyên tĩnh ở local, giảm sự cần thiết việc tải lại. Công nghệ lưu trữ bộ đệm tiện ịch như bộ đệm trình duyệt, CDN hoặc bộ đệm phía máy chủ có thể cải thiện đáng kể thời gian tải trang. 


1. Kích thước bộ đệm: Cân bằng giữa việc có bộ đệm đủ lớn để lưu trữ dữ liệu được truy cập thường xuyên và tránh sử dụng quá nhiều bộ nhớ. Theo dõi việc sử dụng bộ nhớ của bộ đệm và điều chỉnh kích thước của nó cho phù hợp.
2. Chính sách hết hạn bộ nhớ đệm: Đặt thời gian hết hạn thích hợp cho các mục được lưu trong bộ nhớ đệm dựa trên tính biến động và tầm quan trọng của chúng. Hãy cân nhắc sử dụng kết hợp hết hạn dựa trên thời gian và vô hiệu hóa theo sự kiện để đảm bảo rằng bộ nhớ đệm luôn được cập nhật.
3. Tỷ lệ truy cập bộ đệm: Theo dõi tỷ lệ truy cập bộ đệm để đánh giá tính hiệu quả của chiến lược bộ đệm của bạn. Hãy nhắm tới tỷ lệ trúng cao vì chúng cho thấy rằng một phần đáng kể các yêu cầu đang được phân phát từ bộ nhớ đệm, giúp giảm tải cho hệ thống backend của bạn.
4. Mẫu dành riêng cho bộ đệm: Triển khai mẫu dành riêng cho bộ đệm, trong đó ứng dụng trước tiên sẽ kiểm tra bộ đệm để tìm dữ liệu được yêu cầu và chỉ tìm nạp nó từ phần phụ trợ nếu nó không được tìm thấy trong bộ đệm. Điều này giúp giảm thiểu số lượng yêu cầu phụ trợ tốn kém.
5. Thiết kế khóa bộ đệm: Thiết kế cẩn thận các khóa bộ đệm để đảm bảo tính duy nhất và tránh xung đột khóa. Bao gồm thông tin liên quan trong các khóa bộ đệm để cho phép truy xuất hiệu quả và vô hiệu hóa mục tiêu.
6. Nén bộ đệm: Cân nhắc việc nén dữ liệu được lưu trong bộ đệm để giảm yêu cầu lưu trữ và cải thiện hiệu suất bộ đệm, đặc biệt là khi xử lý các đối tượng hoặc bộ dữ liệu lớn.