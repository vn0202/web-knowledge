
 ## bài dịch từ [https://www.prequel.co/blog/sql-maxis-why-we-ditched-rabbitmq-and-replaced-it-with-a-postgres-queue](https://www.prequel.co/blog/sql-maxis-why-we-ditched-rabbitmq-and-replaced-it-with-a-postgres-queue)

#  SQL Maxis: Tại sao chúng ta bỏ qua RabbitMQ và thay thế bằng hàng đơị Postgres

 Nắm bản chất tự nhiên của Maxi SQL : chúng ta loại bỏ RabbitMQ khỏi cơ sở hạ tầng của chúng ta như nào và thay nó với hàng đợi Postgres.

 Chúng ta gần đây đã loại bỏ RabbitMQ ra khỏi hệ thống và thay nó bằng 1 hàng đợi được xây dựng dựa trên CSDL Postgres của chúng ta và viết bằng SQL. Sự thay đổi này cần nửa ngày để triển khai và kiểm tra và có 1 giá trị thực là -580 LOC. càng quan trọng hơn , nó đã cải thiện triệt để mức độ tin cậy và khả năng phục hồi  của hệ thống của chúng ta. Điều này là 1 bài viết về sự thay đổi đó, sự hợp lý đằng sau nó và cách chúng tôi đã làm. 

Chú ý: điều này không phải là 1 bản cáo trạng chống lại RabbitMQ hay các hệ thống hàng đợi khác. Nó có thể ( giống như, thực tế) cái mà chúng ta đã hiểu sai .Nếu bất cư điều gì, nó là 1 cái đẩy để giữ mọi thứ đơn giản. và tất nhiên, một lời nhắc nhở rất thành kiến rằng SQL có sức mạnh không thể tin được. Thực tế, nó thường là tất cả những gì chúng ta cần. Chúng ta mơ ước 1 thế giới nơi mà mọi thứ là SQL? Có thể. 

# Một số bối cảnh hữu ích. 
 Hãy nói tóm tắt về tại sao chúng ta cần 1 hàng đợi đầu tiên. `Prequel` là 1 kênh dữ liệu quy mô lớn: chúng ta giúp công ty B2b Saas đồng bộ dữ liệu với hoặc từ CSDL của khách hàng của họ. Nói cách khác, các doanh nghiệp của chúng ta bao gồm chuyển dịch dữ liệu giữa dbs và các kho khác nhau. Mỗi chuyển dịch này có thể được mô hình hóa như 1 công việc, nơi mà được đặt vào 1 hàng đợi và thực hiên bởi các `worker`. Nột đặc điểm của những công việc này là chúng có thể rất dài để tiến hành: không phải là chưa từng nghe việc chèn data cần vài giờ và công việc nhanh nhất cần ít nhất vài giây. Để làm cho tât cả những công việc này chạy mượt mà, chúng ta liệt kê và loại bỏ hàng nghìn công việc mỗi ngày. 

# RabbitMQ: đủ tốt để bắt đầu 

Khi chúng ta khởi tao thiết kế cho hệ thống, chúng ta định cư trên RabbitMQ như 1 giải pháp hàng đợi đúng đắn cho công việc. Nó được áp dụng rộng rãi, dựa trên 1 giao thức được áp dụng rộng hơn (AMQP), dường như hoàn thiện hơn và ở cấp độ sản xuất, và trông đủ dễ để thêm vào biểu đồ Helm của chúng ta. Chúng ta đã viết nó trên Golang bọc xung quanh 1 mã khách đang tồn tại, không nghĩ về nó hai lần và đã giao nó.

Các thiết lập của chúng ta khá đơn giản. Chúng tôi đã cấu hình nó để bất kỳ các tin nhắn nào có thể chỉ được thực hiên bởi 1 khách hàng: điều này cho phép chúng ta tránh có những `worker` khác nhau chạy cùng 1 chuyển dịch, cái mà có thể lãng phí máy tính của mọi người. Chúng tôi cũng đã thiết lập nó để người tiêu dùng sẽ thao tác thủ công các tin nhắn ACK. CHúng tôi đã cố gắng giữ mọi thứ nhẹ nhất có thể và nó làm việc tốt cho chúng ta trong 1 thời gian 


