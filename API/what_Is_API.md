# API là gì ?

APIs là các cơ chế cho phép hai phần mềm có thể tương tác với nhau  bằng cách sử dụng một tập các định nghĩa và các giao thức. Ví dụ, hệ thống phần mềm của đài báo thời tiết chứa dữ liệu thời tiết hàng ngày. Ứng dụng thời tiết trên điện thoại của bạn "talks" với hệ thống này qua APIs và thể hiện những cập nhật thời tiết trên điện thoại của bạn .

# API là viết tăt của ?

API viết tắt cho  Application Programming Interface ( Giao diện chương trình ứng dụng ). Trong ngữ cảnh của APIs, từ Application ( ứng dụng ) muốn chỉ tới bất kỳ phần mềm nào có chức năng rieng biệt . **Interface** ( giao diện)  có thể được nghĩ như là hợp đồng giữa hai ứng dụng. Hợp đồng này định nghĩa hai ứng dụng giao tiếp với nhau như nào bằng cách sử dụng các yêu cầu và phản hồi. Tài liệu API của họ chứa thông tin cách để các nhà phát triển cấu trúc những *request* và *respones*.

# APIs hoạt động như nào? 

Mô hình APIs thưởng được giải thích trong các thuật ngữ *client* và *server*. Ứng dụng gửi các yêu cầu gọi là *client*, và các ứng dụng gửi phản hồi là *server*. Nên trong ví dụ thời tiết ở trên, CSDL thời tiết của trạm khí tượng là *server* và các ứng dụng điện thoại là *clients*.

Có 4 các khác nhau cái mà APIs có thể làm viêc dựa trên khi nào và tại sao chúng được gọi .

1. SOAP APIs
 
Các APIs này sử dụng các Giao thức truy cập đối tượng đơn giản ( Simple Object Access Protocol) . *client* và *server* trao đổi tin nhắn qua XML. Điều này là 1 API thiếu linh hoạt hơn được sử dụng phổ biến trong quá khứ.

2. RPC APIs

 Các APIS này được gọi là Các cuộc gọi tiến hành từ xa ( Remote Proceduce Calls). *clients* hoàn thành 1 chức năng ( hay 1 tiến trình ) trên *server* và *server* gửi các phản hồi lại người dùng.

3. Websocket APIs

Là 1 sự phát triển API web hiện đại cái mà sử dụng đối tượng JSON để chuyển dữ liệu. Một websocket API hỗ trợ hai các giao tiếp giữa ứng dụng khách và máy chủ. Máy chủ có thể gửi các tin nhắn cuộc gội lại tới các máy khách đã được kết nối, làm cho nó hiệu quả hơn REST API. 

4. REST APIs
 
 Có những API phổ biến và linh hoạt nhất được tìm thấy ngày nay. *clients* gửi các yêu cầu tới máy chủ như là dữ liệu . Máy chủ sử dụng các đầu vào của clients để bắt đầu các chức năng bên trong và trả về dữ liệu đầu ra tới máy khách. Hãy nhìn REST APIs trong chi tiêt dưới đây 

## REST APIs là gì ? 

REST viết tắt của Representational State Transfer ( chuyển đổi trạng thái biểu hiện). REST định nghĩa 1 tập các chức năng như : *GET*, *POST*, *PUT*, *DELETE*... cái mà *clients* có thể sử dụng để truy cập vào dữ liệu máy chủ. *clients* và *server* trao đổi dữ liệu qua HTTP. 

 Đặc tính chính cua REST API là không trạng thái ( statelessness). Statelessness có nghĩa là máy chủ không lưu dữ liệu của máy khách giữa các lần requests. Máy khách requests tới máy chủ giống như là URLs bạn nhập vào trình duyệt của bạn để truy cập 1 trang web. Các phản hồi từ máy chủ là dữ liệu thuần túy, không có kết xuất đồ họa của 1 trang web. 

