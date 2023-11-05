### from [https://laravel-news.com/adding-a-second-server-to-your-app](https://laravel-news.com/adding-a-second-server-to-your-app)

# Summary 
Việc thêm 1 server thứ hai vào ứng dụng của bạn có thể là 1 cách tuyệt vời để cải thiện hiệu suất app của bạn và tăng độ tin cậy của nó. Tuy nhiên, có nhiều điều bạn cần chú ý khi thêm 1 server thứ hai . 

Cùng thảo luận về những thứ quan trọng vạn cần quan tâm khi thêm 1 server bổ sung vào ứng dụng của bạn. Chúng ta sẽ sử dụng Laravel được lưu trong `Laravel Forge` cho ví dụ, nhưng những khái niệm có thể được áp dụng cho bất kỳ ứng dụng nào thậm chí không giới hạn trong PHP. 


### Cơ sở hạ tầng hiện tại 

 Đầu tiên, hãy đảm bảo rằng chúng ta đang cùng nói về 1 ngôn ngữ, đây là bản phác thảo của cơ sở hạ tầng hiện đại. Ứng dụng này hiện đang chạy trên 1 máy chủ được tạo bởi Laravel Forge và chạy trên AWS. 

First, to make sure we are speaking the same language, this is the outline of the current infrastructure. This app is currently running on a server created by Laravel Forge and running on AWS.

- Cho phép mã hóa chứng chỉ SSL; 
- Redis( được cài trên máy) cho các phiên, bộ đệm và trình điều khiển queue cho lưu trữ và các công việc tiến trình ngầm; 
- MySQL( cài trên máy) cho CSDL;
- Thư mục local cho việc lưu các nội dung được tải lên của người dùng;
- Laravel Schedule bằng cách sử dụng CRON của máy chủ mỗi phút;
- Triển khai được kích hoat thủ công bằng cách nhấn nút "Deploy now";

## 1. Load balancer

 Đầu tiên chúng ta cần là bộ cân bằng tải. Đây sẽ là đầu vào cho ứng dụng của bạn, nghĩa là bạn sẽ trỏ DNS domain của bạn tới bộ cân bằng tải thay vì trực tiếp tới server. Công việc của 1 bộ cân bằng tải là,để cân bằng các request đến với `sức khỏe` và các máy chủ được đăng ký. 

Từ đây, mỗi khi đề cập tới `App server` có nghĩa là đang nói tới 1 máy chủ đơn chạy ứng dụng Laravel. 
 ![https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-load-balancer.png](https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-load-balancer.png)


Một trong những tính năng tốt của bộ cân bằng tải là việc kiểm tra sức khỏe, nhằm đảm bảo rằng tất cả các máy chủ được kết nối đều tốt. Nếu 1 trong các máy chủ thất bại vì một lý do nào đó, bảo trì đột ngột chảng hạn, bộ cân bằng tải sẽ ngừng định tuyến các request tới server đó cho đến khi nó hoạt động ổn định trở lại. 

Chúng tôi đề suất sử dụng bộ cân bằng tải app, cung cấp nhiều các tính năng mạnh mẽ hơn về sau nếu bạn cần. Bộ cân bằng tải app có thể định tuyến các traffic tới các máy chủ cụ thể dựa trên các URL được yêu cầu và thậm chí định tuyến các yêu cầu tới nhiều app. Bây giờ chúng ta sẽ có 1 cân bằng đồng đều các truy cập 
  Vì tên miền của bạn sẽ được trỏ tới bộ cân bằng tải, chứng chỉ SSL cũng nên trong bộ cân bằng tải, thay vì trong máy chủ của bạn. 

## 2. Database (MySQL), cache & queue (Redis)
ú
Hiện tại, có một máy chủ chạy app của bạn, một thực thể cục bộ MySQL và Redis. Điều gì sẽ xảy ra khi cái thứ hai được gắn vào bộ cân bằng tải?

