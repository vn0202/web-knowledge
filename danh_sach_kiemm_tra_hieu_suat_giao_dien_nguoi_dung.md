## from [https://dev.to/jacobandrewsky/frontend-web-performance-checklist-2o9j](https://dev.to/jacobandrewsky/frontend-web-performance-checklist-2o9j)



# Danh sách kiểm tra hiệu suât web giao diện người dùng

     Web Performance (3 Part Series)
     1. Tối ưu hóa các hình ảnh trong apps JS với IPx
     2. Danh sách kiểm tra hiệu suất giao diện người dùng. 
     3. Tận dụng bộ nhớ đệm dể cải thiện hiệu suất web


Cải thiện hiệu suất của các ứng dụng web sẽ luôn luôn là cuốn hút. Chng ta muốn các trang web tải nhanh hơn, mượt mà hơn và không có nhiều thay đổi về bố cục( các chỉ số quan trọng về trang web lõi). Trong bài này, tôi muốn tóm tắt tất cả các kiến thức trong 1 tài nguyên duy nhất của sự thât ( với khía cạnh là tác gỉa bài báo)

Tài liệu tóm tắt này là dựa trên các bài viết sau: 

- [Hành trình hiệu suất trang web của tôi với Nuxt, Storyblok và Netlify](https://www.dawntraoz.com/blog/my-web-performance-journey-with-nuxt-storyblok-netlify/) bới @dăntraoz
-  [Chúng tôi đã đạt được điểm hiệu suất là 90+ Lighthouse như nào và đầy đủ chế độ ngoại tuyến cho mua sắm tại nhà DANA](https://medium.com/dana-engineering/how-we-achieve-90-lighthouse-performance-score-and-fully-offline-mode-for-dana-home-shopping-580b1b540c4d) bởi @jefrydco
- [Các Vitals web, google search , tối ưu hóa hiệu suất Vue state và Nuxt trong tháng 6 năm 2020 bởi Johannes Lauter](https://medium.com/weekly-webtips/web-vitals-google-search-the-state-vue-nuxt-performance-optimization-in-july-2020-71441eefc51).

 và các kiến thức của tôi đã thu nhặt dược trong nhiều năm qua. 

Đảm bảo ghé thăm các bài viết trên và dành 1 lượt like cho tất cả các bài viết đó và các tác giả. 

# Tải trước các yêu cầu  khóa/ Kết nối trước các nguồn gốc đươc yêu cầu .
Khia báo các link tải trước trong HTML của bạn để hướng dẫn trình duyệt để tải các tài nguyên quan trọng sớm nhất có thể.. 
```html

<head>
  <link rel="preload" href="critical.css" as="style">
  <link rel="preload" href="critical.js" as="script">
</head>
```


Cân nhắc việc thêm gợi ýcác kêt nối trước hoặc các tài nguyên tìm nạp dns trước  để hình thành các kết nối sớm với các nguồn quan bên thứ ba quan trọng.

```html
<link rel="preconnect" href="https://example.com">
<link rel="dns-prefetch" href="https://example.com">.
```
Tìm nạp trước dns hoat động chính xác như kết nối trước nhưng có hỗ trợ trình duyêt rộng hơn. 

# Giảm sử dụng bên thứ 3 

 Mã bên thứ 3 có thể có những ảnh hưởng lớn đến hiệu suất tải. Bạn có thể điều chỉnh như nào cách bạn đang sử dụng thư viện bên thứ 3 bằng: 

- Tải các kịch bản bằng cách sử dụng bất đồng bộ hoặc thuộc tính `defer` để tránh chặn  phân tích các tài liệu. 
- Tự lưu trữ kịch bản nếu máy chủ ben thứ 3 chậm. 
- Xóa các kịch bản nếu nó khoog thêm các giá trị sạch vào trang của bạn .
- Sử dụng link `rel=preconnect` hoặc link `rel=dns-prefetch` để thực thi 1 DNS tìm kiếm các domains lưu kịch bản bên thứ 3.

# Loại bỏ tài nguyên chặn hiển thị.
 Các tài nguyên bị chặn bức tranh đầu  của trang của bạn. Cân nhắc việc phân phối  các js/css nội tuyến quan trọng và trì hoãn tất cả các Js/styles không quan trọng. Bạn có thể giảm kích thước của các tranhg của bạn chỉ bằng cách gửi mã và kiểu bạn cần. 

Một khi bạn xac định các mã quan trọng, chuyển các mã từ các Url chặn hiển thị tới các thẻ script nội tuyến trong trang HTML của bạn. 

Các định kiểu quan trọng nội tuyến yêu cầu cho nước sơn đầu tiên bên trong 1 định kiểu khóa ở đầu của trang HTML và tải phần còn lại của các định kiểu một cách không đồng bộ bằng cách sử dụng pre load link

đọc thêm ở [đây](https://sia.codes/posts/render-blocking-resources/#how-do-i-test-my-website-for-render-blocking-resources%3F)



# Tối đa hóa/ Xóa các js và css không cần thiết 

Khi bạn đang xây dựng 1 ứng dụng lớn, bạn sẽ tới 1 nơi mà dự án của bạn có thể có nhiều mã hơn cái mà thực sự cần và sử dụng.

Sử dụng các công cụ như `CSS Minification` và `Terser JS plugin`.

Để loại bỏ các css không sử dụng , sử dụng công cụ như PurgeCSS. 

Để loại bỏ các Js không cần thiết bạn có thể sử dụng Terser đã được đề cập trước đo hoặc sử dụng Tree Shaking để cho phép loại bỏ các mã chết. Bạn cũng có thể sử dụng `Chia mã ` cái mà sẽ chia mã thành các bó có thể tải theo yêu cầu. 

# Quét các module bị trùng 
 Xóa các modules JS lớn, trùng lặp từ các bó dể giảm kích thước bó cuối cùng. 
![image](https://res.cloudinary.com/practicaldev/image/fetch/s--IR1cNC44--/c_limit%2Cf_auto%2Cfl_progressive%2Cq_auto%2Cw_800/https://dev-to-uploads.s3.amazonaws.com/uploads/articles/m8jgv7d98jpb0cwdz2gt.png)
 sử dụng [Webpack Bundle Analyzer](https://www.npmjs.com/package/webpack-bundle-analyzer)

# Giảm thời gian thực thi 
Sự kết hợp của chia mã, thu nhỏ và nén, loại bỏ các mã không sử dụng và công nghệ bộ nhớ dệm sẽ cải thiện thời gian thực thi 1 cách tuyệt vời. 

 Cân nhắc việc giảm thòi gian phân tích, biên dịch và thực thi JS. Bạn có thể thấy việc   phân phối trọng tải JS nhỏ hơn hữu ích cho điều này. 
Ý tưởng là tối ưu hóa cả mã JS và mã CSS, thu nhỏ nó và xóa các mã không cần thiết, cũng như thư viện bên thứ 3 chúng ta đang sử dụng. 
Giữ cho thời gian phản hồi của máy chủ cho các tài liệu chính ngắn bời vì tất cả các yêu cầu khác phụ thuộc vào nó. 

Có thể đọc ở [đây](https://gtmetrix.com/reduce-javascript-execution-time.html)

Xử lý hình ảnh 
Hình ảnh có kích thước phù hợp

Cung cấp các hình ảnh cái mà có kích thước phù hợp để tiết kiệm dữ liệu di động và cải thiện thời gian tải. 

```html
<img src="cat-large.jpg" srcset="cat-small.jpg 480w, cat-large.jpg 1080w" sizes="50vw">

```
Đọc thêm ở [đây](https://web.dev/uses-responsive-images/?utm_source=lighthouse&utm_medium=cli)

# mã hóa ảnh hiệu quả.

Tối ưu hóa ảnh tải nhanh hơn và tiêu thụ ít dữ liệu di động hơn. 
Việc sử dụng các dịch vụ CDN hình ảnh của bạn hoặc nén hình ảnh của bạn là đủ


có thể đoc ở [đây](https://web.dev/uses-optimized-images/)
và cũng có thể đọc 1 bài cái mà tôi đã phát hành cạch đây không lâu [đây](https://dev.to/jacobandrewsky/optimizing-images-in-js-apps-with-ipx-37b1)

# Cung cấp các hình ảnh trong định dạng next-gen. 
Các hình ảnh định dạng như webp hay avif cung cấp nén tôt hơn PNG hay JPEG, có nghĩa là tải nhanh hơn và tiêu thụ ít dâta hơn. 

Có thể đọc ở [đây](https://web.dev/uses-webp-images/)

# Các phần tử ảnh chỉ rõ chiều cao và chiều rộng.
 Thiết lập rõ ràng chiều cao và chiều rộng của phần tử ảnh để giảm thay đổi layout và cải thiện CLS. 

ĐỌc thêm ở [đây](https://gtmetrix.com/use-explicit-width-and-height-on-image-elements.html)


# Tải trước lớp sơn có nôi dung lớn nhất (LCP)

Tải trước các hình ảnh được sử dụng bằng các phần tử LCP để cải thiện thời gian LCP của bạn 

```html
<link rel="preload" href="/path/to/image.jpg" as="image">

```
```php
head() {
 return {
    link: [
      {
        rel: 'preload',
        as: 'image',
        href: 'path/to/lcp/image',
      },
    ],
  }
}
```
Có thể đọc thêm ở [đây](https://gtmetrix.com/preload-largest-contentful-paint-image.html#:~:text=Preloading%20the%20Largest%20Contentful%20Paint,sooner%2C%20enhancing%20their%20user%20experience.)

# Fonts 

## Tất cả các text duy trì hiển thị trong khi tải webfonts

Tận dụng đặc tính Css display phông để chắc chắn rằng text là hiển thị với người dùng trong khi webfonts tải 
```html
@font-face {
font-family: 'Arial';
font-display: swap;
}
```

 API display-font chỉ rõ 1 phông được hiển thị như nào. trao đổi nói với trình duyệt rằng text đang sử dụng font sẽ được thể hiện ngay lập tức bằng cách sử dụng phông của hệ thống. Một khi phông tùy chỉnh đã sẵn sằng, nó thay thế phong của hệ thống. 

 Ví dụ phông của Google, là đơn giản là thêm `&display=swap` tham số tới điểm cuối của URl fonts google: 
```html
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&**display=swap**" rel="stylesheet">

```
Có thể đọc thêm ở [đây](https://web.dev/font-display/)

# Điều cần tránh? 
 
## Thay đổi bố cục nhiều 
Thay đổi bố cục tích lũy là 1 chỉ số quan trọng được tính toán bằng cách cộng tất cả các thay đổi bố cục cái mà không xảy ra bởi hành vi tương tác người dùng. 
## Tránh kích thước của DOM quá mức. 

MỘt DOM lớn sẽ tăng kích thước bộ nhớ sử dụng, gây ra tính toán kiểu lâu hơn và yêu cầu chi phí tái cấu trúc lại bố cục lớn. 

## Điều hướng nhiều trang 
 Điều hướng giới thiệu trì hoãn bổ sung trước khi trang có thể được tải. 

Serving legacy JavaScript to modern browsers
Polyfills and transforms enable legacy browsers to use new JavaScript features. However, many aren't necessary for modern browsers.

Enormous network payloads
Large network payloads cost users real money and are highly correlated with long load times.

Defer requests until they're needed.
Optimize requests to be as small as possible, minimizing and compressing, try to use WebP for the images when it's possible. An image CDN will be always there to keep our performance up!
Cache requests so the page doesn't re-download the resources on repeat visits.
Document.write()
For users on slow connections, external scripts dynamically injected via document.write() can delay page load by tens of seconds.

Non-compositioned animations
Animations which are not composited can be heavy and increase CLS. Use translate and scale CSS properties instead.