## Web API là gì ? 

 Môt WEB API hay API dịch vụ web là 1 giao diện xử lý ứng dụng giữa 1 máy chủ web và các trình duyệt web. Tất cả các dịch vụ web là các APIs nhưng không phải tất cả các APIs là dịch vụ web. REST API là 1 kiểu đặc biệt của Web API được sử dụng làm phong cách mô hình tiêu chuẩn đã giải thích ở trên . 

các thuật ngữ khác biệt  xung quang API như là Java API hay APIs dịch vụ, tồn tại bởi lý do lịch sử, APIs được tạo ra trước  world wide web. Các APIs web hiện đại và các REST APIs là  các thuật ngữ có thể được dùng thay cho nhau. 

## Tích hợp API là gì? 

Tích hợp API là các thành phần phần mềm tự động cập nhật dữ liệu giữa *clients* và *server*. Vài ví dụ của tích hợp API là khi tự động đồng bộ đám may từ thư viện ảnh diện thoại của bạn hoặc thời gian và ngày tự động đồng bộ với máy tính của bạn khi bạn tới vùng có múi giờ khác. Doanh nghiệp cũng có thể sử  dụng chúng để tự động hiệu quả nhiều chức năng hệ thống. 
## Lợi ích của REST APIs? 
 Có 4 lợi ích chính: 


1. Tích hợp 
APIs được sử dụng để tích hợp các ứng dụng mới với các hệ thống phần mềm đang có . Điều này gia tăng tốc độ phát triển bởi vì mỗi chức năng không cần phải được viết từ đầu. Bạn có thể sử dụng APIs để tận dụng code có sẵn.

2. Sự đổi mới

 Toàn bộ ngành công nghiệp có thể thay đổi với sự xuất hiện của các ứng dụng mới. Các doanh nghiệp cần phản hồi nhanh nhất  và hỗ trợ sự phát triển nhanh chóng của các dịch vụ đổi mới. Họ có thể làm điều này bằng cách làm cho các thay đổi ở các mức độ của API mà không phải viết lại toàn bộ code. 

3. Mở rộng

APIs đại diện 1 cơ hội duy nhất cho các doanh nghiệp để gặp các yêu cầu của khách hàng thông qua nhiều nền tảng. Ví dụ, map APIs cho phép các thông tin map tích hợp qua websites, Android, iOS...Bất kỳ doanh nghiệp nào cũng có thể truy cập như nhau tới CSDL bên trong của họ bằng các sử dụng các APIs có phí và không có phí.

4. Dễ bảo trì.

Hoạt động của API như 1 cổng giao tiếp giữa hai hệ thống. Mỗi hệ thống bắt buộc để tạo ra các thay đổi bên trong để các API không bị ảnh hưởng. Bằng cách này, bất kỳ các thay đổi code trong tương lai bời 1 trong các phần không ảnh hưởng tới các phần khác. 


## sự khác biệt giữa các kiểu APIs?

 APIs được phân loại cả theo mô hình của chúng và phạm vi sử dụng. Chúng ta đã khám phá các loaị mô hình APIs chính vì vậy hãy có 1 cái nhìn tổng quan phạm vi sử dụng. 

1. Private APIs
Đây là bên trong  để các doanh nghiệp và chỉ đưọc sử dụng cho kết nối hệ thống và dữ liệu bên trong doanh nghiệp. 

2. Public APIs
 
Các APIs này được mở cho cộng đồng và có thể được sử dụng bởi bất kỳ ai. Có thể có hoặc không được xác thực và giá được liên kết với những kiểu APIs này

3. Partner APIs
   Chỉ những nhà phát triển bên ngoài được ủy quyền mới có thể truy cập những thông tin này để hỗ trợ quan hệ đối tác giữa doanh nghiệp với doanh nghiệp.
4. Composite APIs

