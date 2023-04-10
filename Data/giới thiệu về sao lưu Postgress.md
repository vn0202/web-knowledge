 
Sao lưu trong thế giới CSDL là điều cơ bản. Chúng là các lưới an toàn bảo vệ bạn từ những bit nhỏ nhất của dữ liệu hao hụt. Có nhiều cách để sao lưu dữ liệu của bạn và bài viết này mục đích để giải thích các công cụ cơ bản liên quan đến sao lưu và các tùy chọn cho bạn, từ khi mới bắt đầu cho đến các hệ thống sản xuất phức tạp hơn. 

## pg_dump/pg_restore

pg_dump và pg_dumpall là các công cụ được thiết kế để sinh ra 1 file và sau đó cho phép CSDL được khôi phục lại. Những công cụ này được phân loại sư các sao lưu logic và chúng có thể có kích thước nhỏ hơn nhiều trong sao lưu vật lý. Điều này 1 phần là trong thực tế các chỉ mục không được lưu trữ trong kết suất SQL. Chỉ có lệnh CREATE INDEX được lưu và các chỉ mục phải được dựng lại khi khôi phục  từ bản sao lưu logic.


Một lợi ích của tiếp cận kết suất SQL là đẩu ra có thể được tải lại trong các phiên bản Postgres mới hơn vì vậy kết suất và khôi phục thường rất phổ biến cho nâng cấp và di chuyển phiên bản. Một lợi ích khác là những công cụ này có thể được cấu hình để sao lưu đối tượng CSDL xác định và bỏ qua những thứ khác. Điều này là hữu dụng, ví dụ, nếu chỉ có 1 tập các bản nhất định cần được mang ra môi trường kiểm tra. Hoặc bạn muốn sao lưu một bảng đơn khi bạn muốn làm công việc nguy hiểm.

Postgres kết suất cũng nhất quán nội bộ, có nghĩa là kết suất đại diện 1 ảnh chụp nhanh của CSDL ở thời điểm tiến trình bắt đầu. Kết suất sẽ thường không khóa các thao tác khác, nhưng họ có thể chạy trong thời gian dài ( ví dụ, vài giờ hoặc vài ngày, phụ thuộc vào phần cứng và kích thước CSDL.). Bởi vì phương thức Postgres sử dụng để khai triển đồng thời, được biết như là Kiểm soát đồng thời đa phiên bản, chạy các sao lưu trong thời gian dài có thể gây cho Postgres  giảm hiệu năng cho đến khi kết suất hoàn thành. 

Để kết suất 1 bảng CSDL đơn, bạn có thể chạy như sau: 

```postgresql
pg_dump -t my_table > table.sql
```
Để khôi phục : 

```postgresql
psql -f table.sql
```


## pg_dump như 1 kiểm tra hư hỏng. 

pg_dump  quét tuần tụ qua toàn bộ dữ liệu khi nó tạo tệp,. Việc đọc toàn bộ CSDL là kiểm tra tham lam thô sơ cho toàn bộ  các bảng dữ liệu, nhưng không cho các chỉ mục. Nếu dữ liệu bị hỏng, pg_dump sẽ ném ra 1 ngoại lệ. Crunchy thường gợi ý sử dụng module amcheck dể làm 1 kiểm tra hư hỏng, đặc biệt trong một số loại nâng cấp hay di chuyển nơi mà có thể liên quan đến đối chiếu. 


# Máy chủ và sao lưu hệ thống file 


Nếu  bạn đang đến  từ thế giới admin Linux, bạn đã từng sao lưu dữ liệu tùy chọn cho toàn bộ máy mà CSDL của bạn chạy trên đó, bằng cách sử dụng rssync hay các công cụ khác. Postgres có thể không sao luu an toàn bằng cách dung công cụ hướng tệp trong khi nó đang chạy, và không là 1 cách đơn giản để ngừng ghi. Để đưa CSDL vào trong 1 trạng thái mà bạn có thể rsync dữ liệu, bạn hoặc phải tắt nó hoặc đi qua tất cả các công việc của thiết lập lưu trữ thay đổi. Cũng có một vài tùy chọn cho các lớp lưu trữ cái mà hỗ trợ ảnh chụp nhanh cho toàn bộ thư mục dữ liệu - nhưng đọc bản in đẹp trên các lớp này


# Sao lưu vật lý và lưu trữ WAL 

Ngoài  kết suất tệp cơ bản, các phương thức tinh vi hơn của Postgres sao lưu toàn bộ phụ thuộc vào việc lưu các tệp Nhật ký ghi thẳng của CSDL. WAL theo dấu các thay đổi tới toàn bộ khối CSDL, lưu chúng trong các đoạn cái mà mặc định là 16MB. Tập hợp liên tục của các tệp WAL của máy chủ được gọi   luồng WAL của nó. Bạn phải bắt đầu lưu trữ các tệp của luồng WAL trước khi bạn có thể sao chép an toàn CSDL, được theo sau bởi 1 thủ tục cái mà sản xuất 1 "base backup", ví dụ pg_basebackup. Khía cạnh gia tăng của WAL làm cho có thể 1 chuỗi của các đặc tính khôi phục được gộp lại dưới biểu ngữ công cụ **khôi phục theo thời gian**.

