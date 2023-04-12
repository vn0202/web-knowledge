 
Sao lưu trong thế giới CSDL là điều thiết yếu. Chúng là các lưới an toàn bảo vệ bạn từ những bit nhỏ nhất của dữ liệu hao hụt. Có nhiều cách để sao lưu dữ liệu của bạn và bài viết này mục đích để giải thích các công cụ cơ bản liên quan đến sao lưu và các tùy chọn cho bạn, từ khi mới bắt đầu cho đến các hệ thống sản xuất phức tạp hơn. 

## pg_dump/pg_restore

pg_dump và pg_dumpall là các công cụ được thiết kế để sinh ra 1 file và sau đó cho phép CSDL được khôi phục lại. Những công cụ này được phân loại là các sao lưu logic và chúng có thể có kích thước nhỏ hơn nhiều trong sao lưu vật lý. Điều này 1 phần là trong thực tế các chỉ mục không được lưu trữ trong kết suất SQL. Chỉ có lệnh CREATE INDEX được lưu và các chỉ mục phải được dựng lại khi khôi phục  từ bản sao lưu logic.


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

 Xây dựng dựa trên pg_basebackup, bạn có thể viết 1 chuỗi các kịch bản để sử dụng sao lưu này, thêm các phân đoạn WAL vào nó, và quản lý 1 ngữ cảnh sao lưu vật lý hòan chỉnh. Có vài  công cụ bao gồm WAL-E, WAL-G  và pgBackRest cái mà sẽ làm tất cả mọi thứ cho bạn. WAL-G là thế hệ tiếp theo của WAL-E và hoạt động cho vàì CSDL khác bao gồm MySQL và Microsoft SQL server. WAL-G cũng được sử dụng rộng rãi ở mức độ doanh nghiệp với môi trường Postgres rộng lớn, bao gồm Heroku. Khi lần đầu xây dựng Crunchy Bridge, chúng tôi có 1 quyết định giữa WAL-G và pgBackRest vì chúng tôi thuê 1 người bảo trì bao gồm cả và mỗi cái có đặc quyền của nó. Cuối cùng, chúng tôi đã chọn pgBackRest

# pgBackRest

pgBackRest là tốt nhất trong lớp công cụ sao lưu ở đây. Có một số lượng rất lớn csac môi trường Postgres phụ thuộc vào pgBackRest, bao gồm Crunchy Bridge của chúng tôi, Crunchy cho Kubernetes, và Crunchy Postgres cũng tốt như vô số các dự án khác trong hệ sinh thái Postgres.

pgBackRest có thể thực hiện 3 kiểu sao lưu: 

- sao lưu đầy đủ - những bảo sao chép toàn bộ nội dung của cụm CSDL tới bản sao lưu
-  Sao lưu sự khác biệt - Chỉ sao lưu các tệp cụm CSDL cái mà có thay đổi kể từ lần cuối cùng sao lưu đầy đủ. 
- Sao lưu gia tăng - cái mà chỉ sao chép các tệp cụm CSDL cái mà có thay đổi từ lần cuối cùng đầy đủ, khác hoặc gia tăng. 


pgBackRest có một vài đặc điểm đặc biệt sau: 

- Cho phép bạn quay trở lại 1 điểm thời gian - PITR ( Khôi phục 1 thời điểm )
- Việc tạo 1 Khôi phục Delta cái mà sẽ sử dụng các tệp CSDL có sẵn và đã cập nhật dựa trên các phân đoạn WAL. Điều này làm cho khôi phục tiềm năng nhanh hơn, đặc biệt nếu bạncó 1 CSDL lớn và bạn không muốn khôi phục lại toàn bộ 

- Việc để cho bạn có nhiều kho sao lưu - một cục bộ hoặc 1 từ xa cho dự phòng 

Liên quan đến lưu trữ ,người dùng có thể thiết lập cac tham số archive_command để sử dụng pgBackRest để copy các tệp WALs tới 1 lưu trữ bên ngoài. Những files này có thể được duy trì vĩnh viễn hoặc có thời hạn tùy theo chính sách giữ lại dữ liệu của tổ chức của bạn. 

Đẻ bắt đầu pgBackRest sau khi cài đặt, bạn sẽ chạy những thứ như này: 

```postgresql
$ sudo -u postgres pgbackrest --stanza=demo --log-level-console=info stanza-create

```

Để tạo 1 khôi phục Delta: 
```postgresql

$ sudo systemctl stop postgresql-15.service
$ sudo -u postgres pgbackrest \
--stanza=db --delta \
--type=time "--target=2022-09-01 00:00:05.010329+00" \
--target-action=promote restore

```

Khi mà hoàn thành khôi phục, bạn bắt đầu lại CSDL và xác thực rằng bảng users đã trở lại 

```postgresql
$ sudo systemctl start postgresql-15.service
$ sudo -u postgres psql -c "select * from users limit 1"
```

# Thời gian sao lưu 

pgBackRest có các cài đặt và cấu hình rất phong phú để thiết lập 1 chiến lượcc dành riêng cho những thứ bạn cần. Chiến lược sao lưu của bạn sẽ phụ thuộc vào 1 vài nhân tố bao gồm đối tượng điểm khôi phục, kho có sẵn, và các nhân tố khác. Giải pháp đúng sẽ thay đổi dựa trên các yêu cầu này. Tìm 1 chiến lược đúng đắn cho trường hợp sử dụng của bạn là 1 vấn đề tạo ra sự cân bằng giữa thời gian hoàn nguyên, kho lưu trữ sử dụng, chi phí IO trên CSDL nguồn và các nhân tố khác.

Gợi ý thông thường của chúng tôi là kết hợp sao luư và khả năng lưu trữ WAL của pgBackRest. Chúng tôi thường gợi ý khách hàng hãy tạo ra các bản sao lưu cơ sở hàng tuần bổ sung lưu trữ liên tục các tệp WAl của họ, và cân nhắc xem các dạng sao lưu gia tăng khác -- có thể thậm chí là pg_dump -- có ý nghĩa cho các yêu cầu của bạn. 



# Kết luận 

![conclusion](../images/pg_dump_pgBackRest.avif)


Chọn 1 công cụ sao lưu cho nhu cầu sử dụng của bạn sẽ là 1 lựa chọn cá nhân dựa trên các nhu cầu của bạn, sức chịu đựng thời gian khôi phục và kho có sẵn. Nhìn chung, nó tốt nhất nghĩ tới `pg_dump` cho các nhiệm vụ CSDL cụ thể. `pg_basebackup` có thể là 1 tùy chọn nếu bạn ổn với sao lưu vật lý đơn lẻ trên 1 cơ sở thời gian cụ thể. Nếu bạn có 1 hệ thống sản xuất quy mô lớn và cần tạo ra 1 ngữ cảnh khôi phục thảm họa, nó tốt nhất là triển khai pgBackRest hoặc  1 công cụ tinh vi hơn bằng cách sử dụng các đoạn WAL trên các bản sao cơ sở. Tất nhiên, có các tùy chọn quản lý đầy đủ ở đó như Crunchy Bridge cái mà sẽ xử lý tất cả cho  bạn. 

Đồng tác giả với Elizabeth Christensen 
