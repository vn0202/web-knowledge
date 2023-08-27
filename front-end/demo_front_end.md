##### from [https://martinfowler.com/articles/demo-front-end.html](https://martinfowler.com/articles/demo-front-end.html)

# Demo Front-End

Một ứng dụng front-end để kiểm tra và khám phá 1 API.
Bạn có từng thử một "demo" nơi mà các nhà phát triển tự hào hiển thị hết màn hình  này đến màn hình khác của 1 đầu ra Json từ API của họ, trong khi người dùng bối rối và mất tập trung, không thể hiểu ý nghĩa của nó? Bạn có từng cố gắng để sử dung một API trong quá trình phát triển và bực bội bởi nó khó để tìm kiếm chính xác trọng tải JSON và câu thần chú tiêu đề chính xác để có thể kiểm tra các tinh năng? Một "Demo Front-end" là 1 giao diện người dùng đơn giản để chứng minh và khám phá một API.

**Matteo** là một nhà phát triển và là lãnh đạo của tập đoàn công nghệ Thouhtworks Ý. Anh ấy thích điều đó khi Extreme Programming giúp các đội và doanh nghiệp thành công.  


# Động lực 

Một trong những thực hành cốt lõi của bất kỳ nhóm phát triển hoạt động tốt nào là có các bản mẫu phổ biến của những cải tiến mới nhất của sản phẩm họ đang xây dựng. Nếu sản phẩm có 1 giao diện người dùng, sau đó bản demo sẽ được cung cấp thông qua giao diện người dùng của chính nó một cách tự nhiên, có thể thậm chí việc để các cổ đông tham dự cuộc họp trực tiếp sử dụng nó. 

Nhưng nếu 1 sản phẩm là 1 API thì sao? Chúng ta thường đề cập rằng Backend và frontend được phát triển bởi cùng 1 đội,bởi vì điều này thường dẫn đến chất lượng cao  và thời gian phát triển ngắn hơn , được so sánh với trường hợp nơi mà hai teams riêng biệt phải hợp tác với nhau. Nhưng có những trường hớp, khi khi điều đó là không thể: vài Backend(API) được phát triển bởi 1 công ty cái mà bán cho bên thứ ba quyền truy cập để sử dụng các dịch vụ hữu ích thông qua API. Ví dụ,: Một tổ chức tài chính cung cấp 1 "cổng thanh toán" API cái mà giúp các web site thương mại điện tử nhận được các thanh toán từ khách hàng, hoặc một nhà cung cấp dịch vụ giao tiếp với công cụ so sánh thông qua một API cái mà các công cụ so sánh được gọi. 

Trong tất cả các trường hợp, nơi mà API không có 1 giao diện người dùng tự nhiên, nó trở nên khó để cung cấp 1 bản demo đầy đủ ý nghĩa. Thỉnh thoảng, các nhóm cố gắng để chứng minh khả năng sử dụng của API bằng cách show ra mã JSON được trả về bởi APU, nhưng điều này không dễ để hiểu, đặc biệt các bên liên quan không rành về công nghệ. Và để các bên liên quan thấy được sản phẩm trở lên vô vọng. 

