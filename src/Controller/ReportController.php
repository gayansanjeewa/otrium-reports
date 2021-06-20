<?php

namespace App\Controller;

use App\Exception\NotFoundHttpException;
use App\Service\Contract\ReportingServiceInterface;
use Exception;

final class ReportController
{
    private ReportingServiceInterface $reportingService;

    public function __construct(ReportingServiceInterface $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function __invoke(array $params): string
    {
        $startDate = $params['startDate'];
        $errors = [];
        if (empty($startDate)) {
            $errors['startDate'] = ['Start date is required.'];
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
            return json_encode($data);
        }

        $duration = 6;
        $result = [];

        try {
            $result[] = $this->reportingService->createTurnoverPerBrandReport($startDate, $duration);
            $result[] = $this->reportingService->createTurnoverPerDayReport($startDate, $duration);
        } catch (NotFoundHttpException | Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            return json_encode($data);
        }

        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['errors'] = [];
        $data['data'] = $result;
        return json_encode($data);
    }
}