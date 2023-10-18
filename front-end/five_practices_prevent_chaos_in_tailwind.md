### [https://evilmartians.com/chronicles/5-best-practices-for-preventing-chaos-in-tailwind-css](https://evilmartians.com/chronicles/5-best-practices-for-preventing-chaos-in-tailwind-css)

# Summary 

Tailwind Css rất nhanh và dễ dàng thao tác, bạn chỉ cần dán các class khác nhau trong HTML của bạn. Nhưng khi dự án ngày càng lớn, class ngày càng nhiều đến một lúc nào đó bạn sẽ không hiểu nổi chính những gì bạn viết. 

Bạn có thể tránh các vấn đề này bằng cách sử dụng Tailwind đúng đắn và thông minh. Nhưng có hai yêu cầu mà dự án của bạn cân đạt được, nếu không Tailwind sẽ làm cho công việc của bạn khó hơn: 

- Đầu tiên, bạn phải có 1 thiết kế hệ thống dự án của bạn. Triết lý của Tailwind đi cùng với thiết kế hệ thống nơi mà các designer và nhà phát triển sử dụng cùng 1 mã thiết kế. Mã thiết kế là các giá trị nguyên tử( như màu, spacing, kiểu chữ )  định nghĩa các thuộc tính và tái sử dụng trong suốt dự án.
Giả sử bạn có một màu primary: `oklch(45% 0.2 270);`. Bạn sử dụng ở nhiều nơi trong dự án. Một ngày đẹp trời nào đó, bạn k muốn dùng màu này nữa. bạn muốn dùng màu khác. Khi đó, bạn sẽ phải tìm màu đó ở tất cả các nơi sử dụng và sửa lại. That is terrible.

## Mã thiết kế ở đây là các thuộc tính như màu săc, font ... được định nghĩa trong file tailwind.config.. điều này giúp dễ bảo trì code và đảm bảo tính nhất quán 
```php 
module.exports = {
  theme: {
    colors: {
      primary: 'oklch(45% 0.2 270)'
    }
  }
}
```

Nếu bạn chưa có mã thiết kế thống nhất, bạn không nên sử dụng Tailwind. Bởi vì khi đó bạn sẽ phải viết các class magic như `p-[24px]`... làm cho nó trở lên lộn xộn, khó kiểm soát

##  Có 1 thiết kế nhất quán giúp các nhàphát triển và nhóm thiết kế hiểu nhau hơn. 
Với figma, bạn có thể có 1 nguồn đang tin duy nhất cho bất kỳ giá trij nào trong thiết kế của bạn. Để hệ thống có thể bảo trì được, bạn cần giới thiệu các quy ước liên quan về mã và tên. 

Đây là yêu cầu thứ hai mà dự án của bạn cần đáp ứng: Nên sử dụng theo hướng build các component. Điều này sẽ tránh làm cho code lộn xộn và dài dòng, đặc biệt là khi dự án ngày càng lớn. 


## Giải pháp: sử dụng  cách tiếp cận dựa vào các component, đóng gói các mẫu thường dùng  thành các components riêng rẽ. 

Với cách tiếp cận này, chúng ta có thể giữ mọi thứ DRY ( don't repeat yourself). Hơn nữa, chúng tôi vẫn sẽ có một nguồn thông tin chính xác duy nhất cho các phong cách Tailwind của mình và chúng tôi có thể dễ dàng cập nhật các nguồn thông tin đó ở cùng một nơi

Nếu công cụ của bạn khoong cho phép tách các component, cách tiếp cận của Tailwind sẽ làm khó cho bạn, bạn nên sử dụng các CSS framework khác như CSS modules. 

Và điều cần chú ý là tránh sử dung `@apply`
```php 
block {
  @apply bg-red-500 text-white p-4 rounded-lg active:bg-blue-700 active:text-yellow-300 hover:bg-blue-500 hover:text-yellow-300;
}

```
bằng cách sử dụng lệnh này, mã của bạn có thể trông gọn gàng hơn, nhưng nó làm mất đi những ưu điểm chính của Tailwind: ít cần suy nghĩ hơn  khi nghĩ ra tên cho các lớp CSS và không có hồi quy khi thay đổi kiểu . Hơn nữa, sử dụng nó sẽ tăng kích thước gói CSS.

## Giải pháp
 

1.  Sử dụng ít các lớp tiện ích khi có thể; 
ví dụ tahy vì viết `pt-4 pb-4` có thể viết : `p-4`...
2. Nhóm thiết kế mã thông báo và đặt tên cho chúng theo ngữ nghĩa
```php 

module.exports = {
  theme: {
    colors: {
      primary: 'oklch(75% 0.18 154)',
      secondary: 'oklch(40% 0.23 283)',
      error: 'oklch(54% 0.22 29)'
    },
    spacing: {
      'sm': '4px',
      'md': '8px',
      'lg': '12px'
    },
    screens: {
      'sm': '640px',
      'md': '768px'
    },
  },
  //...
}
```
3. Giữ các class theo thứ tự 

 ```html
// không sawpx xếp 
<div class="p-2 w-1/2 flex bg-black h-2 font-bold">
  First block with unsorted classes
</div>

<div class="italic font-mono bg-white p-4 h-2 w-3 flex">
  Second block with unsorted classes
</div>
```
```html 

<div class="flex h-2 w-1/2 bg-black p-2 font-bold">
  First block with sorted classes
</div>

<div class="flex h-2 w-3 bg-white p-4 font-mono italic">
  Second block with sorted classes
</div>
```

có thể sử dụng các công cụ như [Prettỉer](https://github.com/tailwindlabs/prettier-plugin-tailwindcss) của Tailwind. 

4. Giảm thiểu kích thước bản dựng