Chúng kết hợp hai hoặc nhiều API khác nhau để giải quyết các hành vi hoặc yêu cầu hệ thống phức tạp.

## Đầu cuối API là gì và tại sao nó quan trọng ?
 Điểm cuối API là điểm tiếp xúc cuối cùng trong hệ thống giao tiếp API. Chúng bao gồm URL máy chủ, dịch vụ và các vị trí kỹ thuật số cụ thể khác từ đó thông tin được gửi và nhận giữa các hệ thống. Điểm cuối API rất quan trọng đối với doanh nghiệp vì hai lý do chính:
1. Security

Điểm cuối API làm cho hệ thống dễ bị tấn công. Giám sát API là rất quan trọng để ngăn chặn lạm dụng.   

2. Performance
   Các điểm cuối API, đặc biệt là những điểm có lưu lượng truy cập cao, có thể gây ra tắc nghẽn và ảnh hưởng đến hiệu suất hệ thống.
### Cách để bảo mật 1 REST API?

Tất cả các APIs phải được bảo mật thông qua ủy quyền và giám sát chính xác. Có hai cách chính để bảo mật REST APIs bao gồm : 

1. Mã xác thực 

 Những mã này được sử dụng để xác thực người dùng để gọi API. Mã xác  thực kiểm tra xem người dùng là ai, họ có là người tuyên bố và có quyền truy cập cho các API cụ thể. Ví dụ, khi bạn ghi log vào email máy chủ của bạn, email của máy khach của bạn sử dụng mã xác thực cho truy cập bảo mật.

2. Khóa APIs

Khóa APis xác nhận chương trình hay ứng dụng đang gọi các API. Chúng định danh ứng dụng và đảm bảo rằng nó có quyền truy cập đúng để thực hiện các cuộc gọi API cụ thể. Khóa API không an toàn như mã xác thực nhưng chúng cho phép APIs giám sát để thu thập dữ liệu sử dụng. Bạn có thể nhận ra có 1 chuỗi dài các ký tự và các số trong URl trình duyệt của bạn khi bạn vào các trang web khác. Chuỗi này là 1 khóa API website sử dụng để thực hiện các cuộc gọi API bên trong. 

### Cách để tạp 1 API? 

Do sự siêng năng và nỗ lực được yêu cầu để xây dựng 1 API cái mà các nhà phát triển khác sẽ muốn làm và tin tưởng. Những điều này là 5 bước yêu cầu để có thiết kế API chất lượng cao:

1. Kế hoạch cho API

Các đặc tính API, như OpenAI, cung cấp bản vẽ thiết kế cho các thiết kế API của bạn. Nó tốt hơn là nghĩ về các trường hợp sử dụng khác biệt và đảm bảo các API tuân thủ tiêu chuẩn phát triển API hiện tại.

2. Xây dụng API

Các nhà thiết kế APIs, các nguyên mẫu APIs đang sử dụng các mã bản mẫu. Một khi nguyên mẫu được kiểm tra, các nhà phát triển có thể tùy chỉnh nó với các đặc tính bên trong. 

3. Test API  

  Kiểm tra API là giống như kiểm tra các phần mềm khác và phải được hoàn thành để ngăn lỗi và các cuộc tấn công. Công cụ kiểm tra API có thể được sử dụng để kiểm tra mạnh hơn để chống laị các cuộc tấn công không gian mạng.

4. Tài liệu API. 

Trong khi APIs là tự định nghĩa, tài liệu API hoạt động như 1 hướng dẫn để cải thiện khả năng sử dụng. Tài liệu API tốt cái mà đưa ra 1 vùng các chức năng và các trường hợp sử dụng  có khuynh hướng phổ biến hơn trong 1 mô hình hướng dịch vụ 

5. Siêu thị API

 Giống như Amazon là 1 siêu thị online cho bán lẻ, các siêu thị API tồn tại cho các nhà phát triển mua và bán các APIs khác. Lắng nghe các APi của bạn cho phép bạn kiếm tiền 

