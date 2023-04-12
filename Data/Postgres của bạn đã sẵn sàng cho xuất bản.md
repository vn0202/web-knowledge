# Postgres của bạn đã sẵn sàng cho xuất bản chưa?
### Bài dịch từ [Crunchy Bridge](https://www.crunchydata.com/blog/is-your-postgres-ready-for-production)

Bạn đang xây dựng ứng dụng của bạn trong nhiều tháng, bạn đã kiểm tra với bản thử nghiệm người dùng,
bạn có các phản hồi và lặp đi lặp lại. Bạn đã xem qua danh sách khởi chạy của bạn,
gửi mail cho người dùng beta, xuất bản các bài blog, đăng tin tức về tin tặc 
và hi vọng các nhận xét là thân thiện. Nhưng CSDL của bạn đã sẵn sàng cho bất kỳ lý do gì có thể xảy đến vào ngày bạn xuất bản hoặc thậm chí là trong hai tháng? Đây là 1 danh sách cần xử lý để đảm bảo bạn không bị mắc kẹt.

- Đã sao lưu❓
- Tính khả dụng cao?
- Nhật ký đã cấu hình đúng?
- Đã lưu trữ và duy trì?
- Đang ghi các câu query chậm?
- Đã tự động giải thích ?
- Hết thời gian tuyên bố?
- Đã tổng hợp kết nối?

 Hãy đào sâu hơn nữa vào những mục chính.

## Backups?
Postgres có một vài cơ chế khác nhau cho sao lưu. 

- Sao lưu logic .
- Sao lưu vật lý.

Một **sao lưu logical** là 1 dữ liệu SQL thô cái mà có thể đưuọc tải trong 1 mẫu của *INSERT* hoặc tương tự. Sao lưu logical rất tốt nếu bạn muốn chuyển dữ liêu xung quanh giữa các môi trường hoặc một bản sao chép cục bộ.

- **Physical backups** là 1 sự kết hợp của sao lưu cơ bản ( các bytes trông như thế nào trên ổ đĩa) và nhật ký viết trực tiếp hoặc WAL. Nói một cách đơn giản, từ khóa Postgress thực tế chỉ là 1 nhật ký lớn chỉ được nối dưới vỏ bọc được biết đến như là WAL. Bằng cách nối sao lưu **cơ sở** với WAL, bạn có thể có bản sao lưu CSDL cái mà có thể cho 1 khoảng thời gian với 1 ảnh chụp nhanh như với pg_dump

Cả hai logical và physical có thời gian của nó, nhưng 1  mảnh quan trọng của cả hai là chúng nên được kiểm tra. Hệ thống sao lưu cái mà không dược kiểm tra có nhiều khả năng không phải là 1 bản sao lưu hoạt động. 

## Tính khả dụng cao?

Có người nghĩ răng điều này là không cần giải thích hoặc nếu bạn có bản sao lưu bạn vẫn ổn. Tuy nhiên , chúng tôi  luôn nói với các khách hàng người mà đang gặp khó khăn với quyết định xem họ cần HA không. Không có HA, nếu có 1 lỗi với CSDL của bạn, điều đó có nghĩa là bạn phỉa lấy lại từ bản sao lưu. Nếu bạn đang sử dụng 1 bản backup logials bạn có thể mất dữ liệu giữa thời điểm sao lưu và lúc CSDL của bạn hỏng. 


Nếu bạn có 1 thiết lập tốt với sao lưu vật lý và bạn đang sử dụng những thứ như pgBackrest cho việc tiếp tục bảo vệ dữ liệu của bạn là an toàn. Nhưng bạn có lẽ vẫn có những thời điểm chết đáng kể vì bạn làm việc để khôi phục lại CSDL của bạn. Một nguyên tắc chung là bạn nên có kế hoạch cho 1 giờ ngừng hoạt động trên 200GB của kích thước CSDL.

Nhập HA, với HA bạn có 1 luồng bản sao phát trực tuyến (kể cả đồng bộ hoặc bất đồng bộ) nhận các giao dịch từ 1 bản sao chính cái mà sẵn sàng chuyển sang không thành công. Việc sử dụng những thứ như bản sao ổ đĩa có thể có thể dẫn đến kết quả thời gian dự phòng không như mong muốn do Postgres đang phải trải qua quá trình khôi phục sự cố. Nếu thời gian hoạt động là quan trọng, 1 HA tốt là lựa chọn cho bạn. 


## Nhật ký 

Khi tới thời điểm để đầu tư cho vấn đề hiệu năng, nhật ký có thể là 1 chìa khóa quan trọng cho thông tin.