Việc có nhiều các tài nguyên tin cậy cho CSDL và lớp bộ đệm có thể sinh ra rất nhiều các vấn đề. Với nhiều CSDL, người dùng sẽ được đăng ký trong 1 máy chủ nhưng không phải cái còn lại. Với 1 thực thể của Redis trên máy chủ, bạn có thể đăng nhập vào App Server 1 nhưng khi bộ cân bằng tải chuyển hướng sang App Server thứ hai, bạn sẽ phải đăng nhập lại vì phiên của bạn đang được lưu trong thực thể Redis cục bộ. 

Chúng ta có thể tạo App server 2, hoặc cho các máy chủ tương lai được kết nối tới bộ cân bằng tải, kết nối với các dịch vụ của máy chủ 1, nhưng điều gì xảy ra nếu App Server 1 ngừng hoạt động để bảo trì hoặc các lỗi không mong đợi? Một trong các lý do để thêm máy chủ thứ hai là tăng độ tin cậy và khả năng mở rộng, điều này không phải quyết vấn đề của chúng ta. 
 Ngữ cảnh lý tưởng,  khi chúng ta có nhiều app servers, là có thêm các dịch vụ bên ngoài như MySQL và Redis chạy trong 1 moi trường độc lập. Đề làm được điều này, chúng ta cần sử dụng các dịch vụ có thể quản lý như `AWS RDS`, cho CSDL và `AWS Elasticache` cho Redis hoặc các dịch vụ không được quản lý, nghĩa là chúng ta sẽ thiết lập 1 máy chủ riêng để chạy những dịch vụ này. Các dịch vụ được quản lý thường là các lựa chọn tốt hơn nếu tiền bạc không là vấn đề với bạn vì bạn sẽ không phải lo lắng về hệ điều hành và upgrade các phần mềm và thường có 1 lớp bảo mật tốt hơn.

Hãy tưởng tượng chúng ta quyết định với dịch vụ có thể quản lý cho ứng dụng của chúng ta. Cấu hình Laravel sẽ tương tự thế này: 

```php
-DB_HOST=localhost
+DB_HOST=app-database.a2rmat6p8bcx7.us-east-1.rds.amazonaws.com
-REDIS_HOST=localhost
+REDIS_HOST=app-redis.qexyfo.ng.0001.use2.cache.amazonaws.com

```
Sau khi mọi thứ được thiết lập, cơ sở hạ tầng của chúng ta sẽ trong như này khi kết nối các App Server với các dịch vụ của chúng ta. 
![https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-database.png](https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-database.png)

## 3. User uploaded content

 Ứng dụng của chúng tôi cho phép người dùng tải các ảnh hồ sơ cá nhân tùy chỉnh, sẽ show ra khi người dùng đăng nhập. Trên CSHT hiện tại, các ảnh được lưu vào trong một thư mục bên trong và cũng có thể lấy ra từ đó. Bây giờ, chúng ta có nhiều App Services, đấy sẽ là 1 vấn đề, vì ảnh được lưu trong App Server 1 sẽ không tồn tại trong server thứ hai. 

 Có nhiều cách để giải quyết vấn đề này.Một trong số chúng là có 1 thư mục được chia sẻ giữa các máy chủ của bạn ( ví dụ Amazon EFS).Nếu chúng ta chọn giải pháp này, chúng ta sẽ có 1 filesystem tùy chỉnh cấu hình trong Laravel để trỏ tới vị trí thư mục được chia sẻ này  trên các máy chủ. Trong khi đây là một tùy chọn hợp lệ, điều  này yêu cầu nhiều kiến thức để thiết lập các `disk` trên các máy chủ và cho mỗi máy chủ mới bạn thiết lập, bạn phải cấu hình thư mục chia sẻ này lại.

