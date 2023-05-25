
# from [https://www.sitepoint.com/responsive-css-layout-grids-without-media-queries/](https://www.sitepoint.com/responsive-css-layout-grids-without-media-queries/)

## Lưới bố cục Css đáp ứng không cần media quer.


> Nền tảng cho nhiều trang web tiếp tục là 1 lưới bố cục, cho dù là Grid hay Flexbox. Trong doạn trích này từ "Giải phóng sức mạnh của Css": Công nghệ cải tiến cho `Giao diện người dùng đáp ứng`, chúng ta sẽ thấy cả hai công cụ này cung cấp cách như nào để tạo ra lưới giao diện linh hoạt mà k cần truy vấn phương tiện.

## Giao diện đáp ứng với Grid
 Đầu tiên có lẽ là giải pháp yêu thích của tôi trong tất cả các giải pháp,bởi vì tính linh hoạt và dễ sử dụng của nó. Bằng cách sử dụng `Grid`, chúng ta có thể tạo ra 1 tệp đáp ứng của các `cột` cái mà tự tạo ra chúng khi cần thiết. Chúng ta sẽ cung cấp 1 giới hạn đơn - chiều rộng tối thiểu mà cột có thể - cái mà làm hai nhiệm vụ đôi như là `breakpoint` trước khi các phần tử cột bị chia ra các dòng mới.  
Video dưới đây sẽ chứng minh cách cư xử chúng tôi đang thực hiện:

 Đây là tất cả cần để thực hiện điều giao diện `grid` đáp ứng này, nơi chúng ta có thể giảm tối thiểu kích thươc cột được thiết lập tới 30ch thông qua một thuộc tính tùy chỉnh . Quy tắc này hướng dẫn trình duyệt tạo ra nhiều cột nhất sẽ phù hợp vơi cái có chiều rộng ít nhất là 30ch:

```html 
.grid {
--min: 30ch;

display: grid;
grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--min)), 1fr));
}
```

```html
<style>
   .grid {
        --min: 30ch;

        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, var(--min)), 1fr));

        gap: 1rem;
    }

    body {
        --padding: 5vw;
        --max-width: 180ch;
    }
</style>
<ul role="list" class="grid">
  <li class="card">
    <h3>Lorem, ipsum.</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
  </li>
  <li class="card">
    <h3>Minus, pariatur?</h3>
    <p>Veritatis optio illum possimus eveniet exercitationem perferendis ad?</p>
  </li>
  <li class="card">
    <h3>Iste, assumenda!</h3>
    <p>Esse minima in maiores similique unde eaque nostrum!</p>
  </li>
  <li class="card">
    <h3>Voluptatem, veritatis!</h3>
    <p>Provident deserunt veniam, debitis alias atque deleniti laboriosam!</p>
  </li>
  <li class="card">
    <h3>Accusamus, magnam.</h3>
    <p>Ipsum accusantium laboriosam, architecto cum dolor odit animi?</p>
  </li>
  <li class="card">
    <h3>Recusandae, placeat.</h3>
    <p>Incidunt, aliquid odit! Eaque nihil mollitia repellat beatae.</p>
  </li>
</ul>
```
 Vì 1fr là `max` của minmax(), các cột cũng được phép để kéo rộng để điền đầy bất kỳ không gian nào còn lại một cách công bằng trong 1 dòng. Vì vậy, nếu không gian có sẵn là 80ch và có hai `grid' con, chúng sẽ nhận 40ch. Nếu có 3 con, con thứ 3 sẽ nhận trên dòng thứ 2, vì 80 không chia dều với kích thước tối thiểu cho phép là 30.
## Bố cục đáp ứng với flexbox 

 Chúng ta có thể hoàn thành trải nghiệm đơn giản này với Flexbox. Sự khác biệt giữa `Flexbox` và `Grid` là các phần tử `grid` chuyển sang hàng mới không thể mở rộng trên nhiều cột `grid`. Với `flexbox` chúng ta có thể chỉ dẫn trực tiếp các phần tử `flex` phát triển để điền đầy tất cả các không gian bổ sung còn lại và ngăn cho `orphan` cái mà xảy ra với giải pháp `Grid`.

Trong mã này, như mã trong `Grid`, trình duyệt sẽ tạo ra nhiều cột có để phù hợp với không gian ben trong với kích thước nhỏ nhất là 30ch. Nếu có 3 phần tuwrvaf phần tử thứ 3 cần chuyển xuống dòng mới, nó sẽ nhận phần không gian còn lại do cú pháp viết tắt `flex` cái mà quan trọng để thiết lập `flex-grow` là 1. Bởi vậy, có cách cư xử như 1 fr trong hầu hết các trường hợp.: 
```html
.flexbox-grid {
--min: 30ch;

display: flex;
flex-wrap: wrap;
}

