<?php

class CsvExporter
{
    public  function from(array $data): PendingCsvExport
    {
        return new PendingCsvExport($data, $this);
    }

    public function generate(array $data, array $columns, string $delimiter = ',', bool $includeHeaders = true): string
    {
        $output = fopen('./text.txt', 'r+');

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
        print_r($content);
//        return Response::make($content, 200, [
//            'Content-Type' => 'text/csv',
//            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
//        ]);
    }
}
$array = [
    ['email'=>'nghia',
        'username' =>"hell"]
];

(new CsvExporter())->from($array)->columns(['email','username'])->noHeaders()->download();
