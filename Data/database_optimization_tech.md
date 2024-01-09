## from [https://dev.to/yogini16/6-database-optimization-techniques-2588](https://dev.to/yogini16/6-database-optimization-techniques-2588)

# 6 Database Optimization Techniques
## 1. Indexing:
- Khái niệm: Liên quan đến việc tạo ra cấu trúc dữ liệu được biết đến như `indexes`, giúp cải thiện hiệu xuất truy vấn bằng cách truy xuất dữ liệu nhanh chóng.
- Mô tả : Các indexes hình thành cấu trúc có tổ chức, hỗ trợ các công cụ CSDL xác định nhanh chóng các dòng mà đáp ứng điều kiện trong mệnh đề `where`.
- Ưu điểm: thúc dẩy tôc độ của các truy vấn SELECT ( đọc ) 
- Hạn chế: Không tốt cho các tác vụ ghi. 
- Lời khuyên: Đạt được sự cân bằng giữa đọc và ghi và quan trọng khi dùng indexes. 


```sql
CREATE INDEX idx_CustomerId ON Customer(CustomerId);
```
## 2. Composite Indexes:

- ĐỊnh nghĩa: là một chỉ mục cho hai hoặc nhiều cột trong một bảng.
- Ưu điểm: Mang lại lơi ích cho các truy vấn cần lọc hoặc sắp xếp dữ liệu dựa trên nhiều điều kiện. Giúp việc lập chỉ chục cho các cột đơn và nâng cao hiệu quả trong truy vấn.
- Lời khuyên: nếu có nhiều field mà bạn thường hay dùng trong các mệnh đề Where,đó là lúc bạn nên dùng Composite Indexes.

```sql 
CREATE INDEX idx_Department_Salary ON Employees (Department, Salary);

```

## 3. Normalization and Denormalization:


 Normalization và denormalization là hai kỹ thuật CSDL trái ngược nhau được sử dụng để tổ chức dữ liệu trong các CSDL quan hệ một cách hiệu quả.

3.1 .Normailizaion

- Khái niệm: là một tiến trình cấu trúc CSDL để giảm thiểu csac dư thừa và phụ thuộc bằng cách tổ chức thành nhiều bảng và cột .
- Mục đích: Giảm sự dư thừa dữ lieuejj và bảo đảm tính trung thực giữa các dữ liệu bằng cách chia nhỏ thành cách bảng nhỏ hơn, các bảng liên quan đến nhau. Normalization cơ bản liên quan đến phân rã bảng lớn thành các bảng nhỏ hơn, mỗi bảng chứa các kiểu dữ liệu cụ thể để tránh dưu thừa 
- Phân loại : Có nhiều dạng cơ bản (!NF, 2NF, 3NF, BCNF, 4NF, 5NF ) cái mà định nghĩa các quy tắc cụ thể cho việc tổ chức dữ liệu để đạt được `normalization`. Các dạng chuẩn hóa càng cao, dữ liệu càng chặt chẽ để loại bỏ các dư thừa và phụ thuộc. 

- Ví dụ :   Giả sử bạn có CSDL cho 1 thư viện . Thay vì có 1 bảng đơn cho tất cả các thông tin liên quan đến sách ( titke, author, publisher, ISBN,), 'normalization' sẽ chia dữ liệu thành nhiều bảng: 
   
   - "Book" chứa các thông tin về sách như ID, title, ..
   - "Author" chứa các thông tin về tác giả. 
   - 'Author-book' bảng liên kết giữa hai bảng. 

 Cách tiệp cận này giảm thiểu các dữ liệu dư thừa bằng cách chia nhỏ các dữ liệu khác nhau vào các bảng tưởng ứng của nó  và sử dụng relationship để nối hai bảng. 

3.2 Denormalizarion 

- KHái niệm: Ngược lịa với normalization, đây là tiến trình có ý định đưa các phần dư thừa vào 1 CSDL để cải thiên hiệu xuất truy vấn bằng cách giảm số lượng `joins` để truy xuất dữ liệu.
Nó liên quan đến việc nối các bảng hoặc bằng cách thêm các dữ liệu `dư thừa` vào lại các bảng chuẩn hóa, giúp tăng tốc độ truy vấn dữ liệu 
- Vi dụ : Vân với CSDL cho thư viện, công nghệ `denormalization` có thể liên quan đến việc đưa các dữ liệu dư thừa vào 1 bảng. Ví dụ nếu thường xuyên cần lấy tên tác giả cùng với chi tiết sách, thay vì join với bảng 'Author', có thể thêm field 'author_name' trong bảng Book. 
- Ưu điểm: tăng tốc độ truy vấn do hạn chế join bảng. 
- Hạn chế: tăng các dư thừa dữ liệu và rắc rối khi update hoặc xóa dữ liệu . 


## 4. Partitioning

- Khái  niệm : Nguyên tắc đòi hỏi các phân mảnh của các bảng dữ liệu thành cac đoạn nhỏ hơn, dễ quản lý hơn. 
- Ưu điểm: cách tiếp cận này tăng dáng kể hiệu suất truy vấn ví nó có thể cho phép các công cụ CSDL thao tác trên các ddoạn đã được giảm, do đó đẩy nhanh thực thi truy vấn. 

```sql

CREATE TABLE SalesData (
    SalesDate DATE,
    ProductID INT,
    Amount DECIMAL(10, 2)
) PARTITION BY RANGE (YEAR(SalesDate)) (
    PARTITION p0 VALUES LESS THAN (2020),
    PARTITION p1 VALUES LESS THAN (2021),
    PARTITION p2 VALUES LESS THAN (2022),
    PARTITION p3 VALUES LESS THAN (2023),
    PARTITION p4 VALUES LESS THAN MAXVALUE
);
```

## 5. Connection Pooling: 

Sử dụng các kết nối có sẵn:Sử dụng các kết nối CSDL đã có để giảm chi phí liên quan đến hình thành một kết nối mới cho mỗi yêu cầu. Công nghệ này hỗ trợ hiệu qủa trong quản lý và giảm các kết nối CSDL. 


##  6. Monitoring and Profiling:

 - Tích hợp các công cụ để quan sát nhất quán hiệu suất của CSDL trong 1 thời gian dài . Theo dấu nhất quán các đơn vị cơ bản như sử dụng CPU, phân bổ bộ nhớ và thời gian thực thi truy vấn để phát hiện kịp thời và giản quyết các nguy cơ tiềm ẩn. 
 - Hồ sơ truy vấn: truy cập và kiểm tra các hiệu suất truy vấn riêng lẻ để xác đinh vấn đề. Sử dụng các công cụ như MySQL Performance Scheme để có cái nhìn toàn diện trong thực thi truy vấn. 




