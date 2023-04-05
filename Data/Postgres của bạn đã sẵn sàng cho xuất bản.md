# Postgres của bạn đã sẵn sàng cho xuất bản chưa?

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




If you have a good setup with physical backups and you’re using something like pgBackRest for continuous protection your data is safe. But you still may have significant downtime as you work to recover your database. A rough rule of thumb is you should plan for 1 hr of downtime per 200GB of database size.

Enter HA, with HA you have a streaming replica that in real-time (either synchronous or asynchronous) receives the transactions from a primary that is ready to be failed over to. Using something like disk replication could result in unexpected failover times due to Postgres having to go through crash recovery. If uptime is critical, a good HA setup is your f
