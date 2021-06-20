<?php

namespace App\Service;

use App\Exception\NotFoundHttpException;
use App\Repository\Contract\GMVRepositoryInterface;
use App\Service\Contract\ReportingServiceInterface;
use App\Util\CSVWriter;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\Exception;
use League\Csv\CannotInsertRecord;
use Psr\Container\ContainerInterface;

class ReportingService implements ReportingServiceInterface
{
    private GMVRepositoryInterface $gmvRepository;
    private ContainerInterface $container;

    public function __construct(GMVRepositoryInterface $gmvRepository, ContainerInterface $container)
    {
        $this->gmvRepository = $gmvRepository;
        $this->container = $container;
    }

    public function createTurnoverPerBrandReport(string $startDate, int $duration)
    {
        try {
            $data = $this->gmvRepository->getSevenDayTurnoverPerBrand(
                $startDate,
                $this->getEndDateByDuration($startDate, $duration),
                $this->container->get('vat_percentage')
            ); // TODO@Gayan: VO?
        } catch (NotFoundHttpException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (\Doctrine\DBAL\Exception | Exception $e) {
            // TODO@Gayan:
            throw $e;
        }

        try {
            CSVWriter::configure($data, $this->getFilePath('7-days-turnover-per-brand'), ['Day', 'Brand Name', 'Turnover Excluding Vat'])->write();
        } catch (InvalidArgumentException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (CannotInsertRecord $e) {
            // TODO@Gayan:
            throw $e;
        }
    }

    public function createTurnoverPerDayReport(string $startDate, int $duration)
    {
        try {
            $data = $this->gmvRepository->getSevenDayTurnoverPerDay(
                $startDate,
                $this->getEndDateByDuration($startDate, $duration),
                $this->getVatPercentage()
            ); // TODO@Gayan: VO?
        } catch (NotFoundHttpException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (\Doctrine\DBAL\Exception | Exception $e) {
            // TODO@Gayan:
            throw $e;
        }

        try {
            CSVWriter::configure($data, $this->getFilePath('7-days-turnover-per-day'), ['Day', 'Brand Name', 'Turnover Excluding Vat'])->write();
        } catch (InvalidArgumentException $e) {
            // TODO@Gayan:
            throw $e;
        } catch (CannotInsertRecord $e) {
            // TODO@Gayan:
            throw $e;
        }
    }

    private function getFilePath(string $fileName): string
    {
        return $this->container->get('report_store') . '/' . $fileName . '.csv';
    }

    /**
     * @return mixed
     */
    private function getVatPercentage(): mixed
    {
        $vat = $this->container->get('vat_percentage');
        return $vat;
    }

    /**
     * @param string $startDate
     * @param int $duration
     * @return string
     */
    private function getEndDateByDuration(string $startDate, int $duration): string
    {
        $endDate = Carbon::parse($startDate)->addDays($duration)->toDateString();
        return $endDate;
    }
}