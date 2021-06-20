<?php

namespace App\Controller;

use App\Exception\NotFoundHttpException;
use App\Exception\UnprocessableEntityHttpException;
use App\Service\Contract\ReportingServiceInterface;
use Doctrine\DBAL\Driver\Exception;

final class ReportController
{
    private ReportingServiceInterface $reportingService;

    public function __construct(ReportingServiceInterface $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function __invoke($params)
    {
        $startDate = $params['startDate'];
        if (empty($startDate)) {
            throw new UnprocessableEntityHttpException('Start date is required.');
        }

        $duration = 6;

        try {
            $this->reportingService->createTurnoverPerBrandReport($startDate, $duration);
            $this->reportingService->createTurnoverPerDayReport($startDate, $duration);
        } catch ( NotFoundHttpException $e) {
            throw $e;
        } catch ( Exception $e) {
            // TODO@Gayan:
            throw $e;
        }
    }
}