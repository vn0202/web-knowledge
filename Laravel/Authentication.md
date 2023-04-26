## from [https://laravel.com/docs/9.x/authentication#main-content](https://laravel.com/docs/9.x/authentication#main-content)
# Xác thực 


### GIới thiệu 

Nhiều ứng dụng web cung cấp 1 cách cho người dùng của họ để xác thực với ứng dụng và đăng nhập. Việc triển khai đặc tính này có thể là 1 một nỗ lực phức tạp và có nguy cơ nguy hiểm . Vì lý do này, Laravel nỗ lực cung cấp cho bạn cac công cụ bạn cần để khai triển xác thực 1 cách nhanh chóng, bảo mật và dễ hơn. 

Ở lõi của Laravel, cơ sở xác thực của Laravel được tạo thành từ `guards` và `nhà cung cấp`. `Guards` định nghĩa cách mà người dùng được xác thực cho mỗi request. Ví dụ, ,Laravel cung cấp 1 guard phiên cái mà duy trì trạng thái bằng cách sử dụng lưu trữ và cookies. 

Các nhà cung cấp - `providers` định nghĩa cách mà người dùng được lấy ra từ kho lưu trữ liên tục của bạn. Laravel hỗ trợ cho truy xuất người dùng bằng cách sử dụng Eloquent và builder query CSDL. Tuy nhiên, bạn hãy thoải mái để định nghĩa thêm các nhà cung cấp cần thiết cho ứng dụng của bạn.

Tệp cấu hình xác thực ứng dụng của bạn được đặt ở `config/auth.php`. Tệp này chứa vài các tùy chọn đã được ghi lại cho việc tinh chỉnh cử xử của dịch vụ xác thực của Laravel.

    Guards và nhà cung cấp không nên nhầm lẫn với   'roles' và 'permissions'. Để học thêm về ủy quyền người dùng thông qua permissions. 
    vui lòng tham khảo tài liệu ủy quyền 

### Công cụ bắt đầu 

Bạn muốn bắt đầu nhanh? Cài đặt 1 công cụ bắt đầu ứng dụng Larave trong 1 ứng dụng Laravel mới. Sau khi di cư CSDL, chuyển trinh duyệt của bạn tới /register hay bất kỳ các URL khác, cái mà được gán tới ứng dụng của bạn. Các công cụ bắt đầu sẽ làm bệ đỡ cho toàn bộ hệ thống xác thực của bạn. 

Mặc dù bạn chọn không sử dụng 1 công cụ bắt đầu cho ứng dụng Laravel cuối của bạn, việc cài đặt công cụ bắt đầu Laravel Breeze có thể là 1 cơ hộ tuyệt vời cho bạn để học các khai triển tất cả các chức năng xác thực của Laravel trong 1 dự án Laravel thực tế. Vì Laravel Breeze tạo ra bộ điều khiển xác thực, định tuyến và views cho bạn, bạn có thể nghiên cứu mã bên trong những file này để học cách mà các đặc tính xac thực của Laravel có thể được khai triển 

### Cân nhắc CSDL 
THeo mặc định, Laravel bap goofmm 1 model Eloquent App\Models\Users trong thư mục app/Models của bạn. MOdel này có thê được sử dụng cho trình điều khiển xác thực mặc định của Laravel. Nếu ứng dụng của bạn không sử dụng Eloquent, bạn có thể sử dụng nhà cung cấp xác thực CSDL cái mà sử dụng trình tạo truy vấn Laravel. 

Khi xây dựng 1 lược đồ CSDL cho mô hình App\Models\User, hãy đảm bảo cột mật khẩu ít nhất 60  ký tự . Tất nhiên, việc di chuyển bảng ngưởi dùng cái mà được chứa trong 1 ứng dụng Laravel mới đã được tạo 1 cột vượt quá độ dàu này 


Ngoài ra, bạn nên xác nhận rằng bảng người dùng của bạn ( hoặc tương đương ) chứa 1 cột có thể nul;, chuỗi remember_token 100 ký tự. Cột này sẽ được sử dụng để lưu các mã cho người dùng, người mà chọn tùy chọn "rember me" khi đăng nhập vào ứng dụng của bạn. Và cũng, theo mặc định việc di chuyển bảng người dùng cái mà được bao gồm trong các ứng dụng laravel mới đã chứa cột này.

### Tổng quan hệ sinh thái 

Laravel đề xuất một vài gói liên quan đến xác thực, TRước khi bắt đầu, chúng ta sẽ xem hệ sinh thái xác thực chung trong Laravel và thảo luận và mục đích dự định của từng gói .
 
