### from [https://dev.to/logrocket/comparing-classless-css-frameworks-3267](https://dev.to/logrocket/comparing-classless-css-frameworks-3267)

# Summary 
TRong thời buổi ban đầu của internet, Hầu hết các nhà phát triển web đều viết CSSc định kiểu CSS để cấu trúc và định kiểu cho trang web. Ngày nay, chúng ta sử dụng các frameworks css đầy đủ tính năng, được phát triển trước để thực hiện điều này và tiết kiệm thời gian phát triển. Nhưng những frame này không tự động định kiểu cho các phần tử DOM bằng cách sử dụng csac thẻ ngữ nghĩa HTML bởi vì những frame này là các frame CSS dựa trên class. 

Các frameworks CSS không class, ngược lại, không định nghĩa các class CSS. Thay vào đó, chúng tự động định kiểu cấu trúc HTML thô của bạn dựa trên các thẻ ngữ nghĩa HTML. Ví dụ, hầu hết các frame không class áp dụng 1 style button cho tất cả các thẻ <button>. 

Tác giả giải thich về các khái niệm của các frameworks CSS không class với các trường hợp sử dụng và so sánh 10 frameworks CSS không Class với các bản xem trước trực tiếp.

1. Frameworks không class là gì? 
 Một framework CSS không class là một định kiểu CSS không đinh kiểu các phần tử DOM dựa trên các thẻ ngữ nghĩa HTML. Một trang web thường chứa vài kiểu thẻ HTML, như headings, lits, tables, paragraphs, và forms. 

HTML thường đề xuất vài thẻ để kết xuất các thành phần này, như thẻ <table> để tạo bảng. Mỗi thẻ chỉ định cách mà cuối cùng các phần tử DOM sẽ xuất hiện như nào - khi chúng at sử dụng thẻ <table>, chúng ta biết rằng trình duyệt sẽ render 1 bảng. 
 
Các frameworks CSS không class giúp bạn style cac trang HTML thô ngay lập tức mà không cần sử dụng các lớp được định nghĩa trước, như các frameworks dựa trên class, hoặc thậm chí viết 1 dòng đơn CSS. Ngòai ra, các frameworks không class cung cấp đa dạng cac themes và các biến CSS để tùy chỉnh tốt hơn. 

Hãy xem ví dụ dưới đây về CSS không class định nghĩa 1 bảng: 
```css
table {
   /* styles for tables */
}

th, td {
   /* styles for table headers and rows */
}
```

 Các frameworks CSS mà chúng tôi sẽ đề cập ghi đè các styles được định nghĩa bởi định kiểu của người dùng và áp dụng các style tùy chỉnh cho tất cả các thẻ HTML tiêu chuẩn. KHi bạn nhập 1 định kiểu framework CSS không class vào 1 trang HTML thô,, bạn sẽ cuối cùng thấy 1 trang web hiện đại, định kiểu tốt bởi vì framework không class áp dụng các styles cho tất cả các thẻ HTML tiêu chuẩn của bạn. 

2. KHi nào sử dụng các frameworks không class. 

Một framework CSS không class không hành xử như 1 framework CSS dựa trên class đầy đủ các tính nắng cái mà cung cấp nhiều thành phần được phát triển trước và các cấu trúc bố cục. Một framework không class định nghĩa styles tối thiểu để áp dụng các style phổ biến dựa trên tên thẻ HTML và các thuộc tính. 
When to use classless CSS frameworks
Điều này cho phép những thư viện này hữu ích trong ngữ cảnh bạn cần style nhanh chóng cho HTML thô mà không sử dụng các class hoặc viết nguồn CSS. Đây là vài ngữ cảnh phổ biến bạn có thể sử dụng 1 framework CSS không class: 

- Thêm styles cho 1 trang blog đơn giản cái mà thể hiện 1 HTML thô. 
- Cung cấp 1 cái nhìn hiện đại với các trang cũ, tĩnh, chỉ HTML. 
- Tạo ra 1 trang danh mục đầu tư đơn giản hoặc trang web cá nhân. 
- Thêm styles cho nội dung HTML được kết xuất bằng Markdown 
-  Tạo nguyên mẫu ( thiết kế một mẫu HTML cho cuộc gặp khách hàng) và thiết kế một sản phẩm khả thi tối thiểu(MVP).
3. Classless với class-light với class-based CSS frameworks. 

Sau khi khám phá các Frameworks CSS không class là gì và các trường hợp sử dụng phù hợp, chúng tôi đã thảo luận chúng khác nhau như nào giữa các frameworks truyền thống dựa trên class. Tuy nhiên có 1 kiểu framework CSS được gọi là class-light,nằm giữa frameworks classless và class-based. 


Class-light frameworks giúp bạn style trang bằng cách sử dụng các tính năng từ cả kiểu frameworks dựa trên class và không class. Chúng không chỉ style các trang HTML thô dựa trên các khái niệm HTML ngữ nghĩa mà còn cung cấp các class tối thiểu cho việc  xây dựng bố cục và màu sắc. [Chota](https://github.com/jenil/chota) và [Milligram](https://github.com/milligram/milligram) là các framework class-light phổ biến. 

| Nhân tố so sánh                   | Classless | Class-light | Class-based   |
|-----------------------------------| --------- | ----------- |---------------|
| Cung cấp các lớp định nghĩa trước | Không, nhưng có thể chứa vài tiện ích tùy chọn| Hạn chế, so sánh với class-based| Nhiều|
 | Thời gian học                     | Không mới để học bởi vì không có các lớp bắt buộc nào| Hạn chế do các lớp tối thiểu| thường cần thời gian nhiều hơn |
| Kích thước frame( nhỏ tốt hơn)    |  rất nhẹ | nhẹ| không nhẹ ( so với hai kiểu trước)|
| Components | Không (hoặc rât ít dưa trên các thẻ ngữ nghĩa HTML| Tối thiểu| nhiều , full tính năng|
|Trường hợp sử dụng| khi cần style HTML thô nhanh| Thiết kế web tối giản| các trang phức tạp|

