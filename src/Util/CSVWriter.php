<?php

namespace App\Util;

use InvalidArgumentException;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Webmozart\Assert\Assert;

class CSVWriter
{
    private array $data;
    private string $filePath;
    private array $headers;

    private function __construct(array $data, string $filePath, array $headers)
    {
        $this->data = $data;
        $this->filePath = $filePath;
        $this->headers = $headers;
    }

    /**
     * @param array $data
     * @param string $filePath
     * @param array $headers
     * @return CSVWriter
     * @throws InvalidArgumentException
     */
    public static function configure(array $data, string $filePath, array $headers): CSVWriter
    {
        Assert::notEmpty($data);
        Assert::notEmpty($filePath);
        Assert::notEmpty($headers);

        return new static($data, $filePath, $headers);
    }

    /**
     * @throws CannotInsertRecord
     */
    public function write()
    {
        $writer = Writer::createFromPath($this->filePath . '', 'w+');
        $writer->insertOne($this->headers);
        $writer->insertAll($this->data);
    }
}