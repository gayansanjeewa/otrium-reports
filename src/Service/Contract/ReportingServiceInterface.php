<?php

namespace App\Service\Contract;

interface ReportingServiceInterface
{
    public function createTurnoverPerBrandReport(string $startDate, int $duration);

    public function createTurnoverPerDayReport(string $startDate, int $duration);
}