Đầu tiên ,cân nhắn cách mà xác thực hoạt động. Khi sử dụng 1 trình duyệt web, một người dùng sẽ cung cấp tên và mật khẩu của họ thông qua mẫu đăng nhập.  Nêu các chứng chỉ này là đúng, ứng dụng sẽ lưu thông tin về người dùng đã được xác thực trong phiên của người dùng. Một cookie cấp cho trình duyêt chứa ID phiên để các yêu cầu tiếp theo tới ứng dụng có thể liên kết với người dùng với đúng phiên. Sau khi cookie phiên được nhận, ứng dụng sẽ truy xuất tới dữ liệu phiên dựa trên ID phiên, chú ý rằng việc xác thực thông tin được lưu trong phiên, và sẽ xem người dùng như đã xác thực 

Khi 1 dịch vụ từ xa cần xác thực để truy cập 1 API, cookies cơ bản là không được sử dụng cho xác thực bởi vì không có trình duyệt. Thay vào đó, các dịch vụ từ xa gửi 1 mã xác thực API tới API trên mỗi request. Ứng dụng có thể xác thực mã đầu vào với bảng của các mã API hợp lệ và "xác thực" các yêu cầu như được thực hiện bởi người dùng được liên lết với mã API đó.

### Các dịch vụ xác thực trình duyệt dựng sẵn của Laravel.

Laravel bao gồm các dịch vụ phiên và xác thực dựng sẵn cái mà cơ bản được truy cập qua mặt tiền Auth và Session. Các đặc tính này cung cấp xác thực dựa trên cookie cho các request các mà được khởi tạo từ trình duyệt web. Chúng cung cấp các phương thức cho phép bạn xác thực chứng chỉ người dùng và xác thực người dùng. Ngoaì ra, những dịch vụ này sẽ tự động lưu các dữ liệu xác thực đúng đắng trong phiên người dùng và cấp phát cookie phiên của người dùng. Một thảo luận cách sử dụng những dịch vụ này được bao gồm trong tài liệu dưới đây. 

# Bộ công cụ bắt đầu ứng dụng 

Như đã thảo luận trong tài liệu này, bạn có thể tương tác với những dịch vu xác thực này thủ công để xây dựng lớp xác thực của ứng dụng của bạn. Tuy nhiên, để giúp bạn bắt đầu nhanh hơn, chúng tôi đã phát hành các gói cung cấp các bước đầu mạnh mẽ, hiện đại cho toàn bộ lớp xác thực. Những gói này là Laravel Breeze, Laravel Jetstream và Laravel Fortify. 

- Laravel Breeze là 1 khai triển đơn giản, tối thiểu của tất cả các đặc điểm xác thực của Laravel bao gồm đăng nhập, đăng ký, thiết lập lại mật khẩu, xác thực email và xác thực mật khẩu. lớp view của Laravel Breeze bao gồm các mẫu đơn giản được style bằng Tailwind CSS. Để bắt đầu,, kiểm tra tài liệu trên các công cụ bắt đầu ứng dụng của Laravel.

- Laravel Fortity là 1 phụ tợ xác thực không đầu của Laravel, cái mà triển khai nhiều đặc tính được tìm thấy trong tài liệu này, bao gồm xác thực dựa trên phiên, cũng như là các đặc tính khác như xác thực hai yếu tồ và xác thực email. Fortity cung cấp phụ trợ xác thực cho Laravel jetstream hoặc được sử dụng 1 cách độc lập trong kết nối với Laravel Sanctum để cung cấp xác thực cho 1 SPA cần để xáx thực với Laravel. 

- Laravel Jetstream là 1 bộ công cụ bắt đầu ứng dụng mạnh mẽ cái mà sử dụng và đưa ra các dịch vụ xác thực của Laravel Fortity với các UI hiện đại và đẹp được hỗ trợ bởi Tailwind CSS, Livewire và hoặc Inertia. Laravel Jetstream bao gồm các hỗ trợ tùy chọn cho xác thực hai yếu tố, hỗ trợ team quản lý phiên trình duyệt , quản lý hồ sơ, và tích hợp sẵn với Laravel Sanctum để cung cấp xác thực mã API. Xác thực API của Laravel được thảo luận ở dưới. 

# Các dịch vụ xác thực API của Laravel. 

Laravel cung cấp hai gói tùy chọn để hỗ trợ bạn trong quản lý các mã xác thực APU và việc xác thực các yêu cầu được tạo với các mã API: Passport và Sanctum. Vui lòng chú ý rằng những thư viện này và các thư viện xác thực dựa trên cookie tich hợp sẵn của Laravel không loại trừ lãn nhau. Những thư viện này cơ bản tập trung vào xác thực Mã API trong khi các dịch vụ xác thực dựng sẵn dựa vào xác thực trình duyệt dựa trên cookie. Nhiều ứng dụng sẽ dùng cả xác thực dựa trên cookie dựng sẵn và 1 trong các gói xác thực API của Laravel. 

# Passport. 