Trong những trường hợp này, chúng tôi đã nhận ra lợi ích của việc phát triển 1 giao diện người dùng đơn giản, đặc biệt cho mục đích chứng minh API. Giao diện người dùng không cần là cầu kỳ hoặc trông đẹp, và nó không cần liên quan đến thiết lập 1 bản dựng chuyên dụng; mục đích là làm cho nó hiển thị việc sử dụng API một cách nhanh chóng. 
![image](https://martinfowler.com/articles/demo-front-end/interface.png)
Tôi đã nhận thấy rằng, Jquery đáp ứng hầu hết các nhu cầu của tôi về mặt này, nó không hợp thời trang, nhưng mạnh mẽ và ngắn gọn. Một phiên bản thay thế hiện đại là React( với không build), và Js hiện đại mạnh đến nỗi nó có thể được sử dụng trực tiếp mà không cần bất kỳ thư viện hay Frameworks nào.


Lợi ích của bản Demo Front-end như thế là không giới hạn để giới thiệu phần mềm trong suốt bản demos, một khi có mã này, nó sẽ được sử dụng bởi các nhà phát triển để kiểm tra các tính năng mới trên thiết bị của họ trước khi đẩy mã lên repository, và bởi các nhà phân tích chất lượng, chủ sở hữu sản phẩm và các bên liên quan khác kiểm trả sản phẩm trong môi trường test. Nó có thể cũng được sử dụng để chứng minh tác dụng của API tới các đối tác tiềm năng người mà có thể sẽ thích thú đầu tư để truy cập vào nó. Bản Demo Front-end là một món quà không ngừng được tặng. 

# Lời khuyên thực hành. 

Bản Demo Front-end hoạt động tốt nhất khi nó ngay lập tức có sẵn ở trong tất cả các nơi, nơi mà bản API được phát hành tồn tại. Ví dụ, trong ứng dụng Spring Boot, bạn có lẽ đặt tài sản  các HTML, CSS và Js tĩnh  trong 1 thư mục src/main/resources/public/testdrive, để nó có thể truy cập chúng bằng cách mở trình duyệt, ví dụ [https://localhost:8080/testdrive/](https://localhost:8080/testdrive/). Bản demo UI đơn giản nhất có thể làm nhiều hơn là thay thế Postman: 

![https://martinfowler.com/articles/demo-front-end/demo-name.png](https://martinfowler.com/articles/demo-front-end/demo-name.png)*Figure 2:Người dùng có thể tùy chỉnh các trọng tải Request, phương thức và đường dẫn: Phản hồi xuất hiện trong cửa sổ thấp hơn, màu xanh để biểu thị 1 phản hồi thành công.



*Nếu ban đang sử dụng Spring Boot,đảm bảo rằng đã cài đặt devtools trong dự án của bạn, để bạn có thể kiểm tra các thay đổi với các tài sản tĩnh như bản Demo front-end mà không cần khởi động lại máy chủ.* 
![https://martinfowler.com/articles/demo-front-end/demo-missing.png](https://martinfowler.com/articles/demo-front-end/demo-missing.png)*Figure 3: Các phản hồi lỗi được hiển thị rõ ràng hơn bằng màu sắc cảu văn bản đầu ra vùng màu hồng.*

    Đọan trích này thể hiện cách để sử dụng Jquery để tải 1 trọng tải trong văn bản đầu vào từ tệp tĩnh. Nó sẽ được thực thi trên mỗi lần làm mới trang: 

    $(document).ready(() => {
    $.get("test-data/hello.json", (data) =>
    $("#hello-input").val(data));
    });



Bản Demo UI chuẩn bị 1 request JSON hợp lệ cho 1 đầu vào API được cung cấp, sau đó nó để người dùng điều chỉnh request thủ công để phù hợp với cái mà họ muốn kiểm tra, và khi  người dùng nhấn nút, nó sẽ thể hiện ra phản hồi, có thể cùng với mã trạng thái và các tiêu đề liên quan. 

Gọi API với 1 cuộc gọi tới máy chủ: 

```javascript
$(document).ready(() => {
$("#hello-button").click(sayHelloRequest);
});

function sayHelloRequest() {
$("#hello-output").val("");
$("#hello-spinner").show()
$.ajax({
method: $("#hello-method").val(),
data: $("#hello-input").val(),
contentType: "application/json",
url: $("#hello-path").val(),
success: onSayHelloSuccess,
error: onSayHelloError,
complete: onSayHelloComplete,
});
}

function onSayHelloSuccess(content) {
$("#hello-output").
removeClass("error").
addClass("success").
val(indent(content));
}

function onSayHelloError(jq) {
const d = jq.responseJSON ?
jq.responseJSON : jq;
$("#hello-output").
removeClass("success").
addClass("error").
val(indent(d));
}

function onSayHelloComplete() {
$("#hello-spinner").hide();
}

function indent(json) {
return JSON.stringify(json, null, 2);
}

```
 Mặc dù tất cả các điểm này chúng ta vẫn hiện JSON ở cả đầu vào và đầu ra, chúng ta có 1 lợi ích có thể lợi thế đáng kể hơn Postman, đó là chúng ta có thể sử dụng tự động để tăng cường hoặc điều chỉnh một phiên bản tĩnh của đầu vào Json được đề xuất tới người dùng. Nếu, ví dụ, một yêu cầu hợp lệ nên chứa 1 định dang duy nhất, thì một đoạn Js ngắn có thể sinh ra một định danh bất kỳ mà không yêu cầu bất kỳ nỗ lực nào từ người dùng. Cái quan trọng ở đây là UI cho phép 1 kiểm tra nhanh hơn với ít trở ngại nhất.

Js được yêu cầu cho việc tạo 1 bản demo Front-end như này là tối thiểu: Js hiện tại đủ mạnh mẽ mà không cần bất kỳ thư viện đặc thù nào, mặc dù các nhà phát triển có thể thấy nó tiện để sử dụng các công cụ nhẹ như Htmx, Jquery hoặc thậm chí là Inline React. Chúng tôi đã đề cập tránh thiết lập 1 bản dựng chuyên dụng, vì điều này dẫn tới các bước bổ sung giữa chạy API và thực thi 1 bài kiểm tra thông qua UI. Lý tưởng nhất là bản dựng duy nhất chúng ta muốn chạy là bản dụng của chính sản phẩm API. Bất kỳ sự trì hoãn nào giữa mong muốn kiểm tra vài thứ và hiện tại chúng ta đang thực sụ kiểm tra bài test làm chậm vòng phát triển. 

Sự phát triển tự nhiên của mội UI là : 

- Thêm các cơ sở để sinh ra các kiểu khác nhau của đầu vào, có lẽ thay thế hoàn toán các đoạn JSON textarea với 1 form HTML chuẩn. 

- Phân tích và hiển thị đầu ra trong 1 cách dễ để hiểu. 

Ví dụ, giả sử chúng ta có 1 API liên quan đến du lịch cái mà cho phép chúng ta đặt các chuyến bay, với mục đich để tìm ra giao dịch tốt nhất cho các traveler người có thể linh hoạt về lịch trình. CHúng ta có thể có 1 API khởi tạo thực thi 1 tìm kiếm và trả về 1 danh sách các kết hợp giá. Đầu vào JSON có thể trông như này: 
```php
{
"departure-airport": "LIN",
"arrival-airport"  : "FCO",
"departure-date"   : "2023-09-01",
"return-date"      : "2023-09-10",
"adults"           : 1,
"children"         : 0,
"infants"          : 0,
"currency"         : "EUR"
}
```

Bản demo UI sẽ tải một tải trọng mãu vào vùng văn bản đầu vào, bởi vậy, người dùng không cần phải nhớ chính xác cú pháp. 

![image](https://martinfowler.com/articles/demo-front-end/search-json.png)


Tuy nhiên, người dùng có thể cần thay đổi ngày, bởi vì bất kỳ ngày khởi hành tĩnh hoặc ngày đến sau cùng sẽ trở nên không hợp lệ vì thời gian trôi qua và ngày đã trôi qua., và việc thay đổi ngày cần thời gian và có thể dẫn đến ngày trong tương lai sẽ mất bởi vì các lỗi thủ công. Một giải pháp có thể là tự động điều chỉnh ngày trong JSON, thiết lập chúng, thành 30 ngày trong tương lai. Điều này làm nó rất dễ để thực thi nhanh 1 bài "smoke test" của API: chỉ bấm "Search flights" và xem kết quả. 

Chúng ta cso thể cần bước này trong tương lai: ví dụ, thỉnh thoảng, chúng ta cần kiểm tra vài thông tin của chuyến bay trong khoảng 6 tháng tới; thỉnh thoảng là 3 tháng, thinh thoảng là vài tuần. Nó thú vụ để cung cấp 1 UI cái mà cho phép người dùng có thể nhanh chóng thay đổi trọng tải Json bằng cách chọn từ menu. Nếu chúng ta có thể làm tương tự cho các trường đầu vào, ví dụ, mã máy bay, chúng ta xóa những thứ cần cho người dùng để tìm kiếm mã mays bay, việc này cũng tốn thời gian quý báu. 
![https://martinfowler.com/articles/demo-front-end/search-form.png](https://martinfowler.com/articles/demo-front-end/search-form.png)
