### from [https://vadimkravcenko.com/shorts/database-migrations/](https://vadimkravcenko.com/shorts/database-migrations/)

# Summary 

##  Tác giả phát biểu rằng "data migrations" là một trong những vấn đề phiền toái nhất cần giải quyết trong suốt cuộc đời của 1 kỹ sư phần mềm. Không những vậy, nếu nó sai, như nó vẫn thường xảy ra, mọi người thường lo lắng về những thứ liên quan đến thay đổi lược đồ. 

##  Tại sao nó lại quá phiền toái? Thoạt nhìn nó có vẻ đơn giản, nhưng nó thực sự khó khi bắt đầu
Có rất nhiều framework thông minh giúp chúng ta tự động sinh ra các file migrations, lưu các tham chiếu migration trong CSDL, và cho phép dễ dàng quay lại trạng thái trước, nhưng cũng vẫn có nhiều thứ cần quan tâm khi thực hiện "data migration".
Tưởng tượng, khi bạn cần chia trường name thành hai trường `first_name` và `last_name` trong bảng User. Mọi thứ đều ổn. Bạn thực hiện việc di chuyển lược đồ, chạy các kịch bản migration, và deploy phiên bản API mới nhất với những thay đổi. Nhưng,bỗng xẩy ra lỗi với các field mới. Nhiều người dùng k thể lưu do vi phạm các lỗi xác thực. Vấn đề này được xem là nghiêm trọng, và bạn quyết định 'rollback'. Nghe có vẻ đúng. Bạn hoàn nguyên lại lược đồ và hoàn nguyên app lại trạng thái trước đó, nhưng sau đó bạn nhận ra rằng vieecjc ghi vẫn diễn ra với các trường mới trong khoảng thời gian nhỏ triển khai, và bây giờ vài người dùng thiếu dữ liệu trong field name, dẫn đến dữ liệu không nhất quán.
Và nếu trường hợp yêu cầu không có thời gian chết? 

##  các lý do tác giả cho rằng 'data migration ' là khó: 

1. Khi triển khai 1 sản phẩm, bạn chỉ có thể nhìn trước dược 1 vài tháng, có thể là 1 năm, và phần mềm của bạn sẽ phát triển như nào và bạn chuẩn bị như nào cho nó. Một năm nào đó trong tương lai, chủ sở hữu sản phẩm quay lại và nói rằng, " Okey, ứng dụng tài chính của chúng ta không còn dựa trên các giao dịch nữa. mọi thứ là 1 đăng ký". điều mà rõ ràng là 1 di chuyển CSDL lớn để phù hợp với điều này ( hoặc các phương án dự phòng). 
2. Thực hiện di chuyển giống  như bạn làm việc với dây diện trực tiếp. Bạn có 1 chiếc bóng đèn mới cần treo lên trần nhà, nhưng bạn đang làm điều đó mà không tắt điện
3. Mỗi việc dịch chuyển bạn đang triển khai cần làm việc với các ngữ cảnh khác nhau: 
- Upgrading ( migrating up) - một tính năng mới được xây dựng, dữ liệu model được thêm/sửa/xóa. Ứng dụng cũ và mới vẫn thực hiện các chức năng như mong đợi 
  
- Downgrading ( migrating down) - khi có lỗi xảy ra, đó là dữ liệu không nhất quán, cần một cách để quay lại trạng thái ổn định trước đó môt các có kiểm soát. Không thay đổi thủ công trong DB.

- Mọi thứ ở giữa - nghĩa là tất cả các logic chuyển dời dữ liệu cần được chú ý. Mặc dù ngày nay có nhiều cách để thực hiện việc di chuyển dữ liệu với thời gian dài hơn với khía niệm "ghi kép" hoặc bảng "ma",
- Việc di chuyển dữ liệu lớn vẫn là 1 vân đề bởi vì nó thường không phải là công việc của 1 người. Dữ liệu càng lớn, càng nên có nhiều người tham gia. Nó tốt  nhất là nên có những người khác đứng bên khi triển khai, sẵn sàng nhảy vào khi có lỗi xảy ra