.flexbox-grid > * {
flex: 1 1 var(--min);
}
```
 Bức ảnh dưới đây thể hiện danh mục cuối, danh sách các phần tử có số lẻ trải rộng trên hai cột, nhơ có thuộc tính `flex-grow`
![images](https://uploads.sitepoint.com/wp-content/uploads/2023/05/1684338555flexbox-grid-layout.png)
 
> Chú ý: Trong cả hai giải pháp, nếu chúng ta thêm 1 `gap`, không gian đó sẽ bị trừ khỏi việc tính toán có bao nhiêu cột được tạo trước khi một dòng mới được tạo.
Note: in both the Grid and Flexbox solutions, if we add a gap, that space will be subtracted from the calculation of how many columns may be created before new rows are added.

Những độc giả sắc sảo có thể nhận ra một sự khác biệt quan trọng giữa hai giải pháp này: khi sử dụng `Grid`, `parent` định nghĩa hành xử `child`. Với `FlexBox`, chúng ta đặt hành xử bố cục cpn trên các con. Cú pháp ngắn gọn thiết lập, theo thứ tự: flex-grow, flex-shrink, và flex-basis.

Như là 1 trải nghiệm, chúng ta có thể thay đổi giá trị của `flex-grow` là 0 để xem những phần tử sẽ mở rộng tới giá trị của flex-basis. ( Trải nghiệm với Codepen mẫu bên dưới). Nó quan trọng để giữ flex-shrink là 1, để cuối cùng,khi  không  gian có sẵn bên trong là nhỏ hơn flex-basis. - phần tử vẫn cho phép để `shrink`, vì điều này giúp ngăn chặn tràn. 

 Thuộc tính flex-basis có thể được điều chỉnh cho giải pháp này để gắn với `breakpoint` duy nhất cho các phần tử khác nhau. Vì chúng ta đang thiết lập giá trị thông qua thuộc tính tùy chỉnh `--min`  và `flexbox child` kiểm soát kích thước của chúng và chúng ta có thể điều chỉnh nó với kiểu `inline`:
The flex
```html
<li style="--min: 40ch">...</li>

```
Một danh sách con khác trong ví dụ này vẫn sẽ hiện xung quanh nó và sử dụng 30ch cho quy tắc cơ bản, nhưng cột rộng hơn có thay đổi hiệu hành vi một cách hiệu quả. 

 ```html 
<style>
    .flexbox-grid {
        --min: 25ch;

        display: flex;
        flex-wrap: wrap;

        gap: 1rem;
    }

    .flexbox-grid > * {
        flex: 1 1 var(--min);
    }

    body {
        --padding: 3vw;
        --max-width: 180ch;
    }
</style>
 <ul role="list" class="flexbox-grid">
    <li class="card">
        <h3>Lorem, ipsum.</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    </li>
    <li class="card" style="--min: 40ch">
        <h3>Custom "Breakpoint" --min</h3>
        <p>Veritatis optio illum possimus eveniet exercitationem perferendis ad?</p>
    </li>
    <li class="card">
        <h3>Iste, assumenda!</h3>
        <p>Esse minima in maiores similique unde eaque nostrum!</p>
    </li>
    <li class="card">
        <h3>Molestiae, quo.</h3>
        <p>Quos ullam, iure inventore delectus sequi aliquam omnis!</p>
    </li>
    <li class="card">
        <h3>Illo, facere!</h3>
        <p>Vel pariatur quod alias, quaerat porro sint quas.</p>
    </li>
</ul>
 ``` 
Đây là hai công nghệ `Flexbox` khác cái mà sử dụng flex-grow và flex-basis trong những cách thú vị: 
- [Flexbox Holy ALbatross](https://heydonworks.com/article/the-flexbox-holy-albatross-reincarnated/), chia các cột trong 1 dòng dựa trên tổng chiều rộng của vùng chứa cha. 
- [sidebar layout](https://every-layout.dev/layouts/sidebar/) của Heydon và Andy ,thể hiện cách để bắt buộc các `breakpoint` dựa trên flexbox cho kiểm soát tốt hơn khi các phần tử bọc.