# Tạo 1 basebackup với pg_basebackup

Bạn có thể dùng như sau : 

```postgresql
$ sudo -u postgres pg_basebackup -h localhost -p 5432 -U postgres \
	-D /var/lib/pgsql/15/backups -Ft -z -Xs -P -c fast
```

Một vài nhận xét từ lệnh trên: 

- Lệnh này nên chạy với tư cách là người dùng postgres. 
- Đối số -D chỉ rõ nơi nên lưu bản sao lưu 
- Đối số -Ft chỉ định dạng tar nên được ấp dụng 
- -Xs chỉ định rằng WAL tệp sẽ được truyền tới bản sao lưu. Điều này là quan trọng bởi vì các hoạt dộng WAL đáng kể có thể xảy ra trong khi sao lưu được lấy và bạn có thể không muốn duy trì những file này trong khoảng thời gian này. Điều này là ứng xử mặc định, nhưng đáng để chỉ ra. 
- -z chỉ định rằng tệp tar sẽ đưuọc nén 
- -P chỉ định thông tin tiến trình sẽ được ghi tới stdout trong suốt tiến trình.
-  Tham số -c fast cho biết rằng một điểm kiểm tra được thực hiện ngay lập tức. Nếu tham số này không được chỉ định, thì quá trình sao lưu sẽ không bắt đầu cho đến khi Postgres tự đưa ra một điểm kiểm tra và quá trình này có thể mất một lượng thời gian đáng kể.

Khi lệnh được nhập, quá trình sao lưu sẽ bắt đầu ngay lập tức. Tùy thuộc vào kích thước của cụm, có thể mất một thời gian để hoàn thành. Tuy nhiên, nó sẽ không làm gián đoạn bất kỳ kết nối nào khác đến cơ sở dữ liệu.


#  các bước để khôi phục từ 1 bản sao lưu đươc lấy từ pg_basebackup
 CHúng được đơn giản hóa từ tài liệu chính thức. Nếu bạn đang sử dụng các đặc tính như tablespaces bạn sẽ cần để điều chỉnh những bước này cho môi trường của bạn 

1. Đảm bảo cơ sở dữ liệu đã tắt 

```postgresql
$ sudo systemctl stop postgresql-15.service
$ sudo systemctl status postgresql-15.service
```
2. xóa nội dung của Postgres để mô phỏng thảm họa 

```postgresql
$ sudo rm -rf /var/lib/pgsql/15/data/*
```

3. Giải nén base.tar.gz vào thư mục dữ liệu.

```postgresql
$ sudo -u postgres ls -l /var/lib/pgsql/15/backups
total 29016
-rw-------. 1 postgres postgres   182000 Nov 23 21:09 backup_manifest
-rw-------. 1 postgres postgres 29503703 Nov 23 21:09 base.tar.gz
-rw-------. 1 postgres postgres	17730 Nov 23 21:09 pg_wal.tar.gz


$ sudo -u postgres tar -xvf /var/lib/pgsql/15/backups/base.tar.gz \
     -C /var/lib/pgsql/15/data
```
4. Giải nén pg_wal.tả.gz trong 1 thư mục mới bên ngoài thư mục dữ liệu, Trong trường hợp của chúng ta, tôi tạo ra 1 thư mục được gọi là pg_wal bên trong thư mục sao lưu của chúng ta. 

```postgresql

$ sudo -u postgres ls -l /var/lib/pgsql/15/backups
total 29016
-rw-------. 1 postgres postgres   182000 Nov 23 21:09 backup_manifest
-rw-------. 1 postgres postgres 29503703 Nov 23 21:09 base.tar.gz
-rw-------. 1 postgres postgres	17730 Nov 23 21:09 pg_wal.tar.gz

$ sudo -u postgres mkdir -p /var/lib/pgsql/15/backups/pg_wal

$ sudo -u postgres tar -xvf /var/lib/pgsql/15/backups/pg_wal.tar.gz \
      -C /var/lib/pgsql/15/backups/pg_wal/
```
5. Tạo tệp recovery.signal.

```postgresql
$ sudo -u postgres touch /var/lib/pgsql/15/data/recovery.signal

```
6. Đặt lệnh restore_command trong postgresql.conf để sao chép các tệp WAL được truyền phát trong quá trình sao lưu.

```postgresql
$ echo "restore_command = 'cp /var/lib/pgsql/15/backups/pg_wal/%f %p'" | \
      sudo tee -a /var/lib/pgsql/15/data/postgresql.conf
```
7. Bắt đầu CSDL 

```postgresql
$ sudo systemctl start postgresql-15.service sudo systemctl status
postgresql-15.service
```

8. Bây giờ CSDL của bạn đã hoạt động và hoạt động dựa trên các thông tin bao gồm trong baseback trước.


#  Tư động sao lưu vật lý

