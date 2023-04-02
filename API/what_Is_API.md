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
How to secure a REST API?
All APIs must be secured through proper authentication and monitoring. The two main ways to secure REST APIs include:

1. Authentication tokens
   These are used to authorize users to make the API call. Authentication tokens check that the users are who they claim to be and that they have access rights for that particular API call. For example, when you log in to your email server, your email client uses authentication tokens for secure access.

2. API keys
   API keys verify the program or application making the API call. They identify the application and ensure it has the access rights required to make the particular API call. API keys are not as secure as tokens but they allow API monitoring in order to gather data on usage. You may have noticed a long string of characters and numbers in your browser URL when you visit different websites. This string is an API key the website uses to make internal API calls.

How to create an API?
Due diligence and effort are required to build an API that other developers will want to work with and trust. These are the five steps required for high-quality API design:

1. Plan the API
   API specifications, like OpenAPI, provide the blueprint for your API design. It is better to think about different use cases in advance and ensure the API adheres to current API development standards.

2. Build the API
   API designers prototype APIs using boilerplate code. Once the prototype is tested, developers can customize it to internal specifications.

3. Test the API  
   API testing is the same as software testing and must be done to prevent bugs and defects. API testing tools can be used to strength test the API against cyber attacks.

4. Document the API  
   While APIs are self-explanatory, API documentation acts as a guide to improve usability. Well-documented APIs that offer a range of functions and use cases tend to be more popular in a service-oriented architecture.

5. Market the API
   Just as Amazon is an online marketplace for retail, API marketplaces exist for developers to buy and sell other APIs. Listing your API can allow you to monetize it.

What is API testing?
API testing strategies are similar to other software testing methodologies. The main focus is on validating server responses. API testing includes:

Making multiple requests to API endpoints for performance testing.
Writing unit tests for checking business logic and functional correctness.
Security testing by simulating system attacks.
How to write API documentation?
Writing comprehensive API documentation is part of the API management process. API documentation can be auto-generated using tools or written manually. Some best practices include:

Writing explanations in simple, easy-to-read English. Documents generated by tools can become wordy and require editing.
Using code samples to explain functionality.
Maintaining the documentation so it is accurate and up-to-date.
Aiming the writing style at beginners
Covering all the problems the API can solve for the users.
How to use an API?
The steps to implement a new API include:

Obtaining an API key. This is done by creating a verified account with the API provider.
Set up an HTTP API client. This tool allows you to structure API requests easily using the API keys received.
If you don’t have an API client, you can try to structure the request yourself in your browser by referring to the API documentation.
Once you are comfortable with the new API syntax, you can start using it in your code.
Where can I find new APIs?
New web APIs can be found on API marketplaces and API directories. API marketplaces are open platforms where anyone can list an API for sale. API directories are controlled repositories regulated by the directory owner. Expert API designers may assess and test a new API before adding it to their directory.

Some popular API websites include:

Rapid API – The largest global API market with over 10,000 public APIs and 1 million active developers on site. RapidAPI allows users to test APIs directly on the platform before committing to purchase.
Public APIs – The platform groups remote APIs into 40 niche categories, making it easier to browse and find the right one to meet your needs.
APIForThat and APIList – Both these websites have lists of 500+ web APIs, along with in-depth information on how to use them.    
What is an API gateway?
An API Gateway is an API management tool for enterprise clients that use a broad range of back-end services. API gateways typically handle common tasks like user authentication, statistics, and rate management that are applicable across all API calls.

Amazon API Gateway is a fully managed service that makes it easy for developers to create, publish, maintain, monitor, and secure APIs at any scale. It handles all the tasks involved in accepting and processing thousands of concurrent API calls, including traffic management, CORS support, authorization, and access control, throttling, monitoring, and API version manageme