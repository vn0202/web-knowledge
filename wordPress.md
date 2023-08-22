### from [https://make.wordpress.org/core/2023/08/21/wordpresscs-3-0-0-is-now-available/](https://make.wordpress.org/core/2023/08/21/wordpresscs-3-0-0-is-now-available/)

Chào mừng !
Nhóm phát triển core ( là một tập các phần mềm cầm thiết để chạy Wordpress) WordPress xây dựng WordPress! Theo dõi trang này cho các cập nhật chung, báo cáo trạng thái và các cuộc tranh luận mã. Có rất nhiều cách để đóng góp:   

- Phát hiện ra lõi? Tạo 1 [ticket](https://wordpress.org/support/bb-login.php?redirect_to=https://core.trac.wordpress.org/newticket) trong trình theo dõi lỗi .
- Bạn muốn đóng góp? Hãy bắt đầu nhanh với các thẻ đánh dấu như [lỗi đầu tiên tốt](https://core.trac.wordpress.org/tickets/good-first-bugs) cho những người đóng góp mới hoặc tham  gia [loại bỏ lỗi](https://make.wordpress.org/core/handbook/testing/bug-gardening/). Có nhiều thông tin hơn   trên [trang báo cáo ](https://make.wordpress.org/core/reports/), như các [bản vá](https://core.trac.wordpress.org/tickets/needs-testing) cần kiểm tra và các [trang dự án tính năng](https://make.wordpress.org/core/features/) 
- Câu hỏi khác? Đây là 1 [cuốn sách chi tiết](https://make.wordpress.org/core/handbook/) cho các nhà đóng góp với các hướng đãn đầy đủ.

  


# WordPressCS 3.0.0 hiện đã có sẵn.

Bài này thông báo tính khả dụng ngay lập tức cho bản phát hành  [WordPressCS 3.0.0](https://github.com/WordPress/WordPress-Coding-Standards/releases/tag/3.0.0) được chờ đợi rất lâu.

Đây là 1 bản phát hành quan trọng có những thay đổi có ý nghĩa để cải thiện độ chính xác, hiệu năng, độ ổn định, và khả năng bảo trì của tât cả các **sniffs** ( Một module cho PHP code Sniffer cái mà phân tich mã cho các vấn đề đặc biệt . Nhiều độ cứng kết hợp tạo ra 1 tiêu chuẩn PHPCS . Từ khóa được đặt tên bởi vì nó phát hiện ra "code smells", tương tự như con chó sẽ đánh hơi ra thức ăn ), cũng làm cho WordPressCs càng tốt hơn để xử lý PHP hiện đại 

Hầu hết các quy tắc cái mà được đề xuất trong [Make post from March 2020](https://make.wordpress.org/core/2020/03/20/updating-the-coding-standards-for-modern-php/) được thêm vào [Hướng dẫn tiêu chuẩn mã ](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
Các **quy tắc" được đề xuất cái mà mang lại nhiều cuộc thảo luận hoặc bị phản đối, không được thêm vào. 
Mục đích là xuaasts bản các **bài đăng** cho từng đề xuất này theo thời gian, để thảo luận nhieue hơn về những đề xuất gây trang cãi 

Cho số lượng lớn các *rules* mới, "sniffs" đã được thêm vào WordPress để tập trung cho những rules này. 
Nhiều "Sniffs" có thể được thêm vào bản phát hành WordPress tương lai để bao quát toàn diện các quy tắc mới và các quy tắc được cập nhật.

# Kiến trúc mới 
 WordPressCs trước đây chỉ có 1 phụ thuộc thời gian chạy là [PHP_CodeSniffer](https://github.com/squizlabs/php_codesniffer) và người dùng cuối sẽ cần để đăng ký thủ công WordPress với PHP_codeSniffer ( hoặc sử dung Plugin Composer để thực hiện)

**Plugins** : Một Plugin là 1 phần của phần mềm chứa một nhóm các chức năng có thể thêm vào 1 web WordPress. Chúng có thể mở rộng các chức năng hoặc thêm các tính năng mới cho web Wordpress của bạn. Các plugins WordPress được viết bằng PHP và tích hợp liền mạch với WordPress. Có những Plugins free trên [thư mục Plugin WordPress.org](https://wordpress.org/plugins/) hoặc có thể là plugin dựa trên chi phí từ bên thứ ba. 
kể từ WordPressCS 3.0.0, WordPress sẽ có 4 phụ thuộc thời gian chạy bời và bởi vì điều này, Composer sẽ là các duy nhất để cài đặt WordPressCS.

     Lưu ý: vẫn có thể cài đặt WordPressCS và các phụ thuộc của nó mà không cần sử dung Composer. Nó chỉ không phải là một phương thức cài đặt được hỗ trợ sẽ được cung cấp. 
![make post](./images/Make-post.png)
- [PHPCSUtils](https://github.com/PHPCSStandards/PHPCSUtils) là một tập các hàm tiện ích dể sử dụng PHP_CodeSniffer. 
- [PHPCSExtra](https://github.com/PHPCSStandards/PHPCSExtra) là 1 tập bổ sung của sniffs. 
- [Composer Installer](https://github.com/PHPCSStandards/composer-installer) là một plugin Composer cái mà đảm bảo rằng WordPressCS, PHPCSUtils cũng như PHPExtra sẽ được đăng ký chính xác với PHP_CodeSniffer. 

Giờ đây, các "sniffs" không dành riêng cho WordPress, sẽ được thêm vào PHPCSExtra, trong khi các sniffs dành riêng cho WordPress tiếp tục được bảo trì trong WordPressCS. 
Một vài Sniffs WordPressCS đã tồn tại trước đó, cái mà có thể có ích với cộng động rộng lớn PHP, được xóa và thay thế bởi sniffs tương tự ( và được cải thiện), cái mà đã dược thêm vào PHPCSExtra. 

# Nâng cấp lên WordPressCS 3.0.0
WP 3.0.0 chứa những thay đổi đột phá, cho cả những người sử dụng "ignore annotations", người đang duy trì bộ quy tắc tùy chỉnh,cũng như cho các nhà phát triển Sniff người mà bảo trì 1 tiêu chuẩn PHPCS tùy chỉnh dựa trên WPCS. 

Bên cạnh [nhật ký thay đổi](https://github.com/WordPress/WordPress-Coding-Standards/releases/tag/3.0.0), WPCS 3.0.0 xuất hiện với những hướng dẫn nâng cấp chi tiết cho cả [người dùng cuối/ người bảo trì các tập quy tắc](https://github.com/WordPress/WordPress-Coding-Standards/wiki/Upgrade-Guide-to-WordPressCS-3.0.0-for-ruleset-maintainers) cũng như 1 [hương dẫn nâng cấp riêng cho các nhà phát triển người](https://github.com/WordPress/WordPress-Coding-Standards/wiki/Upgrade-Guide-to-WordPressCS-3.0.0-for-Developers-of-external-standards) mà đang xây dựng tiêu chuẩn mã hóa trên WPCS.  

Vui lòng đọc các tài liệu được cung cấp 1 cách cẩn trọng trước khi nâng cấp. 

Lõi WP sẽ nâng cấp lên WPCS 3.0.0 trong tương lai gần. Theo dõi các Trac ticket [#59161](https://core.trac.wordpress.org/ticket/59161) nếu bạn muốn được cạp nhật thông tin và đảm bảo để chạy *composer update --with-all-dependencies* sau khi bản vá được cam kết để hưởng lợi từ những Sniff mới nhất và tốt nhất. 