### test API là gì?

Chiến lược kiểm tra API là tương tự như phương pháp luận kiểm tra các phần mềm khác. Mục tiêu chính là xác thực các phản hồi của máy chủ. Kiểm tra API bao gồm: 

- Tạo ra nhiều yêu cầu tới đầu cuối API cho kiểm tra hiệu năng. 
- Viết các đơn vị kiểm tra để kiểm tra logic hoạt động và tính chính xác của các chức năng.
- Kiểm tra bảo mạt bằng cách mô phỏng tấn công hệ thống. 

### cách để viết tài liệu API?

Viết tài liệu API toàn diện là 1 phần của tiến trình quản lý API. Tài liệu API có thể được tự sinh ra bằng cách sử dụng các công cụ hoặc viết bằng tay. Những thực hành tốt nhất bao gồm: 

- Viết giải thích đơn giản,dễ đọc . Tài liệu được sinh ra bởi công cụ có thể trở lên dài dòng và yêu cầu chỉnh sửa. 
- Việc sử dụng các mẫu code để giải thích các chức năng. 
- Duy trì tài liệu để nó là chính xác và cập nhật 
- HƯớng đến phong cách viết cho nguời mới bắt đầu 
- Bao gồm tất cả các vấn đề API có thể giải quyết cho người dùng.

### cách để sử dụng  một API? 

Các bước để khai triển 1 API mới bao gồm: 

- Có được khóa API. Điều này được thực hiện bằng cách 1 tài khoản xác thực với 1 nhà cung cấp API. 
- THiết lập 1 giao thức máy khách API HTTP. Công cụ này cho phép bạn cấu trúc các request APIs dễ dàng bằng cách sử dụng các khóa API nhận được 

- Nếu bạn không có 1 máy khách API, bạn có thể cố gắng cấu trúc các yêu cầu của chính bạn trong trinh duyệt bằng cách tham chiếu tới tài liệu API. 
- Một khi bạn cảm thấy thoải mái với cú pháp API mới, bạn có thể bắt đầu sủ dụng nó trong code của bạn. 

### Nơi toôi có thể tìm thấy các APIs mới? 

- Các APIs web mới có thể được tìm thấy trên các siêu thị APIs và các thư mục API. Siêu thị API là môi trường mở cho bất kỳ ai có thể liệt kê các API để bán. Các thư mục API được kiểm soát do các chủ sở hữu quy định. Các chuyên gia thiết kế API có thể truy cập và kiểm tra các API mới trước khi thêm nó vào thư mục của họ.

- Vài website API phổ biến bao gồm:
  
  - Rapid API : Siêu thị API toàn cầu lớn nhất với hơn 10,000 APIs công cộng và hơn 1 triệu các nhà phát triển trên site. RapidApi cho phép người dùng trực tiếp kiểm tra API trên môi trường trước khi xuât bản để bán.
  - Publics API: Nhóm môi trường từ các API từ xa trong 40 danh mục, làm cho nó dễ hơn  để duyệt và tim tháy đúng cái mà bạn cần.
  - APIForThat và APIList - cả hai website này có danh sách của 500+ web API cùng với các thông tin chi tiết trên các sử dụng của chúng. 

### CỔng API là gì ? 

Một cổng API là 1 công cụ quản lý API cho các công ty khách nơi mà sử dụng các bảng vùng của dịch vụ backend. Cổng API cơ bản xử lý các nhiệm vụ thông thường như xác thực người dùng, thống kê và  quản lý  tốc độ cái mà áp dụng cùng với tất cả các cuộc gọi API

Amazon API Gateway is a fully managed service that makes it easy for developers to create, publish, maintain, monitor, and secure APIs at any scale. It handles all the tasks involved in accepting and processing thousands of concurrent API calls, including traffic management, CORS support, authorization, and access control, throttling, monitoring, and API version manageme