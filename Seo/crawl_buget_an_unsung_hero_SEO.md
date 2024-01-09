# from [https://www.indiehackers.com/post/crawl-budget-the-unsung-hero-of-seo-optimization-guide-for-2024-aaa117b43e](https://www.indiehackers.com/post/crawl-budget-the-unsung-hero-of-seo-optimization-guide-for-2024-aaa117b43e)


# Crawl Budget là một từ khóa SEO.

 ` Các trang web gần như là một không gian vô tận, vượt quá khả năng của GG để khám phá và lập chỉ mục các URL có sẵn. Kết quả là, có sự giới hạn bao nhiêu thời gian GG bot có thể dùng để thu thập dữ liệu của bất kỳ các trang đơn nào. Số lượng thời gian và tài nguyên mà GG dùng để thu thập 1 trang thường được gọi là ngân sách thu thập của trang. Chú ý không phải tất cả mọi thức được crawl trên trang của bạn sẽ được index; mỗi trang sẽ được tinh toán, hợp nhất và tính toán để xác định xem nó có dược lạp chỉ mục hay không sau khi nó được crawl` (chi tiết [google](https://developers.google.com/search/docs/crawling-indexing/large-site-managing-crawl-budget#:~:text=The%20amount%20of%20time%20and,after%20it%20has%20been%20crawled.))

## Crawl budget được xác định bởi hai nhân tố chính: crawl capacity và crawl demand . 

 1. crawl capacity 
  
  - Mục đích :Để thu thập dữ liệu mà không ảnh hưởng tới Servẻr của bạn, GG bot tính toán 1 giới hạn khả năng crawl 
  - Khái niệm : là số lượng tối đa các kết nối đồng thời song song mà GG bot có thể sử dụng để thu tập dữ liệu một trang cũng nhữ thời gian trì hoãn giữa các lần tìm nạp.
  - Các nhân tố ảnh hưởng: Crawl capacity có thể tăng hoăc giảm phụ thuộc vào : 

     - Tình trạng thu thập dữ liệu: Nếu trang web phản hồi nhanh trong một thời gian, giới hạn sẽ tăng lên, nghĩa là có thể sử dụng nhiều kết nối hơn để thu thập dữ liệu. Nếu trang web chậm lại hoặc phản hồi khi có lỗi máy chủ thì giới hạn sẽ giảm xuống và Googlebot thu thập dữ liệu ít hơn.
     - Giới hạn thu thập của GG:

2. Crawl demand.
  

-    GG dành nhieuf thời gian có cần thiết cho thu thập dữ liệu của 1 trang, dựa trên kích thước, tần xuất update, chất lượng trang, mức độ liên quan so với các trang khác. 
- Các nhân tố xác định nhu cầu thu  thập: 

- Perceived inventory : Không có sự hướng dẫn từ bạn, GG bot sẽ cố gắng thu thập tất cả hoặc gần như toàn bộ các URLs cái mà nó biết về trang của bạn. Nếu nhiều URL bị trùng hoặc bạn không muốn chúng được thu thập vì vài lý do, điều này sẽ tốn nhiều thời gian thu thập của GG . Đây là nhân tố có thể kiểm soát chủ động. 
- Popularity:URLs mà càng phổ biến trên Internet được thu thập thường xuyên hơn để giữ chúng luôn mới trong index của GG. 
- Staleness: hệ thống của GG sẽ thu thập lại các tài liệu một cách thường xuyên đủ để nhận ra các thay đổi.
  Ngoài ra, các sự kiện trên toàn trang web như di chuyển trang web có thể làm tăng nhu cầu thu thập dữ liệu để lập chỉ mục lại nội dung trong các URL mới.


      ```Google xác định lượng tài nguyên thu thập dữ liệu để cung cấp cho mỗi trang web, dựa trên mức độ phổ biến, giá trị người dùng, tính độc đáo và khả năng phân phát. Cách duy nhất để tăng ngân sách thu thập dữ liệu của bạn là tăng khả năng phục vụ cho việc thu thập dữ liệu và (quan trọng hơn) là tăng giá trị của nội dung trên trang web của bạn đối với người tìm kiếm.```