Passport là  nhà cung cấp xác thực OAuth2, việc cung cấp đa dạng các `kiểu cấp` của OAuth2 cho phép bạn cấp nhiều loại mã. Nói chung, đây là 1 gói mạnh mẽ và phức tạp cho xác thực API. Nhưng, hầu hết các ứng dụng không yêu cầu các đặc tính phức tạp được đề cập bởi các đặc tả của OAuth2, csai mà có thể gây nhầm lẫn cho cả người dùng và các nhà phát triển. Ngoài ra, các nhà phát triển có lịch sử nhầm lẫn về các xác thực các ứng dụng SPA hoặc các ứng dụng điện thoại bằng nhà cung cấp xác thực OAuth2 như Passport. 

# Sanctum

Để đối lại với sự phức tạp của OAuth2 và sự nhầm lẫn của các nhà phát triển, chúng tôi đã thiết lập 1 gói xác thực đơn giản hơn, rõ ràng hơn cái mà có thể xử lý cho các yêu cầu của bên thứ nhất từ 1 trình duyệt web và các yêu cầu API thông qua tokens. Mục tiêu này được thực hiện thông qua việc phát hành Laravel Sanctum, cái mà nên được cân nhắc là gói xác thực được thích hơn và được đề nghi cho các ứng dụng  cái mà sẽ được cung cấp UI wed bên thứ nhất ngoài 1 API hoặc sẽ được hõ trợ các ứng dụng single -page cái mà tồn tại độc lập với các ứng dụng Laravel phụ trợ, hoặc các ứng dụng hỗ trợ client mobile .

Laravel Sanctum là 1 gói hỗn hợp xác thực web/API  có thể quản lý toàn bộ tiên trình xác thực của ứng dụng của bạn. Điều này là cso thể bởi vì khi Sanctum dựa trên các ứng dụng để nhận request, Sanctum sẽ đầu tiên xác định nếu 1 request chứa 1 cookie phiên cái mà tham chiếu tới phiên đã được xác thực. Sanctum thực hiện được điều này bằng cách gọi các dich vụ xác thực bên trong của Laravel cái mà được thảo luận sau. Nếu request không được xác thực qua 1 phiên cookie, Sanctum sẽ kiểm tra các yêu cầu cho 1 mã API> Nếu 1 Mã xác thực API tồn tại, Sanctum sẽ xác thực request bằng cách sử dụng mã xac thực đó. Để học nhiều hơn về tiến trình này, vui lòng xem tài liệu " nó hoạt đông như nào của Sanctum".

Laravel Sanctum là 1 gói API cái mà chúng ta chọn cùng với bộ bắt đầu ứng dụng của Laravel Jetstream. bởi vì chúng tôi tin nó là phù hợp nhất cho hầu hết các nhu cầu xác thực của các ứng dụng web. 

### TÓm tắt và lựa chọn ngăn xếp của bạn 

Tóm lại, nếu ứng dụng của bạn sẽ được truy cập bằng cách sử dụng 1 trình duyệt và bạn đang xây dựng 1 ứng dụng Laravel nguyên khối, ứng dụng của bạn sẽ sử dụng các dịch vụ xác thực dựng sẵn của Laravel. 

Tiếp theo, nếu ứng dụng của bạn cung cấp 1 API cái mà sẽ được tiêu thụ bởi bên thứ 3, bạn sẽ chọn giữa Passport hoặc Sanctum để cung cấp các mã API xác thực cho ứng dụng của bạn. Nhìn chung, Sanctum nên được sử dụng nếu có thể bởi nó đơn giản, giải pháp hoàn hảo cho xác thực API, xác dụng SPA, và xác thực điện thoại, bao gồm hỗ trợ cho phạm vi hoặc khả năng, 

Nếu bạn đang xây dựng 1 ứng dụng SPA, cái mà sẽ được hỗ trợ bởi 1 backend Laravel, bạn nên sử dụng Sanctum. Khi sử dung  Sanctum, bạn sẽ hoặc cần triển khai bằng tay các tuyến xác thực phụ trợ của chính bạn hoặc sử dụng Laravel Fority như 1 dịch vụ phụ trợ xác thực không đầu cung cấp các tuyến và các bộ điều khiển cho các đặc tính như đăng ký, thiết lập lại mật khẩu xác thực email và hơn thế nữa. 

Passport  cũng có thể được chọn khi ứng dụng của bạn hoàn toàn cần tất cả các đặc tính đưuọc cung cấp bởi đậc tả của OAuth2. 

Và, nếu bạn muốn bắt đầu nhanh, chúng tôi sẵn lòng đề nghị sử dụng Laravel Breeze như 1 cách nhanh chóng để bắt đầu 1 ứng dụng Laravel cái mà đã sử dụng sẵn ngăn xếp xác thực dược yêu thích của chúng tôi của các dịch vụ xác thực dựng sẵn và Laravel Sanctum. 
