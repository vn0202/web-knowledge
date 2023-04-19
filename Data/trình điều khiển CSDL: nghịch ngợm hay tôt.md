## from [https://www.prequel.co/blog/database-drivers-naughty-or-nice](https://www.prequel.co/blog/database-drivers-naughty-or-nice)
# Trinh điều khiển CSDL: Ngỗ nghịch hay tốt?

 Một danh sách các lỗi hàng đầu trinh điều khiển và hành vi sai trái chúng ta đã phát hiện trong những năm gần đây
 Chúng ta đã dành rất nhiều thời gian viết mã cho CSDL, kho dữ liệu và các trình điều khiển của chúng trong năm nay. Giống như, rất nhiều . Thông qua tiến trình đó, chúng ta đã phát hiện  các hành vi không mong đợi. Trong cac trường hợp, những cư xử này chống lại tài liệu của công cụ. TRong các trường hợp khác, chúng chỉ đi ngược lại lẽ thường(dù sao đi nữa   ý thức của chúng ta là phổ biến  trong chừng mực  ). Trong bất kỳ hoàn cảnh nào, chúng tôi đã phát hiện ra nó sẽ thú vị để biên dịch và chia sẻ chúng, cả cho giải trí và cho hậu thế :>
 Trước khi chúng ta đào sâu vào nó, có 1 chú ý. Chúng ta cảm thấy cực kỳ may mắn để làm việc với nhiều công cụ như vậy, và có khả năng để xây dựng trên các công việc của người khác để tiếp tục phân phối các sản phẩm tốt hơn. Chúng ta đã viết các phần mềm cho cuộc sống và chúng ta là người đầu tiên biết lỗi đó đến với lãnh thổ- trừ khi bạn là siêu nhânm hoặc bạn không viết bất kỳ các mã có ý nghĩa nào, các lỗi là không thể tránh khỏi

Mục tiêu của chúng ta ở đây rõ ràng không chế giễu bất kỳ sản phẩm hay công ty cụ thể nào vì sử dug chúng. Thay vào đó, chúng tôi đã phát hiện ra các kiên thức đủ thú vị để chia sẻ. Bất cứ khi nào và ở đâu có thể, chúng tôi làm hết khả năng để trở thành công dân trung thực và báo cáo các lỗi này với chủ sở hữu tương ứng của nó. Bất kể, để ngăn cản bài viết này khỏi việc phục vụ như 1 cơ hội " làm nhục tên tuôi", chúng tôi đã nghĩ nó tốt nhất để ẩn danh CSDL và các trình điều khiển ở đây. Nếu bạn làm việc ở công ty CSDL  và bạn muốn có các phản hồi trên 1 trình điều khiển cụ thể hoặc mở 1 dòng của cuộc giao tiếp, hãy gửi cho chúng tôi 1 ghi chú ở prequel.co!

Bây giờ, không cần quảng cáo thêm, ở đây là những bản hít top đầu của "hả, WTF?" của CSDL và hành xử của trinhf điều khiển của năm nay.

# Vài trình điều khiển bên thứ nhất không khia triển chức năng đưuọc quảng cáo. 

Trình điều khiển GoLang bên thứ nhất cho đa số CSDL/dwh không triển khai chức năng sao chép tệp, mặc dù rõ ràng phát biểu trong tài liệu của nó là có. Đưa cái gì? Chúng ta không chắc nữa, nhưng chúng ta biết từ trải nghiệm đầu tay cái mà khó để giữ cho tài liệu hoàn hảo để cập nhật. Vì vậy chúng tôi sẽ đưa chúng vượt qua. 

Để mở khóa đầy đủ chức năng, ( và giải quyết các vấn đề khác) chúng ta bắt đầu sử dụng nhiều hơn trình điều khiển dựa trên ODBC. Điều này mang đến cho chúng ta các bản hit tiếp theo. 

# vài trình điều khiển ODBC rò rỉ bộ nhớ

Giao diện ODBC được thiết kế cho việc sử dụng với ngôn ngữ lập trình C. Nói cách khác, trình điều khiển ODBC, theo tự nhiên, phải chạy mã C. Hóa ra, chương trinh C có thể rò rỉ bộ nhớ, và trình điều khiển ODBC không là ngoại lệ. Vài rò rỉ là 1 bit nhỏ với mỗi cuộc gọi, trong khi số khác có các cuộc gọi cụ thể và các mẫu sử dụng cái mà gây ra chúng để rò rỉ nhiều bộ nhớ.

