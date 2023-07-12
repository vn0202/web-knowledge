### from [https://web.dev/inp/](https://web.dev/inp/)

# Interaction Next Pain (INP)

    Interaction next pain(INP) là một chỉ số Core Web Vital đang chờ xử lý sẽ thay thế cho chỉ số 
    First Input Delay(FID- Độ trễ đầu vào ddaaafu tiên) vào tháng 3 năm 2024.
    INP đánh giá khả năng sử dụng dữ liệu từ `Event Timing API`. Khi có một tương tác khiến 1 trang trở lên không đáp
    ứng, đó là 1 trải nghiệm người dùng kém. 
    INP quan sát độ trễ của tất cả các tương tác một người dùng tương tác với trang, và báo cáo một giá trị duy nhất mà 
    tất cả ( hoặc gần như tất cả ) tương tác dưới đây. 
    Một `INP` thấp có nghĩa là trang đó hoàn toàn có khả năng phản hồi nhanh tới tất cả các hầu hết đa số các tương tác 
    của người dùng. 

Dữ liệu sử dụng của Chrome thể hiện rằng 90% thời gian người dùng trên một trang là sau khi nó được tải, Bởi vậy, việc cẩn thận đo lường khả năng phản hồi thông qua vòng đời của trang là quan trọng . Đây là cái mà chỉ số INP khám phá.

Phản hồi tốt có nghĩa là trang đó phản hồi nhanh tới các tương tác với nó. Khi một trang phản hồi với các tương tác, kết quả là một phản hồi trực quan, cái mà dược đại diện bởi trình duyetj trong 1 khung tiếp theo mà trình duyệt hiển thị. `Visual feedback` cho  bạn biết , ví dụ , một phần tử bạn thêm vào 1 giỏ hàng shopping online là đã đang được thêm hay không, liệu một menu điều hướng trên mobile có được mở, nếu một nội dung biểu mẫu đăng nhập đang được xác thực bởi máy chủ hay không và etc..

Một vài phản hồi bản cần nhiều thời gian hơn các tương tác khác theo một lẽ tự nhiên, nhưng cho các tương tác đặc biệt phức tạp, nó quan trọng  để nhanh chóng thể hiện phản hồi trực quan như một dấu hiệu cho người dùng biết cái gì đang xảy ra. THời gian cho đến khi `next pain` là cơ hội sớm nhất đề thực hiện điều đó. Bơi vậy, mục đích của `INP` không để đo lường tất cả các hiệu ứng cuối cùng của tương tác ( chẳng hạn như tìm nạp mạng và cập nhật UI từ các thao tác bất đồng bộ), nhưng là thời gian mà `next pain` bị chặn ở đó. Bằng cách trì hoãn `visual feedbeck`, bạn có thể tạo ra ấn tượng với người dùng rằng trang web đang không phản hồi lại các hành động.

Mục tiêu của INP là đảm bảo thời gian từ khi người dùng khởi tạo một tương tác cho tới khi khung tiếp theo được `pain` là ngắn nhất có thể, cho tất cả hoặc gần như tất cả các tương tác người dùng tạo ra. 

Trong video dưới dây, ví dụ bên phải cung cấp các phản hồi ngay lập tức rằng 1 accordion đang mở. Nó cũng chứng minh khả năng phản hồi kém có thể gây ra nhiều phản hồi không đúng dự định với đầu vào bởi vì người dùng nghĩ trải nghiệm đã hỏng. 
 Ví dụ về khả năng phản hồi kém đấu với phản hồi tốt . Ở bên trái, các tác vụ dài chặn việc mở accordion. Điều này khiến người dùng nhấp nhiều lần, nghĩ rằng hành động bị hỏng. Khi mà luồng chinsg bắt kịp, nó thực thi trễ , kết quả là việc mở dóng accordion không như mong đợi.
Link tới ví dụ [ở đây ](https://storage.googleapis.com/web-dev-uploads/video/jL3OLOhcWUQDnR4XjewLBx4e3PC3/WSmcjiQC4lyLxGoES4dd.mp4)

Bài viết này đã giải thích cách mà INP hoạt động, cách để đo lường nó và chỉ ra các tài nguyên để cải thiện nó. 

# INP là cái gì? 

INP là một chỉ số cái mà đo lường khả năng phản hồi tổng quan của một trang web với các tương tác của người dùng bằng  cách quan sát độ trễ của tất cả các tương tác click, tap, nhấn phím xảy ra thông qua tuổi thọ của một lần ghé qua trang của người dùng. Giá trị cuối cùng của INP là tương tác được quan sát dài nhất bỏ qua các ngoại lệ.

## **Một chú ý trên cách tính toán INP**
Như đã bắt đầu ở trên, INP được tính bằng các quan sát tất cả các tương tác với trang. Cho hầu hết các trang, tương tác với độ trễ tệ nhất được báo cáo như là INP. Tuy nhiên, với các trang với số lượng lớn các tương tác, các trục trặc ngẫu nhiên có thể dẫn đến các tương tác cao không bình tường trên một trang web phản hồi khác. Các nhiều tương tác, càng nhiều khả năng điều này xảy ra. Để tính toán điều này, và cung cấp 1 đo lường tốt nhất cho khả năng phản hồi thực tế với những kiểu trang đó, chúng ta bỏ qua một tương tác cao nhất cho mỗi 50 tương tác. Hầu hết đa số trải nghiệm trang không quá 50 tương tác, vì vậy sẽ báo cáo tương tác tệ nhất. Phần trăm  thứ 75 của tất cả lượt xem trang là sau đó được báo cáo là bình thường, cái mà tương lai xóa bỏ các ngoại lệ để lấy về giá trị cái mà đa số trải nghiệm người dùng hoặc tốt hơn 