# Các vấn đề bắt đầu nẩy sinh 

Chúng ta đã có các vấn đề trải nghiệm và các thach thức trong 1 vài tháng. Chúng ta đã dành rất nhiều thời gian để điều chỉnh hành vi kết nối lại của người tiêu dùng, aka điều xảy ra nếu bẳng cách nào đó 1 trong các `worker` làm mất các kết nối của nó tới hàng đợi. Chúng ta đã có 1 lỗi đa luồng khó chịu nơi mà người dùng hết thời gian từ hàng đợi sẽ gây ra kết nối hia lần, làm gia tăng số lượng hàng đợi theo cấp số nhân. Điều này dẫn tới một vài đồ thị sử dụng bộ nhớ hài hước. Chúng tôi đã làm việc với những điều đó và tiếp tục. 

Chó đến khi 1 buổi tối định mệnh, khi chúng ta đươc 1 khách hàng phản hồi. Họ đang nhìn thấy những tri hoãn một cách bất kỳ các giao dịch của họ trong 1 vài giờ, thình thoảng nhiều tới mức hệ thông của chúng ta sẽ đánh dấu chúng là cũ và hủy chúng. Không có bất kỳ lời giải thích đúng nào cho điều này: Hàng đợi của chúng ta đã không đang thể hiện bất kỳ công việc tồn đọng lớn nào, các `worker ` của chúng ta trông có vẻ ổn và đa số các công việc được thực hiện ổn. Ngoại trừ thỉnh thoảng, dường như người di lạc không xác đinh. Tất nhiên, điều đó không làm cho 1 câu htrar lời hợp lý cho khách hàng của chúng ta: "xin lỗi, chúng tôi biết 1 số giao dịch của bạn bị trì hoãn một vài giờ,, nhưng hệ thống của chúng tôi ổn nên kệ :>"