![ODBC](../images/ODBC.png)

Đây là 1 đồ thị hài hước của bộ nhớ sử dụng của chúng tôi, qua 1 chu kỳ vài ngày khi chúng tôi đang kiểm tra 1 rò rỉ bộ nhớ trong môi trường dev của chúng ta. NHờ dó, chúng tôi đã bắt đưuọc nó trước khi nó được sản xuất. Nguyên nhân gốc hóa ra là 1 mẫu cuộc gọi cụ thể bên trong trình điều khiển ODBC. 

# Một vài trình điều khiển rò rì chứng chỉ. 

Một vài trình điều khiển ghi các chứng chỉ nếu ở đó là 1 lỗi trong 1 chuỗi chứa 1 chứng chỉ như 1 khóa API hoặc 1 khóa bí mật AWS. Những thứ khác, những công dân trung thực hơn sẽ thay thế các trường chứng chỉ được biết với 1 chuỗi "dữ liệu đã xóa " khi đang đăng nhập vào thiết bị xuất hoặc ...

# Một vài trình điều khiển xóa lỗi phím và chỉ trả về 1 tập kết quả trống thay thế.

 Chúng ta đã học điều này 1 cách khó khăn khi đang cố để vận chuyển 1 tích hợp mới. Các mã của chúng ta trông có vẻ đúng, và làm việc với các tệp dữ liệu nhỏ. NHưng khi chúng ta cố gắng chạy nó trên các tập dữ liệu lớn hơn, nó sẽ cư xử như không có dữ lieuj trong bảng CSDL và trả về 1 tệp rỗng 

Chúng tôi đã phát hiện ra rằng vài trình điều khiển có hết hạn thời gian truy vấn ẩn. Và đó, có lúc, khi các hết hạn thời gian đó xảy ra, các trình điều khiển không sinh ra hoặc trả về bất kỳ 1 loại ngoại lệ hay lỗi nào. Thay vào đó, chúng đơn giản là trả về 1 tệp rỗng.

# Môt vài trình điều khiển không đồng ý với cách các kỷ nguyên nên được đại diện 

Nó không là bí mật cái mà mẫu thời gian, múi giờ và các đối tượng hỗn hợp thường gây ra hiểu lầm cho các nhà phát triển [ bằng chứng A](). Cái mà có lẽ ít được biết đến hơn là ngy cả epochs (đai diện giây Unix của timestamp), mặc dù các lời thỉnh cầu của chúng như là đại điện không múi giờ, có thể khó để làm việc với nó. 

Hóa ra, các CSDL thường không đồng sy cái mà chính xác là 1 epoch nên như nào. Một vài tuân theo định nghĩa nghiêm ngặt, việc cư xử chúng rõ ràng là 1 số giây ĐÃ trôi qua kể từ giữa đêm Giờ UTC ngày 1 tháng 1 năm 1970. Trong các trường hợp này, chúng được lưu và đại diện như int32. Số khác tuy chọn bao gồm tính chính  mili giây hay nano giây, Đây là những đại diện dạng int64 hay như các loại khác của số thực,  với số nguyên là giây và phần nhỏ là độ chính xác được thêm vào.

Điều này có thể gây ra toàn bộ các vấn đề tích hợp dữ liệu khi chuyển dịch dữ liệu. Trong trường hợp tốt nhất, không tính chính xác là  mất đi 1 cách có ý nghĩa. TRong các trường hợp nguy hiểm hơn, quá trình tải công việc nhưng tạo ra 1 vùng deltas giữa các timestamp cái mà chỉ có thể phát hiện được bằng cách phân biệt dữ liệu đã tải một cách cẩn thận.

Trong các trường hợp khác, các trình điều khiển khác cho cùng 1 kho chứa có thể cư xử với chúng khác nhau, một cái cư xử chúng như là số nguyên, cái khác thì coi như số thực. chúng ta va phải điều này khi chuyển đổi từ trình điều khiển bản địa sang 1 trình điều khiển ODBC cho 1 cái được biết đến là nhà kho. Tất cả điều này nói rằng: chỉ bởi vì bạn chuyển các mầu thời gian của bạn như 1`epochs` không có nghĩa là bạn đã không hieieerur gì khi nào nó đến độ phức tạp của xử lý thời gian. 