Về cơ bản, bước đầu tiên là đảm bảo chắc chắn bạn có 1 nhật ký ghi lại tốt. Bạn muốn một vài vùng goldilocks, bạn không muốn ghi nhật ký cho tất cả các truy vấn cái chạy trên CSDL của bạn. Bạn không muốn chỉ những truy vấn cái mà chạy hàng giờ đươc ghi lại Bạn không cần để duy trì những nhật ký tháng này qua tháng khác, thông thường một vài tuần là hợp lý, nhưng điều này thực sự phù hợp với nhu cầu kinh doanh của bạn. 
Chúng ta thường gợi ý tận dụng 1 dịch vụ ghi nhật ký của bên thứ 3 hoặc các công cụ với việc tự quản lý tất cả những điều này ví dụ như Mezmo. Sau đó, gửi tất cả các nhật ký của bạn cho ứng dụng, tiến trình ngầm và các dịch vụ khác  vào 1 nơi.

Bạn đã giữ lại, nhưng thực sự điều gì bên trong log? Một vài cái lớn là

- Tự động ghi nhật ký những truy vấn chậm 

Bao gồm các kế hoạch giải thích cho các truy vấn chậm trong nhật ký. 

- QUan tâm tới các tuân thủ và kiểm tra xem ai truy cập CSDL của bạn và như nào? Tân dụng pg_audit ( đã được dựng sẵn trong Crunchy Bridge ) để kiểm tra các truy vấn cho bất kỳ người dùng nào không sử dụng ứng dụng của bạn.

Bạn có thể đào sâu vào những điều này hơn ở đây. 

## Đừng để bất 1 truy vấn xầu  nào làm cho quá trình sản xuất chững lại 

Bạn có sợ những hoạt đọng dưới đây?



```sql
SELECT *
FROM events;
```
Hầu hết thời điểm, có những vấn đề cụ thể xảy ra khi phân tích được chạy trên CSDL sản xuất. ở các công ty nhỏ, nó là phổ biến cho CSDL ứng dụng có thể là 1 nhà kho và cũng là nguồn cung cấp CRM. Nếu bạn chọn để chạy theo cách này, sử dụng các mệnh lệnh ngoài giờ

Nếu bạn lớn hơn, nhiều hơn công ty cầu kì ( ai có những trải nghiệm được nỗi đau của việc để mọi người kết nối với CSDL sản xuất của bạn), chạy các phân tích trên các bản sao chỉ đọc hoặc ETL CSDL trong 1 nhà kho dữ liệu 

Nếu ứng dụng của bạn, hoặc cơ sở khách hàng của bạn là đang tạo ra các truy vấn xấu và không bảo đảm nơi bắt đầu, hãy đọc sâu hơn vào các để kiểm soát các truy vấn chạy sai .

## Tổng hợp kết nối 

 Trong 1 thế giới không máy chủ, nếu ứng dụng của bạn chậm, Bạn làm gì? Chạy thêm người chạy ứng dụng 

Không quá nhanh. Postgress có các giới hạn kết nối bởi vì mỗi kết nối có 1 tập bản sao của bộ nhớ cho các hoạt động CSDL cấp con , như sắp xếp và ghi các giao dịch. Nếu Postgress cho phép quá nhiều kết nối, tất cả bộ nhớ sẽ được nhân đôi để phục vụ các kết nối thay vì đánh chỉ mục ( chỉ mục thích RAM). Chú ý, Postgres đã cải thiện tuyệt vời trong việc quản lý kết nối trong những phát hành gần đây. Các quy luật cũ của việc không sử dụng nhiều hơn 500 kết nối không áp dụng như 3 năm trước, nhưng ràng buộc và giới hạn vẫn tồn tại. 

Bởi vậy, khi bạn bắt đầu mở rộng ứng dụng của bạn, nó tốt nhất để quản lý các kết nối giữa ứng dụng của bạn và CSDL của bạn. Crunchy Bridge sử dụng PgBouncer cho tổng kết các kết nối - và nó đã được thiết lập và sẵn sàng cho bạn để sử dụng. Kiểm tra tài liệu ở đây. Nó đủ dễ để bắt đầu sử dụng nó để phóng, sau đó bạn sẽ không phải chuyển để sử dụng nó về sau. 

## SUy nghĩ cuối cùng.

Postgress của bạn có thể sẵn sàng cho sản xuất trong 1 vài bước :

- sao lưu 
- Tính khả dụng cao. 
- Nhật ký 
- Lưu trữ. 
- GHi nhật ký những câu truy vấn chậm
- TỰ động giải thích 
- Đã đặt thơi gian tuyên bố 
- Kích hoạt tổng hợp kết nối 

2 xu của tôi - tìm một nhà cung cấp làm cho danh sách kiểm tra của bạn dễ dàng nhất có thể.
Enjoy this article?
