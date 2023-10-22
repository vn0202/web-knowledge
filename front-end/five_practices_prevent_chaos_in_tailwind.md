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

4. Giảm thiểu kích thước  bản dựng 
Nó quan trọng để giữ kích thước file nhỏ nhất có thể. tệp càng lớn thời gian tải càng lâu và hiệu suất giảm.

Tailwind cung cấp nhiều các lớp tiện ích nhưng không phải luc nào chúng ta cũng chỉ dùng trong 1 dự án đơn. Do đó hãy đảm bảo chỉ các class cần thiết đưọc sử dụng xuất hiện trong bản production. 
Nếu sử dụng Tailwind >= 3.0., Just-in-time (JIT) được bật, bạn không cần lo lắng về điều đo. 
Nếu phiên bản cũ hơn, bạn cần thực hiện thêm tùy chọn bổ sung bằng cách sử dụng `PurgeCSS`- một công cụ để xóa các class CSS không dùng. 
Bạn có thể bật JIT thủ công bằng cách: 
```html 
module.exports = {
	mode: 'jit',
	...
}
```
Một điều quan trọng khác cần nhớ là luôn tối giảm file css trước khi production( xóa các ký tự, khoảng trống không cần thiết) giúp làm giảm kich thước file. 
Nếu sử dụng Tailwind CLI, có thể thực hiện bằng cách: 
```html 
npx tailwindcss -o build.css --minify
```

5. Ngăn cản sự không nhất quán khi ghi đè và mở rộng

Giả sử bạn có 1 component: 
```html 
<Button className="bg-black" />
```
và button component 
```html 
export const Button = () => {
  return <button className="bg-white">Test button</button>
}
```

trong trường hợp này Button vẫn giữ nguyên 'bg-white', để làm được điều này cần chỉ rõ trong component: 
```html 
export const Button = ({ className = "bg-white" }) => {
  return <button className={className}>Test button</button>
}
```
Điều này không có gì sai, nhưng nó sẽ phiền toái và dài dòng khi bạn phải ghi đè và mở rộng nhiều class. 
Hơn nữa, điều này khuyến khich sử dụng bất kỳ class nào dẫn đến sự không nhất quán. 

Thay vào đó, hãy định nghĩa trước các biến thể có của component : 
```html 
const BUTTON_VARIANTS = {
  primary: "bg-blue-500 hover:bg-blue-600 text-white",
  secondary: "bg-gray-500 hover:bg-gray-600 text-white",
  danger: "bg-red-500 hover:bg-red-600 text-white"
};
export const Button = ({ className, variant = BUTTON_VARIANTS.primary }) => {
return <button className={clsx(className, variant)}>Test Button</button>
}
```


# Conclusion: 
 
Tailwind là một công cụ mạnh mẽ nhưng nó quan trọng là phải tuân theo những quy tắc sau để tránh sự hỗn độn phát sinh trong dự án của bạn.

*Đầu tiên,  bạn nên sử duụng Tailwind khi đã có 1 hệ thống thiết kế và mã thiết kế nhất quán và có các tùy chọn tiêp cận hướng component. Nếu không sử dụng các thàng phần tái sử dụng, không sớm thì muộn, nó sẽ là nỗi đau của bạn. dẫn đến cấu trúc HTML dài dòng, lặp lại.*
1. Tối thiểu số lượng các lớp tiện ích nhất có thể 
2. Phổ biến các quy ước với team, ví dụ, nhóm các mã thiết kế và đăt tên có ý nghĩa. 
3. Tương tự, triển khai các lớp theo thư tự nhất quán để đảm bảo mã sach. 
4. Toois thiểu kích thước tệp. 
5. Khi có thể hãy thiết lập các biến thể cho component của bạn. 