# Vài nhà cung cấp CSDL không quan tâm về các thay đổi phá vỡ 

 Một kho đữ liệu nổi tiếng được cập nhật các định dạng chuỗi kết nối được mong chờ bởi trình điều khiển của nó, việc dẫn tới 1 lỗi kết nối mơ hồ. Phiên bản tương thích ( Semver) thay đổi đã chỉ ra 1 thay đổi "MINOR", thậm chí điều này trong thực tế không phải là 1 thay đổi tương thích ngược. Thông qua điều này, chúng ta đã học 1: là không phải tất cả các nhà cung cấp cư xử với các trinh điều khiển của họ như một đề nghị sản phẩm lớp đầu  và 2 là để luôn kiểm tra mức độ hỗ trợ 1 1 nhà cung câp cung cấp cho các trình điều khiển trước khi cam kết để hỗ trợ 1 CSDL được cung cấp hay 1 kho dữ liệu. 

# Một vài trinh điều khiển khăng khăng về chuỗi như là 1 sự cố chấp 

Vài trình điều khiển tin rằng các chuỗi phải là UTF-8, số khác tin rằng các chuỗi chỉ là các bytes và sẽ luôn luôn cư xử chúng như là các mảng bytes. Ngoài ra, một vài trình điều khiển có những ngoại lệ kỳ lạ về các ký tự cái mà trình điều khiển của họ có thể hỗ trợ ( hầu hết cái mà không thực sụ dược suất bản)

# Vài kho dữ liệu chỉ thực sự là không nhất quán khi đề cập đến các loại 


Chúng ta có rất nhiều để nói về chủ đề cái mà điều này xứng đáng để là 1 bài viết của riêng nó. Nhưng tổng quan, kho dữ liệu khác nhau rất nhiều trong các mức độ khoan dung chúng xử lý các kiểu . Một vài sẽ tải 1 thực thể  của 1 DECIMAL (38,9) một cách happy, bởi vì chúng không tập trung vào kích thước, độ chính xác hay giới hạn quy mô  trên kiểu số của chúng. SỐ khác sẽ phàn nàn nhiều và gọi bạn là 1 thằng ngu cho việc nghĩ  vê những thứ như thế.  
 Một vài  thậm chí không có hỗ trợ đầy đủ các kiểu với trình điều khiển của chúng. Hầu hết chúng không hỗ trợ tuyệt vời khi có các cột JSON và cư xử nó theo nhiều cách có ỹ nghĩa. 

# Một vài CSDL không tuân theo các quy chuẩn thông thường 
 Chúng ta gần đay đã phát hiện ra 1 lỗi khi đang cố gắng tải file Parquet được tạo bởi DuckDb trong Databricks.Điều hài hước là các tệp giống nhau có thể được tải tối trong các kho dữ liệu khác, chẳng hạn như Snowflake hay Bigquery. Lỗi cụ thể là :
```postgresql
Only one of num_children and type should be set in SchemaElement.

```
Dựa trên tìm hiều sâu hơn, chúng ta nhận ra điều này là sản phẩm của hai điều:

- Databircks triển khai thông số Parquet theo nghĩa đen nhiều hơn các CSDL khác, ít nhất khi  nói tới siêu dữ liệu. Nó không hoàn toàn ngạc nhiên, cái đã cung cấp rằng Parquet có hỗ trợ sâu  hơn trong hệ sinh thái Spark. 
- DuckDB cài đặt không chính xác cả trường siêu dữ liệu khi tạo ra 1 tệp Parquet.

Chúng ta đã báo cáo lỗi với dự án DuckDB OSS để họ biết, và họ đã sửa nó chi trong 48 giờ. Chúng ta là những fans to lớn của dự án DuckDB để bắt đầu với nó, nhưng điều này vượt ra bất kỳ những gì chúng ta mong đợi. Kudos cho đội.

Và đó là nó. Chúng tôi đang có kế hoạch dành nhiều thời gian cho CSDL và trình điều khiển vào năm 2023, vì vậy ở đây đang viết nhiều phần mềm hơn , phát hiện các hành vi kỳ lạ hơn và gửi lỗi nhiều hơn và làm việc nhiều hơn với CSDL. 

Nếu có bất kỳ lỗi cụ thể nào cái mà bạn đã phát hiện ra trong công việc của bạn, chúng tôi rất vui để nghe về nó. Hãy thấy thoải mái để cho chúng tôi 1 dòng và chúng ta sẽ ghi lại những điều đó. 
If there are any specific bugs that you’ve uncovered in your own work, we’d love to hear about them. Feel free to drop us a line and we’ll document those as well!