Chúng tôi thích sử dụng các dịch vụ lưu trữ đám mây hơn,như Amazon S3 hoặc `Digital Ocean Seas`. Laravel khiến nó thực sự dễ dàng để làm việc với những dịch vụ này, nếu bạn đang sử dụng tùy chọn `File Storage`. Trong trường hợp này, bạn chỉ cần cầu hình đĩa file hệ thống của bạn sử dụng S3, và upload tất cả các nội dung đã được người dùng của bạn upload vào 1 thùng chứa ( bucket). 

```php
-FILESYSTEM_DISK=local
+FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=us-east-1S
AWS_BUCKET=your-bucket-name
```

Tất cả các nội dung đã được người dùng của bạn upload sẽ được lưu trong cùng 1 trung tâm bucket. S3  được tích hợp sẵn trong các phiên bản, nhiều lớp dự phòng và bất kỳ những App Server bô sung nào chúng ta thêm vào bộ cân bằng tải, có thể sử dụng chung Bucket này để lưu các nội dung. 

![https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-s3-bucket.png](https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-s3-bucket.png)

 Nếu ứng dụng của bạn phát triển hơn trong tương lai, bạn có thể thiết lập [AWS Cloudfront](https://aws.amazon.com/cloudfront/) cho phép thực hiện như 1 lớp CDN trên bucket S3 của bạn, cung cấp nội dung nhanh hơn tới người dùng của bạn và thường nhanh hơn S3. 

## 4. Queue workers

 trong bước hai, chúng ta đã thiết lập 1 máy chủ tập trung Redis, công nghệ chúng ta đang sử dụng để quản lý các hàng đợi ứng dụng. Điều này cũng hoạt động cho bộ cân bằng tải app, nhnwg có những tùy chọn tốt hơn để khám phá. 

Nếu bạn tiếp tục xử lý hàng đợi của bạn trên các máy chủ app bằng cách tận dụng  Redis tập trung, bạn không cần làm gì cả. Jobs sẽ được lấy bởi máy chủ cái mà có sẵn các worker  để xử lý công việc. 

Một tùy chọn khác là sử dụng 1 dịch vụ như `AWS SQS`,  giúp giảm áp lực cho Redis của bạn vì ứng dụng có thể xử lý công việc đó trên các dịch vụ khác. 

## 5. Scheduled commands

 Khi chạy nhiều máy chủ đằng sau bộ cân bằng tải, Lập lịch `command` sẽ chạy trên mỗi máy chủ được gắn với bộ cân bằng tải theo mặc định, điều này chưa tối ưu. Không chỉ sẽ chạy cùng 1 command nhiều lần gây lãng phí tài nguyên, mà còn gây ra các vấn đề về độ chính xác dữ liệu phụ thuộc vào command đó làm gì. 

Laravel có 1 cách được tích hợp sẵn để xử lý vấn đề này để các command `schedule` chỉ chạy trên 1 máy chủ đơn bằng cách nối thêm phương thức `onOneServer(). 

```php 
$schedule->command('report:generate')
->daily()
->onOneServer();
``` 

 Bẳng cách sử dụng phương thức này yêu cầu sử dụng chung máy bộ đệm tập trung.


## 6. Deployment

 Khi thực hiện triển khai ứng dụng, ban có nhiều tùy chọn và có nhiều thứ để cân nhắc. 

chúng ta có thể vẫn deploy ứng dụng với cách tiếp cận trước đó, nhưng bây giờ chúng ta phải đảm bảo chúng ta nhớ nhấn click vào nut deploy trên cả hai máy chủ. Nếu quên,  chúng ta có hai máy chủ chạy nhiều phiên bản khác nhau của ứng dụng,gây ra nhiều vấn đề lớn. 

Với nhiều máy chủ, có lẽ đã đến lúc nâng cấp chiến lược triển khai. Có một số công cụ và dịch vụ triển khai rất tốt, như Laravel Envoyer hoặc PHP Deployer. Những loại công cụ và dịch vụ này cho phép bạn tự động hóa quy trình triển khai trên nhiều máy chủ, do đó bạn có thể loại bỏ lỗi của con người khỏi phương trình.

Deployment is a very important process of our applications, so if we can automate the deployment using Github Actions or any CI/CD services out there, we are greatly improving the process. Making the deployment process simple and where anyone can trigger a deployment really shows the maturity of the development team and the application.



7. Network & security
   One additional benefit we have with the use of a load balancer is that our servers are not the entrypoint of our websites anymore. This means we can only have our servers be internally accessible and/or restricted by specific IPs (our IPs, Load Balancer IPs, etc). This greatly improves the security of our servers since they are not directly accessible. The same can (and should) be done for our database and cache clusters.

To achieve this, we are going to only allow traffic to port 22 from our own IPs (so we can SSH into the server) and we are going to only allow traffic to port 80 from the load balancer, so it can send requests to the server. The same rules apply for our database and cache clusters.

Final thoughts
There are a lot of things to consider when adding additional servers to your infrastructure. It adds more complexity to your infrastructure and workflows, but it also increases the reliability and scalability of your application as well as improves your overall security.

When considered from the beginning of the process, these recommendations are simple to implement and can have a large impact on improving your app.

Luis Dalmolin photo
Luis Dalmolin

Luis is a senior developer at Kirschbaum and has over 10 years of experience architecting complex applications and has been working with Laravel since the early days of Laravel 4.

In addition to PHP and Laravel, Luis specializes in VueJS/Javascript and everything DevOps related. He loves working with Open Source and has contributed several open-source projects to the community.

Luis taught an AngularJS course at University Feevale, where he also earned his degree in Internet Systems, and he translated the Laravel book "Code Bright" by Dayle Rees into Portuguese.

XX
GitHubGitHub
Filed in:
Laravel Tutorials
Forge
Cube
Laravel Newsletter
Join 40k+ other developers and never miss out on new tips, tutorials, and more.

EmailNewsletter icon
Email
Join free
Cube
Laravel Jobs
Explore hundreds of open positions today.

View all jobs
Full Stack Laravel Developer
Senior Laravel Engineers → Roll with high growth team
Laravel Full Stack Developer
Senior Laravel Developer
Senior Full Stack Developer
image
Tinkerwell
Version 4 of Tinkerwell is available now. Get the most popular PHP scratchpad with all its new features and simplify your development workflow today.

Visit Tinkerwell
The latest
View all →
Easily Read and Write XML in PHP image
Easily Read and Write XML in PHP
Read article
Tinkerwell v4 is now released image
Tinkerwell v4 is now released
Read article
Dispatch Events after a DB Transaction in Laravel 10.30 image
Dispatch Events after a DB Transaction in Laravel 10.30
Read article
BookStack is a simple and free Wiki software Written in Laravel image
BookStack is a simple and free Wiki software Written in Laravel
Read article
Register now for Sentry Launch Week! image
Register now for Sentry Launch Week!
Read article
7 Tips for Adding a Second Server to your App image
7 Tips for Adding a Second Server to your App
Read article
Partners
View all →
Honeybadger logoHoneybadger
Zero-instrumentation, 360 degree coverage of errors, outages and service degradation.

Visit partner's website
Laravel Forge logoLaravel Forge
Easily create and manage your servers and deploy your Laravel applications in seconds.

Visit partner's website
LoadForge logoLoadForge
Easy, affordable load testing and stress tests for websites, APIs and databases.

Visit partner's website
Kirschbaum logoKirschbaum
Providing innovation and stability to ensure your web application succeeds.

Visit partner's website
Bacancy logoBacancy
Supercharge your project with a seasoned Laravel developer with 4-6 years of experience for just $2500/month. Get 160 hours of dedicated expertise & a risk-free 15-day trial. Schedule a call now!

Visit partner's website
Shift logoShift
Running an old Laravel version? Instant, automated Laravel upgrades and code modernization to keep your applications fresh.

Visit partner's website
Subscribe to our newsletter
Cube
EmailNewsletter icon
Email
Join free
and follow us on

FacebookFacebook
InstagramInstagram
XX
LinkedinLinkedin
TelegramTelegram
YoutubeYoutube
ThreadsThreads
Laravel Newsletter
Laravel Links
Laravel Packages
Laravel Tutorials
Laravel Events
Popular Packages
Partners
Advertising
Laravel Jobs
Contact Us
Your account
© 2012 - 2023 Laravel News
A division of dotdev inc.

Colophon / About


dỡ hàng

Verb

cho hành khách xuống


Một tùy chọn khác là sử dụng dịch vụ như AWS SQS, dịch vụ này có thể giảm bớt một số áp lực lên phiên bản Redis khi ứng dụng của bạn phát triển bằng cách giảm tải khối lượng công việc đó sang dịch vụ khác.


Chúng tôi vẫn có thể triển khai các ứng dụng của mình bằng cách sử dụng phương pháp trước đó, nhưng bây giờ chúng tôi phải đảm bảo rằng chúng tôi nhớ nhấp vào nút triển khai trên cả hai máy chủ. Nếu quên, máy chủ của chúng tôi sẽ chạy các phiên bản ứng dụng khác nhau, điều này có thể gây ra sự cố lớn.


Vì hiện tại chúng tôi có 2 máy chủ ứng dụng, một trong những lợi ích tuyệt vời là chúng tôi có thể tạm thời xóa một trong các máy chủ khỏi bộ cân bằng tải và máy chủ đó sẽ ngừng nhận yêu cầu. Điều này cho phép chúng tôi triển khai không có thời gian ngừng hoạt động, trong đó chúng tôi xóa máy chủ đầu tiên khỏi bộ cân bằng tải, triển khai mã mới, đặt mã trở lại bộ cân bằng tải, xóa máy chủ thứ hai và thực hiện lại quy trình tương tự. Khi máy chủ 2 kết thúc, cả hai máy chủ sẽ có mã mới và được gắn vào bộ cân bằng tải. Để đạt được điều này, chúng tôi sẽ sử dụng các công cụ như AWS CodeDeploy, nhưng quá trình thiết lập phức tạp hơn các tùy chọn trước đây của chúng tôi.

Triển khai là một quá trình rất quan trọng đối với các ứng dụng của chúng tôi, vì vậy nếu chúng tôi có thể tự động hóa quá trình triển khai bằng cách sử dụng Github Actions hoặc bất kỳ dịch vụ CI/CD nào hiện có thì chúng tôi đang cải thiện quy trình rất nhiều. Việc làm cho quy trình triển khai trở nên đơn giản và nơi mọi người có thể kích hoạt triển khai thực sự cho thấy sự trưởng thành của nhóm phát triển và ứng dụng.

![https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-deployment.png](https://picperf.io/https://d11r87y54pwjy9.cloudfront.net/a2ca1920-d01e-406a-a6cf-18c06edfd3eb/images/insights/multiple-servers-deployment.png)

## 7. Network & security
 Một lợi ích chúng ta có với việc sử dụng bộ cân bằng tải là các máy chủ không phải là công vào của các web của chúng ta nữa. Điều này có nghĩa là chúng ta chỉ có thể có các máy chủ được truy cập nội bộ / hoặc giới hạn bởi các IPs ( our IPs, load balance IPs ). Điều này cải thiện tuyệt vời bảo mật cho các máy chủ vì chúng k được truy cập trực tiếp. Tương tự có thể hoặc nên được thực hiện cho các cụm CSDL hoặc bộ đệm. 

Để làm được điều này, chúng ta sẽ chỉ cho phép các truy cập tới cổng 22 từ IPs của chúng ta ( vì vậy chúng ta có thể thêm SSH vào máy chủ)  và chúng ta sẽ chỉ cho phép truy cập cổng 80 từ bộ cân bằng tải, vì vậy nó có thể gửi các request tới máy chủ. Quy tắc tương tự cho CSDL và bộ đệm. 