Sau 1 vài giờ đầu tư và 1 vài lần đập đầu mạnh vào bàn chúng tôi đã quản lý được nguồn gốc cái gây ra vấn đề. Hóa ra nỗi người tiêu dùng RabbitMQ đã đang tìm nạp trước các tin nhắn (job) khi nó đón 1 cái hiện tại. Điêu này ngăn cản các ngươi tiêu thụ khhác khỏi việc xác nhận tin nhắn và bởi vậy, ngăn cản nhận nó từ hàng đợi. Hãy đọc tài liệu RabbitMQ ở [đây](https://www.rabbitmq.com/confirms.html#channel-qos-prefetch)

Bây giờ, điều này là ổn khi hầu hết các công việc nhận môt khoảng thời gian gần như đồng đều và có giới hạn thời gian. Nơi mà mọi thứ không thể đoán trước là khi một vài công việc cần nhiều giờ ( hãy nhớ, chúng ta đang nói về chuyển dịch dữ liệu ở đây. ). Cái đã đang xảy ra trong trường hợp của chúng ta: 1 `worker` sẽ nhận 1 tin nhắn, và tìm nạp trước cái tiếp theo. Nó sẽ thực hiện tin nhắn hiện tại , một chuyển dịch nhiều giờ và nhận cái tiếp theo làm con tin  1 cách hiệu quả cho đến khi nó hoàn thành tin nhăn mà nó đnag nhai.

Tệ hơn, không có cách nào ( cái mà chúng tôi có thể tìm ) để tắt cư xử . Bạn có thể thiết lập tìm nạp trước đếm tới 1, có nghĩa là mỗi `worker ` sẽ tìm nạp nhiều nhất 1 tin nhắn. Hoặc bạn có thể thiết lập nó là 0, có nghĩa là chúng sẽ tìm nạp một số lượng không giơi hạn các tin nhắn. NHưng nó dường như không có cách nào, thực tế, thiết lập đếm tìm nạp trước là không ( tắt tìm nạp trước)


# Earning our stripes as SQL maxis

Chúng ta đã có 1 sự hiểu biêt tốt về cái đã xảy ra, nhưng chúng ta không có cách để ngay lập tức sửa nó. Điều này đang gây ra các vấn đề cho người tiêu dùng sản xuất, vì vậy đợi nó không phải là 1 lựa chọn.  Chúng ta không thể tìm ra cách để làm cho hàng đợi cư xử theo cách mà chúng ta muốn nó nhu thế. Chúng ta cũng không thể chỉ loại bỏ toàn bộ hàng đợi..Hoặc chúng ta có thể...


Hóa ra là chúng ta có thể thực sự tạo ra lại các chức năng hàng đơị tương tự trong CSDL Postgres, nhưng tinh chỉnh nó để phù hợp với chính xác các yêu cầu của chúng ta, Tốt hơn, triển khai đó sẽ là 1 cách đơn giản hơn và đầy đủ khả năng điều chỉnh trong tương lai nế các yêu cầu của chúng ta thay đổi theo bất kỳ cách nào đó. 

vi thế nó là những gì chung tôi đã xây dựng: một hàng đợ mới được hỗ trợ bởi 1 bảng đơn Postgres, đơn giản không ngờ. Các nhà xuất bản của chúng tôi đã viết nó. Các nhà tiêu thụ của chúng tôi ( workers) đọc nó từ đấy. Chúng tôi bảo trì mọi thứ như thứ tự hàng đợi bằng cách thêm 1 mệnh đề `orrdderr by` trong câu truy vấn cái mà c=người tiêu thụ sử dụng để đọc nó từ đấy ( đôtj phá, tôi biết).và chúng tôi đảm bảo rằng các công việc sẽ không được đón nhận bởi nhiều hơn 1 `worker` thông qua khóa cấp dòng đoc/ghi đơn giản. Hệ thông mới là thực sự đơn giản đến ngớ ngẩn khi bạn nhìn vào nó., và đêìu đó là 1 thứ tốt. Nó cũng cư xử  hòan hảo cho đến nay .

 Nó đến với 1 vìa lợi ích đáng kể cho nhóm của chúng ta. Thứ nhất,trạng thái ứng dụng của chúng ta sẽ không còn trải rộng trên hai hệ thông ( lưu trữ RabbitMq và postgres). Nó bây giờ là trung tâm trong CSDL ứng dụng của chúng ta. Điều này làm cho khôi phục thảm họa sẽ dễ hơn và gia tăng khả năng phục hồi cảu hẹ thống 1 cách khái quát bằng cách xóa các mảnh di chuyển. 

Tốt hơn , chúng ta đã hiểu toàn booh hệ thống hàng đợi này, thực tế, tốt vì chúng ta đã viết nó và đeue trôi chảy SQL. Điều này cho phpes chúng tôi tinh chỉnh nó nếu chung  tôi cần để cập nhật cư xử cảu nó và giám sát và sữa lỗi dễ hơn. CHúng ta có nhiều công cụ thiết lập để làm việc với Postgres và chúng ta không cần nhớ cách đẻ thiết lập điều một mảnh khác nhau của cơ sở hạ tầng như chúng ta đã làm với RabbitMQ. Điều này có thể nghe không nhiều, nhưng sự khác biệt về thời gian và dộ phức tạp lmaf nên 1 thế giới của sựu khácm biệt trong suốt thời gian ngừng hoạt động.

Mort trong các thành viên team của tôi đã quen chỉ ra rằng " bạn co thể làm điều này trong Postgres" bất cứ khi nào chúng tôi làm một vài loại hệ thống hoặc nói về khia triển 1 đặc tính mới. nó nhiều tới mức nó trở thành 1 loại même ở công ty. Nhưng có 1 lý do tốt cho điều này: anh ây đúng. Hóa ra, khi vấn đề của bạn liên quan đến các thực thi rằng buộc trên dữ liệu của bạn, bạn có thể làm nó trong Postgres và nó có thể là cách đơn giản hơn hàng núi code bạn sẽ viết cho nó. Chúng tôi tư hào về SQL m



