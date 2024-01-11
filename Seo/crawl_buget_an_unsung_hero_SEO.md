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


      ```Google xác định lượng tài nguyên thu thập dữ liệu để cung cấp cho mỗi trang web,dựa trên mức độ phổ biến,
      giá trị người dùng, tính độc đáo và khả năng phân phát. Cách duy nhất để tăng ngân sách thu thập dữ liệu của bạn
      là tăng khả năng phục vụ cho việc thu thập dữ liệu và (quan trọng hơn) là tăng giá trị của nội dung trên trang web của
       bạn đối với người tìm kiếm.```

### Tại sao Craw Budget ảnh hưởng tơi SEO 

 Crawl budget ảnh hưởng tới SEO vì nó ảnh hưởng tới cách mà công cụ tìm kiếm và lập chỉ mục trang của bạn. 

 Nếu GG không lập chỉ mục một trang, noó sẽ không xuất hiện trong kết quả tìm kiếm. 
 Nhiều trang sẽ không được lập chỉ mục nếu web của bạn có nhiều trang hơn ngân sách của bạn. 



### Những trang nào cần chú ý tới crawl budget 

- Hầu hết các site không cần lo lắng về ngân sách crawl, vì GG hiệu quả trong việc tìm và lập chỉ mục. 
- Tuy nhiên nó quan trọng trong các trường hợp sau : 

  - Trang lớn: nếu trang của bạn có hơn 10K pages, GG có lẽ chỉ tìm thấy vài trong số chúng. 
  - Các trang mới: nếu bạn thêm 1 vùng mới với hàng trăm trang, đảm bảo ngân sách crawl của baạn đủ. 
  - Redirects: Nhiều redirect và các chuỗi redirect có thể tốn nhiều ngân sách của bạn. 

### Crawl Budget ảnh hưởng tới các nhân tố SEO như nào: 

- HTTPs Migration: Khi một trang được migrates, GG sẽ tăng nhu cầu crawl để cập nhật các chỉ mục của nó với csac URL mới nhanh chóng. 
- URL parameters: Quá nhiều tham số URL có thể tạo ra các nội dung trùng lặp, tiêu tốn ngân sách crawl của bạn và giảm cơ hội lập chỉ mục các trang quan trọng khác. 
- XML sitemáp: Một sitemap có cấu trúc tôt, cập nhập giúp GG tìm các trang mới nhanh hơn , tăng ngân sách crawl.
- Các nội dung trùng lặp: Sites với nhiều nội dung trùng lặp có thể gây ra ngân sách crawl thấp vì GG cho rằng các trang của bạn ít quan trọng. 
- Mobile-first indexing: Đây là cách mà GG thu thập, Lập chỉ mục và xếp hạng trang của bạn dựa trên nôi dụng user-agent. Nó khong ảnh hưởng trực tiêp đến ranking nhưng có thể ảnh hưởng tơi số lượng trang của bạn được crawl và lập chỉ mục. 
- robot.txt: Các Url không được phép trong file Robot.txt không ảnh hưởng tới ngân sách của bạn. Nhưng sử dụng robot.txt có thể giúp hướng dẫn GG bot các trang nào b muốn được lập chỉ mục. 
- Thời gian phản hồi máy chủ: Thời gian phản hồi càng nhanh tơi các request crawl của GG bot có thể dẫn tới càng nhiều trang của bạn được thu thập. 
- Cấu trúc trang: Một trang có cấu trúc tốt giúp GG bot tìm và lập chỉ mục hiệu quả hơn. 
- Tốc độ trang: tốc độ tải trang càng nhanh GG bot thu thập càng nhiều các URL của bạn.


# JavaScript Frameworks and SEO

Các frameworks như React, Angular, Vue.js giúp xây dựng các trang web phưc tạp .Chúng cải thiện trải nghiệm người dùng và tạo ra các trang giúp tương tác động. 
 Những frame này cũng cải thiện hiệu xuất của trang web và tối ưu hóa kết xuất 

Việc sử dụng Server-side rendering (SSR) hoặc prerendering, các dev có thể đảm bảo các bot của công cụ tìm kiếm co thể dễ dàng truy cập và lập chỉ mục nội dung. 


Optimizing JavaScript for SEO đảm bảo các công cụ tìm kiếm có thể thu thập, kết xuất và lập chỉ mục các nội dungdo Javascript sinh ra. Đây là đặc biệt quan trọng cho các trang và caác SPAs được build bằng các frame Javascript. 

##  Các mẹo cho SEO Javascript: 

- Sử dụng title duy nhất và các đoạn duy nhất trong các trang. 
- Viết mã thân thiện cho công cụ tìm kiếm 
- Sử dụng cac Mã trạng thái HTTP 
- Ngăn các lỗi 404 

## Cách khác để lập chỉ mục các trang Javascript: 

- Có hai cách chinh để thu thập dữ liệu từ các trang: truyền thống và cách `javascript-enable`

 Cách truyền thống phân tích cấu trúc HTML của trang để có thể có các thông tin cần. 
Tuy nhiên, nó có thể khó khăn cho các trang nặng JS. 

 Javascript-enable giải quyết vấn đề này. Chúng hoạt động như con người bằng cách render các phần tử Js cho phpes chúng truy câp nội dung được tải dynamically. 


## Dynamic rendering 
 Là một giải pháp cung cấp nhiều phiên bản khác nhau của trang tới người dùng và CC tìm kiếm 

- khi bot tới trang của bạn, nó nhận được `prerendered`, phiên bản HTML tĩnh trên trang.Phiên bản này 
đơn giản hơn cho bot để thu thập và index, cải thiện SEO cho trang. 
When a bot visits your site, it receives a prerendered, static HTML version of the page.


- `prerendering` là một dạng của `dynamic rendering`. Nó tải trước tất cả các trang cho trình thu thập. 


## Dynamic rendering and Server-side rendering 

- SSR liên quan đến việc xuất toàn bộ trang trên máy chủ trước khi gửi nó tới browser. 
Điều này có nghĩa là all js được chạy trên máy chủ và user nhận về trang được kết xuất đầy đủ. Nó cỉa thiện hiệu xuất và SEo nhưng cũng 
đặt gánh nặng tải lên server. 
- Dynamic rendering cung cấp các trang tĩnh HTML tới côngcụ tìm kiếm và phiên bản thường tơi users. 
Điều này có nghĩa là là khi bot tới trang của bạn,nó sẽ nhận được `prerendering`, HTML tĩnh, điều này dễ cho bot có thể thu thập và index. 
Trong khi đó, user nhận về phiên bản trang được kết xuất trên trình duyệt của họ, có thể cung cấp các trải nghiệm tương tác  nhiều hơn. 
