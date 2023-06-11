## from [https://www.cockroachlabs.com/blog/use-cases-trigram-indexes/](https://www.cockroachlabs.com/blog/use-cases-trigram-indexes/)


# Các trường hợp sử dụng `trigram indexes` (khi không sử dụng tìm kiếm toàn văn)

Chúng ta có kế hoạch tham quan tới Orange County, Calfiornia, nơi mà tôi đã lớn lên, vào những ngày nghỉ sắp tới. Nhà hàng yêu thích Mexican của tôi ở đó, in Placentia: Q-Tortas! Tôi thường xuyên thèm các món burrito, vì vậy chúng tôi có một chuyến đi tới đó là bắt buộc (Tôi mong muốn bài viết này là về search )

Có một nguyên nhân tôi đề cập đến nhà hàng là bởi vì tôi đã đánh vần sai tên. Bây giờ, nhìn lại, tôi thấy ynos thực sự được gọi là Q Tortas, không có dấu gạch ngang. Nếu tôi xây dựng 1 ứng dụng cái mà không kết hợp với khả năng tìm kiếm các nhà hàng, tôi sẽ có 1 nhân tố thực tế phũ phàng rằng người dùng sẽ không đánh vần mọi thứ  một cách chính xác. THực tế này, cùng với sự có sẵn của đánh chỉ mục dựa trên `trigram` trong CockroachDB 22.2 ( và tôi thèm bánh burrito), khiến tôi suy ngẫm các trường hợp sử dụng tìm kiếm văn bản cái mà có lợi từ `indexes trigram`. Đây là các trường hợp sử dụng ngay lập tức xuất hiện trong tâm tri:

- Tìm kiếm những thứ cái mà có thể hoặc không có dấu gạch ngang, như trong ví dụ ở đây 
- Chuyển ngữ tên của người, địa điểm, ....
- Người dùng mắc lỗi chính tả, hoặc người dùng của ứng dụng hoặc thậm chí những người nhập dữ liệu mà ứng dụng dựa vào.
- Đánh vần nhiều cách, ví du như trong tên: "Megan","Meaghan"...

*Tiết lộ đầy đủ: Tôi có có một chút cuồng tín ở tìm kiếm văn bản. Trong quá khứ, tôi đã viết về việc tìm kiếm `full text search` với CockroachDB và Elastic. Trước điều đó, tôi đã viết về lập chỉ mục text trong CockroachDB 20.2 bằng cách sử dụng `GIN indexes`. Sự hỗ trợ cho `trigram indexes` là 1 cải thiện lớn trên những cái mà chúng ta đã có sẵn thời gian thử nghiệm chúng.

Trước khi bạn đào sâu vào hướng dẫn  `trigram indexes` dưới dây bạn có thể muốn xem video hướng dẫn nhanh này được xây dựng bởi đồng nghiệp của tôi Dikshant Adhikari:

link to video [here](https://youtu.be/YchwH7d8UPE)

# chờ đã, vậy `Trigra` là cái gì?

![diargram demo of trigram indexed](./images/trigram_indexes.avif)
 Tưởng tượng có 1 cửa sổ trượt, rộng 3 ký tự, cái mà được nâng cấp thông qua 1 từ, mỗi lần 1 ký tự, tạo ra chuỗi 3 ký tự ở mỗi vị trí,- đây là 1 `trigram`. Nếu tôi sử dụng một chức năng dụng sẵn mới để trích xuất các `trigrams` từ các giá trị chuỗi này, đây là cái mà tôi nhận được: 

```cockroach
defaultdb=> SELECT show_trgm('Q-Tortas');
                    show_trgm                    
-------------------------------------------------
 {"  q","  t"," q "," to","as ",ort,rta,tas,tor}
(1 row
)

```

Một vài quan sát: (1) chuỗi được quy ước thành không viết hoa, (2) các dấu gạch ngang được thay thế bằng dấu cách, với kết quả là chuỗi được xem như hai từ riêng biệt. Điều này mang lại lợi ích là làm cho quy trình khớp là không phân biệt hoa thường và không ảnh hưởng tới dấu câu, vì vậy trong trường hợp của tôi, việc đánh vần sai tên này bằng cách thêm dấu gạch ngang bị bỏ quên và tôi vẫn tìm được kết quả khớp.

Giá trị của đề cập này chính là điều đó, cho chuỗi dài N ký tự, kích thước của kết quả trigrams là 3*(* N * + 1), vì vậy điều này sẽ không là 1 ý tưởng phù hợp như 1 cách tiếp cận để đánh chỉ mục `full-text`. Tương tự, cho các texts lớn, tiếp cận này không thực hiện tốt ở tất cả các điểm, vì sự chính xác của kết quả tìm kiếm tiệm cận về không khi độ dài của text tăng. Cách tiếp cận `trigram` đã làm tốt, thực thi rất tốt cho các trường tìm kiếm văn bản ngắn như cột name trong bảng được sử dụng trong ví dụ bên dưới.


# Các bước sử dụng indexes trigram để `fuzzy match`

Ngoài ra trình sinh trigram đó, có vẻ như chúng ta cần: 

- Một cách để đánh chỉ mục các trigrams đươc sinh ra này, vì vậy chúng ta có thể tìm kiểm  hiệu quả các dòng phù hợp.
- Nột tập các toán tử cái mà mở ra các khả năng bên trong của truy vấn SQL;
- Một xếp hạng ý nghĩa, vì vậy các kết quả phù hợp nhất sẽ xuất hiện đầu tiên trong danh sách kết quả. 

# Trigram indexes tutorial

 Vì cảm hứng cho điều này là nhà hàng Mexico ở Orange County, điểm bắt đầu là 1 tập dữ liệu nhỏ chứa chi tiết các nhà hàng địa phương ( [ơây là bản sao chép dữ liệu](https://storage.googleapis.com/crl-goddard-gis/oc_restaurants.psv)). Trước khi đi xa hơn, Tôi nên chỉ ra rằng, tất cả điều này đang được thực hiện bằng cách sử dụng CockroachDB trên Macbook của tui. CockroachDB có thể được tìm thấy ở [đây(https://www.cockroachlabs.com/docs/releases/index.html)] và command để tôi bắt đầu nó. 

```cockroach
$ cockroach start-single-node --insecure --listen-addr=localhost:26257 --http-addr=localhost:8080 --background

```

Dựa trên cấu trúc dữ liệu, đây là bảng được đánh giá: 
```cockroach
CREATE TABLE restaurant
(
id UUID NOT NULL PRIMARY KEY DEFAULT gen_random_uuid()
, name TEXT
, address TEXT
, phone TEXT
, url TEXT
, stars VARCHAR(5)
, description TEXT
);
```

Sau đó chúng ta cần index trigram trên tên của cột vì đó là đặc tính trung tâm của ví dụ này: 

```cockroach
CREATE INDEX restaurant_trgm_idx ON restaurant USING GIST (name gist_trgm_ops);

```
Bước tiếp theo của chúng ta là tải tệp dữ liệu được phân tách bằng đường ốn trong bảng. Theo thói quen, Tôi có su hướng sử dụng psql CLI nhiều cho công việc của tôi, vì vậy đó là cái được thể hiên dưới đây: 
```postgresql
$ psql "postgres://root@localhost:26257/defaultdb" -c "COPY restaurant (name, address, phone, url, stars, description)
> FROM STDIN
> WITH DELIMITER '|' NULL E'';" < ./oc_restaurants.psv
Timing is on.
COPY 70
Time: 83.443 ms
Let’s have a quick look at the data:

```
Hãy có cái nhìn nhanh : 
```cockroach
defaultdb=> SELECT name, address, phone, url, stars FROM restaurant ORDER BY RANDOM() LIMIT 3;
name       |                address                |    phone     |               url               | stars
------------------+---------------------------------------+--------------+---------------------------------+-------
Vaca             | 695 Town Center Drive, Costa Mesa     | 714-463-6060 | http://vacarestaurant.com/      | ★★★
Paradise Dynasty | 3333 Bristol St., Costa Mesa          | 714-617-4630 | http://paradisegp.com/usa       | ★★
Haven            | 190 S. Glassell St., Orange           | 714-221-0680 | http://havencraftkitchen.com/   | ★★
(3 rows)

Time: 3.653 ms
```

Vì chúng ta có  70 dòng của dữ liệu thực, chúng ta cần sinh ra 100k dòng nữa vì vậy chúng ta sẽ có thể chúng minh hiệu quả của trigram index: 
```cockroach
defaultdb=> INSERT INTO restaurant (name, address, phone, url, stars, description)
SELECT SUBSTR(MD5(RANDOM()::TEXT), 0, 12), SUBSTR(MD5(RANDOM()::TEXT), 0, 32),
SUBSTR(MD5(RANDOM()::TEXT), 0, 12), SUBSTR(MD5(RANDOM()::TEXT), 0, 27),
SUBSTR(MD5(RANDOM()::TEXT), 0, 3), SUBSTR(MD5(RANDOM()::TEXT), 0, 32)
FROM GENERATE_SERIES(1, 1.0E+05);
INSERT 0 100000
Time: 41101.097 ms (00:41.101)
```

Vì vậy đây là kết quả của 1 truy vấn được trả về trong 1 thứ tự tương tự, chúng ta có thể sử dụng chức năng được đánh giá dựng sẵn: 
```cockroach

> defaultdb=> SELECT similarity('Q Torta', 'Q-Tortas');
 similarity 
------------
        0.7
(1 row)
```
Dựa trên hai sự khác nhau trong những chuỗi trên, dấu cách với dấu gạch ngang và việc thiếu 's' ở cuối, chúng ta có điểm của 0.7. Việc tính toán tương tự được hoàn thành như này: Chúng ta đếm số lượng trigrams được chia sẻ trong chuỗi và chia bằng số các trigrams không được chia sẻ trong chuỗi. Nếu tôi cố thử điều này trong Sql, nó không thú vị, nhưng tôi cso thể lấy được các giá trị tương tự: 
```cockroach
defaultdb=> WITH b AS
(
WITH a AS (
SELECT UNNEST(SHOW_TRGM('Q Torta')) tg UNION ALL SELECT UNNEST(SHOW_TRGM('Q-Tortas')) tg
)
SELECT tg, COUNT(*) n
FROM a
GROUP BY tg
)
SELECT REGEXP_REPLACE(
((SELECT COUNT(*) FROM b WHERE n = 2)/((SELECT SUM(n) FROM b) - (SELECT COUNT(*) FROM b WHERE n = 2)))::TEXT,
E'0+$', E''
) similarity;
similarity
------------
0.7
(1 row)
```
OK. Bây giờ chúng ta có 1 bảng với dữ liệu, trigram index, và 1 cách tiếp cận dể đánh điểm các kết quả, hãy có 1 truy vấn 
```cockroach
defaultdb=> \set name '''Q Torta'''
defaultdb=> SELECT name, address, phone, url, stars, SIMILARITY(:name, name)::NUMERIC(4, 3) sim
FROM restaurant
WHERE :name % name
ORDER BY sim DESC
LIMIT 5;
name   |            address            |    phone     |                           url                           | stars |  sim  
----------+-------------------------------+--------------+---------------------------------------------------------+-------+-------
Q-Tortas | 220 S Bradford Ave, Placentia | 714-993-3270 | https://www.facebook.com/profile.php?id=100070084074668 | ★★★   | 0.700
(1 row)
Time: 3.028 ms

```
Chúng ta có 1 kết quả, với giá trị mong chờ tương tự của 0.7, và nó tốn 3ms. Chú ý rằng việc sử dụng toán tự `%` ở đây. Đó là toán tử `fuzzy search` tiện dụng dược tăng tốc bởi trigram indexes. Hay có 1 cái nhìn kế hoạch truy vấn để xem điều gì đã được thực hiện: 
```cockroach
defaultdb=> EXPLAIN SELECT name, address, phone, url, stars, SIMILARITY(:name, name)::NUMERIC(4, 3) sim
FROM restaurant
WHERE :name % name
ORDER BY sim DESC
LIMIT 5;
info
-----------------------------------------------------------------------------------------------
distribution: local
vectorized: true

• top-k
│ estimated row count: 5
│ order: -sim
│ k: 5
│
└── • render
│
└── • filter
│ estimated row count: 3,723
│ filter: 'Q Torta' % name
│
└── • index join
│ estimated row count: 0
│ table: restaurant@restaurant_pkey
│
└── • inverted filter
│ estimated row count: 0
│ inverted column: name_inverted_key
│ num spans: 8
│
└── • scan
estimated row count: 0 (<0.01% of the table; stats collected 1 day ago)
table: restaurant@restaurant_trgm_idx
spans: 8 spans
(27 rows)
```
  Đã đử chắc chắn, truy vấn chạy hiệu quả bằng cách quét qua chỉ các trigram index. Đó là 1 ý tưởng. Hãy sử dụng 1 tính năng mới của CockroachDB cho phép chúng ta bật tắt `visibility` của 1 index, và sau đó chạy lại chuỗi truy vấn và kế hoạch giải thich của nó: 
```cockroach
defaultdb=> ALTER INDEX restaurant_trgm_idx NOT VISIBLE;
ALTER INDEX
Time: 476.591 ms

defaultdb=> SELECT name, address, phone, url, stars, SIMILARITY(:name, name)::NUMERIC(4, 3) sim
FROM restaurant
WHERE :name % name
ORDER BY sim DESC
LIMIT 5;
name   |            address            |    phone     |                           url                           | stars |  sim  
----------+-------------------------------+--------------+---------------------------------------------------------+-------+-------
Q-Tortas | 220 S Bradford Ave, Placentia | 714-993-3270 | https://www.facebook.com/profile.php?id=100070084074668 | ★★★   | 0.700
(1 row)

Time: 561.168 ms
    
    defaultdb=> explain SELECT name, address, phone, url, stars, SIMILARITY(:name, name)::NUMERIC(4, 3) sim
                        FROM restaurant
                        WHERE :name % name
                        ORDER BY sim DESC
                        LIMIT 5;
info
-----------------------------------------------------------------------------------------------
distribution: local
vectorized: true

• top-k
│ estimated row count: 5
│ order: -sim
│ k: 5
│
└── • render
│
└── • filter
│ estimated row count: 33,357
│ filter: 'Q Torta' % name
│
└── • scan
estimated row count: 100,070 (100% of the table; stats collected 6 minutes ago)
table: restaurant@restaurant_pkey
spans: FULL SCAN
(18 rows)
```


Thời điểm này truy vấn đã yêu cầu quết bảng đầy đủ, kết quả trong thời gian chạy là 561 ms, vì vậy, cho tệp dữ liệu của 107000 dòng, tốc độ tăng, do trigram index là khoảng 180x. Tốt hơn là không quên khôi phục lại khả năng hiên thị của chỉ mục: 
```cockroach
defaultdb=> ALTER INDEX restaurant_trgm_idx VISIBLE;
ALTER INDEX
Time: 439.067 ms
```

# Một số suy nghĩ về trigram cuối cùng
như thường lệ với CockroadDb, mỗi một bản phát hành kết hợp  với các tính năng mới được yêu cầu bởi các thành viên của cộng đồng phát triển nhanh chóng của cac người dùng. Khả năng của trigram là 1 bước khác trong việc làm cho CockroachDb là 1 đích dễ dàng hơn cho người dùng Postgres và tôi mong chờ nó sẽ được chào đón bởi các lập trình viên ứng dụng trong nhiều trường hợp sử dụng, từ lễ tân khách sạn đến danh mục sản phẩm. Các đặc tính khác được thể hiện ở đây, khả năng hiển thi chỉ mục, nó rất tiện dụng khi thực hiện điều chỉnh hiệu năng.Vui lòng cung cấp những tính năng mới khi bạn có cơ hội. Nếu bạn muốn học nhiều hơn về khả năng mới này trong CockroachDB , hãy xem trang khơi chạy 22.2

Cuối cùng, cảm ơn bạn đã dành thời gian để theo dõi!



