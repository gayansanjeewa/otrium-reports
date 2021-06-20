<?php

namespace App\Repository\Contract;

interface GMVRepositoryInterface
{
    public function getSevenDayTurnoverPerBrand(string $startDate, string $endDate, float $vat): array;

    public function getSevenDayTurnoverPerDay(string $startDate, string $endDate, float $vat): array;
}