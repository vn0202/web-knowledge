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
