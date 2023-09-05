### from [https://ahmedash.dev/blog/laravel-core-patterns/pending-object-pattern/?ref=laravelnews?ref=laravelnews](https://ahmedash.dev/blog/laravel-core-patterns/pending-object-pattern/?ref=laravelnews?ref=laravelnews)


# Mô hình Pending Object Laravel 

Mô hình Pending Object đóng 1 vai trò quan trọng trong Laravel, vì nó được sử dụng trong hầu hết các khía cạnh của Laravel. Nó cung cấp các trải nghiệm nhà phát triển đặc biệt  cho các trợ lý.

# Pending Object là cái gì? 

Bạn đã  từng băn khoăn cái gì xảy ra khi bạn sử dụng phương thức **Mail::to**?

```php
Mail::to($request->user())
	->send(new OrderShipped($order));
```

Phương thưc **to()** ở đây không sinh ra 1 đối tượng Mail. Nó sinh ra 1 đối tượng PendingMail . 

```php
namespace Illuminate\Mail;

class Mailer
{
	public function to($users, $name = null)
	{
		if (! is_null($name) && is_string($users)) {
		    $users = new Address($users, $name);
		}

		return (new PendingMail($this))->to($users);
	}
}
```

Lợi thế của cách tiếp cận này là mỗi **Mail::to** sẽ có một đối tượng chờ xử lý lý độc quyền của riêng nó,nơi bạn có thể gọi các phương thức để điều chỉnh bất kỳ các khía cạnh liên quan tới đối tượng mail đặc biệt này bạn đã khởi tạo.

```php
namespace Illuminate\Mail;

class PendingMail
{
    public function __construct(MailerContract $mailer)
    public function locale($locale)
    public function to($users)
    public function cc($users)
    public function bcc($users)
    public function send(MailableContract $mailable)
    public function queue(MailableContract $mailable)
    public function later($delay, MailableContract $mailable)
}
```

Vì vậy, chúng ta điều tra cem **phương thức cc()** làm gì: 

```php
/**
 * Set the recipients of the message.
 *
 * @param  mixed  $users
 * @return $this
 */
public function cc($users)
{
    $this->cc = $users;

    return $this;
}
```

Nó dường như giống như 1 Data Transfer Object (DTO), bạn có thể **setters** và **getters* cho việc trao đổi dữ liệu giữa các lớp trong ứng dụng của bạn.Tuy nhiên, một khác biệt đáng kể trong cách tiếp cận cốt lõi của Laravel là Pending Object là có thể thực hiện được. Nguyên tắc này là rõ ràng trong các phương thức như **send** and **queue**.
```php 
public function send(MailableContract $mailable)
{
    return $this->mailer->send($this->fill($mailable));
}

public function queue(MailableContract $mailable)
{
    return $this->mailer->queue($this->fill($mailable));
}
```

# Core Pending Objects trong Laravel 10
 Tồn tại một mảng các Pending Objects cho bạn để khám phá và hiểu các chức năng của chúng. 

- Illuminate/Database/Eloquent/PendingHasThroughRelationship
- Illuminate/Broadcasting/PendingBroadcast
- Illuminate/Mail/PendingMail
- Illuminate/Foundation/Bus/PendingChain
- Illuminate/Foundation/Bus/PendingDispatch
- Illuminate/Foundation/Bus/PendingClosureDispatch
- Illuminate/Bus/PendingBatch
- Illuminate/Testing/PendingCommand
- Illuminate/Support/Testing/Fakes/PendingBatchFake
- Illuminate/Support/Testing/Fakes/PendingMailFake
- Illuminate/Support/Testing/Fakes/PendingChainFake
- Illuminate/Http/Client/PendingRequest
- Illuminate/Routing/PendingResourceRegistration
- Illuminate/Routing/PendingSingletonResourceRegistration
- Illuminate/Process/PendingProcess

# Ứng dụng của Pending Object
 Bây giờ, hãy cố thử xây dựng 1 hành động chờ cho trình xuât bản CSV. 

