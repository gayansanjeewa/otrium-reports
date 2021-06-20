<?php

namespace App\Controller;

use App\Exception\NotFoundHttpException;
use App\Repository\Contract\GMVRepositoryInterface;
use App\Util\CSVWriter;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Exception;
use InvalidArgumentException;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Psr\Container\ContainerInterface;

final class ReportController
{
    private GMVRepositoryInterface $gmvRepository;
    private ContainerInterface $container;

    public function __construct(GMVRepositoryInterface $gmvRepository, ContainerInterface $container)
    {
        $this->gmvRepository = $gmvRepository;
        $this->container = $container;
    }

    public function __invoke($params)
    {
        $startDate = $params['startDate'];
        $endDate = Carbon::parse($startDate)->addDays(6)->toDateString();
        $vat = $this->container->get('vat_percentage');

        try {
            $turnoverPerBrandData = $this->gmvRepository->getSevenDayTurnoverPerBrand($startDate, $endDate, $vat); // TODO@Gayan: VO?
            $turnoverPerDay = $this->gmvRepository->getSevenDayTurnoverPerDay($startDate, $endDate, $vat); // TODO@Gayan: VO?
        } catch (NotFoundHttpException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (\Doctrine\DBAL\Exception | Exception $e) {
            // TODO@Gayan:
            throw $e;
        }

        try {
            CSVWriter::configure($turnoverPerBrandData, $this->getFilePath('7-days-turnover-per-brand'), ['Day', 'Brand Name', 'Turnover Excluding Vat'])->write();

            CSVWriter::configure($turnoverPerDay, $this->getFilePath('7-days-turnover-per-day'), ['Day', 'Brand Name', 'Turnover Excluding Vat'])->write();
        } catch (InvalidArgumentException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (CannotInsertRecord $e) {
            // TODO@Gayan:
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param string $fileName
     * @param array $headers
     * @throws \League\Csv\CannotInsertRecord
     */
    private function csvWriter(array $data, string $fileName, array $headers): void
    {
        $writer = Writer::createFromPath($this->container->get('report_store') . '/' . $fileName . '', 'w+');
        $writer->insertOne($headers);
        $writer->insertAll($data);
    }

    private function getFilePath(string $fileName): string
    {
        return $this->container->get('report_store') . '/' . $fileName . '.csv';
    }
}