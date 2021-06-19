<?php

namespace App\Util;

use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Webmozart\Assert\Assert;

class CSVWriter
{
    private array $data;
    private string $fileName;
    private array $headers;
    private \DI\Definition\Reference $outputPath;

    public function __construct(array $data, string $fileName, array $headers)
    {
        Assert::notEmpty($data);
        Assert::notEmpty($fileName);
        Assert::notEmpty($headers);

        $this->outputPath = \DI\get('report_store');
        Assert::notEmpty($this->outputPath);

        $this->data = $data;
        $this->fileName = $fileName;
        $this->headers = $headers;
    }

    /**
     * @throws CannotInsertRecord
     */
    public function write()
    {
        $writer = Writer::createFromPath($this->outputPath . '/' . $this->fileName . '', 'w+');
        $writer->insertOne($this->headers);
        $writer->insertAll($this->data);
    }
}