```php 
$users = User::all()->toArray();
CsvExporter::from($users)
	->columns(['email', 'username'])
	->noHeaders()
	->download()
```
 Điều này chứng minh một trình xuất bản CSV và cách 1 Pending Object có thể hỗ trợ chúng ta trong việc xây dựng file CSV này. Đầu tiên, chúng ta sẽ tạo ra 1 lớp CSVExporter: 

```php 
namespace App\Services\Exporter;

class CsvExporter
{
    public function from(array $data): PendingCsvExport
    {
        return new PendingCsvExport($data, $this);
    }

    public function generate(array $data, array $columns, string $delimiter = ',', bool $includeHeaders = true): string
    {
        $output = fopen('php://temp', 'r+');

        if ($includeHeaders && !empty($data) && !empty($columns)) {
            fputcsv($output, $columns, $delimiter);
        }

        foreach ($data as $row) {
            $selectedData = [];
            foreach ($columns as $column) {
                $selectedData[] = $row[$column] ?? null;
            }
            fputcsv($output, $selectedData, $delimiter);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
```
Ngoài phương thức sinhh, tôi đã chọn một cách tiêp cận dễ dàng hơn để chứng minh  và tạo ta 1 file chức năng xuất CSV thực. Tuy nhiên, bạn có thể chọn cho 1 gói được thiêt kế đặc biệt cho nhiệm vụ này nếu muốn. 

Tiếp theo, hình thành đối tượng PendingCSVExport.

```php 
namespace App\Services\Exporter;

use Illuminate\Support\Facades\Response;

class PendingCsvExport
{
    protected array $data;
    protected array $columns = [];
    protected bool $includeHeaders = true;
    protected string $delimiter = ',';
    protected CsvExporter $exporter;

    public function __construct(array $data, CsvExporter $exporter)
    {
        $this->data = $data;
        $this->exporter = $exporter;
    }

    public function columns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function noHeaders()
    {
        $this->includeHeaders = false;
        return $this;
    }

    public function delimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    public function download($filename = 'export.csv')
    {
        $content = $this->exporter->generate($this->data, $this->columns, $this->delimiter, $this->includeHeaders);

        return Response::make($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
```
Hãy xem ở đây cách PendingObject giữ một số thuộc tính về bố cục CSV và các dữ liệu. Sau đó, tải xuống 1 hành động đơn duy nhất. Sau đó, bạn có thể thêm các hành động như stream, queue,mail. để tạo hàng đợi xuất và gửi nó dưới dạng mail. Hoặc là chỉ tạo ra CSV và gửi trực tiếp cho người dùng. 

# Thực thi tự động 
 Bạn đã bao  giờ băn khoăn cơ chế đằng sau các công việc điều phối hoat động theo nhiều cách chưa? 
```php 
ProcessPodcast::dispatch();

ProcessPodcast::dispatch()->onQueue('emails');
```

Việc quan sát cách **dispatches** chỉ đơn thuần phân phối công việc, nhưng ngoài ra, nếu bạn xâu chuỗi 1 phương thức như queue, nó sẽ tính đến điều đó và vẫn phân phối `jobs`,  bạn có thể thắc mắc về trình điều khiên đằng sau hệ thống này. Câu trả lời nằm ở phương thức **__destruct**. 
```php 
public function __destruct()
{
    if (! $this->shouldDispatch()) {
        return;
    } elseif ($this->afterResponse) {
        app(Dispatcher::class)->dispatchAfterResponse($this->job);
    } else {
        app(Dispatcher::class)->dispatch($this->job);
    }
}

```
 Vậy điều gì thực sự xảy ra ở đây khi vạn viết **SomeJob::dispatch()**, nó chỉ trả về 1 PendingObject. Sau đó, php gọi tới phương thức **__destruct** khi nó bắt đầu quá trình thu gom rác ( bạn có thể đọc nheieuf hơn về nó từ PHP.NET). Laravel tận dụng công nghệ này để tự thực thi nó một các thuận lợi các pending object., loại bỏ nhu câu bạn phải kích hoạt  một phương thức kết luận như **run()** hoặc **send()**.








