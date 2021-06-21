<?php

namespace App\Controller;

use App\Exception\NotFoundHttpException;
use App\Service\Contract\ReportingServiceInterface;
use EasyCSRF\Exceptions\InvalidCsrfTokenException;
use Exception;

final class ReportController extends BaseController
{
    private ReportingServiceInterface $reportingService;

    public function __construct(ReportingServiceInterface $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function __invoke(array $params)
    {

        try {
            $this->validateCSRFToken($params);
        } catch (InvalidCsrfTokenException $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            echo json_encode($data);
            return;
        }

        $startDate = $params['startDate'];
        $errors = [];
        if (empty($startDate)) {
            $errors['startDate'] = 'Start date is required.';
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
            echo json_encode($data);
            return;
        }

        $duration = 6;
        $reports = [];

        try {
            $reports['turnoverPerBrandReport'] = $this->reportingService->createTurnoverPerBrandReport($startDate, $duration);
            $reports['turnoverPerDayReport'] = $this->reportingService->createTurnoverPerDayReport($startDate, $duration);
        } catch (NotFoundHttpException | Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            echo json_encode($data);
            return;
        }

        $data['success'] = true;
        $data['message'] = 'Success!';
        $data['errors'] = [];
        $data['reports'] = $reports;
        echo json_encode($data);
    }
}