## Các ví dụ : 
1. Thêm 1 trường mới: 
 Đây là trường hợp đơn giản nhất hay gặp. Trường mới sẽ được truy cập và được sử dụng chỉ khi ứng dung mới được triển khai. 

 **Sau khi thêm trường vào ORM(Object relation mapping):** 
 - viết kịch bản migration. 
 - Kiểm tra kịc bản dưới local để đảm bảo  nó hoạt động như mong đợi. 
 - Kiểm tra khả năng rollback. 
 - với các field non-nullable, hãy cung cấp 1 giá trị mặc định. Điều này là hết sức quan trọng để tránh các vấn đề với các bản ghi đã tồn tại nhưng không có trường này. Nếu có logic liên quan, ví dụ trường là tổng hợp của cac field khác, thêm các logic vào kịch bản.( Khái niệm này chỉ nên áp dụng cho các CSDL nhỏ - vài triệu bản ghi  và không thực hiện cho các CSDL lớn. )


 **Cách tiếp cận `triển khai hai giai đoạn`:**

 - Đầu tiên, triển khai các thay đổi về CSDL. Vì thêm 1 trường mới không phá vỡ bất kỳ điều gì trong ứng dụng của bạn, nó nên hoạt dộng mượt mà thậm chí với phiên bản hiện tại của ứng dụng.
 - Một khi bạn đã `tự tin` rằng kịch bản hoạt động ổn định và không phát sinh bất kỳ vấn đề nào, hãy deploy các thay đổi ứng dụng. 

Sau khi triển khai ( và đương nhiên trướ đó nữa), quan sát ứng dụng của bạn và hiệu suất CSDL. Kiểm tra bất kỳ cac hành vi không mong đơị liên quan đến trường mới của bạn. 

2. Xóa fields 

 Nếu khi thêm field mới, bạn deploy data change trước , nhưng khi xóa field, cách tiếp cận sẽ khác. 

- Đánh dấu các mã liên quan tới field sẽ bị xóa. 
- Sau khi đánh dấu hoàn tất, bắt đầu loại bỏ dần việc sử dụng các field đó. 

Việc này đảm bảo cho ứng dụng vẫn ổn định với việc không sử dụng field sẽ xóa. 

# Triển khai ghi kép 

1. Thêm các trường mới vào CSDL ( không có ảnh hương trong quá trình chay code). 
   2. Deploy thay đổi CSDL, tái cấu truc code, ( nơi bạn sẽ bắt đầu ghi vào cả trường mới và trường cũ, với các logic tương ứng). Việc `reading` vẫn thực hiện trên field cũ.Việc `writing` xảy ra ở cả hai field như 1 phần của Transaction đơn. 
   3. So sánh dữ liệu và đảm bảo nó tương thích. 
   4. Viết mã migration thực hiện chuyển phần còn lại của dữ liệu từ trường cũ sang trường mới trong 1 định dạng đúng( hoặc sử dụng gh-ost từ github). 
   5. Triển khai migration và thay đổi đường dẫn đọc sang trường mới. Ghi vẫn xảy ra ở cả hai. 
   6. Xác nhận ứng dụng và dữ liệu vẫn nhât quán. 
   7. Xóa việc ghi ở trường cũ . Read/write chỉ xảy ra trên trường mới. Trường cũ vẫn tồn tại nhưng không ghi. 
   8. Xác thực ứng dụng và dữ liệu vẫn ổn định. 
   9. Xóa những code liên quan đến trường cũ. 
   10. Xác thức ứng dụng và data vẫn nhất quán. 
   11. Triển khia kịch bản migration để xóa field cũ. 
   12. Shake hands with your teammates.

# Conclution

1. Không sửa trực tiếp CSDL. luôn luôn sử dụngg 1 kịch bản migration 
2. Phiên bản CSDL nên chứa trong chính CSDL
3. Nếu bạn không có thời gian hở để bảo trì, tập trung vào tiến trình ghi kép. 
4. Khi xây dựng các tính năng với các thay đổi đáng kể CSDL - hãy nghĩ đến khả năng tương thích ngược và sửa lỗi trừu tượng. 
5. Cân nhắc sử dụng các tool mới nhất để làm cho việc di chuyển dễ hơn