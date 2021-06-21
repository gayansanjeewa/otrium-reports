<?php

namespace Tests\Unit\Service;

use App\Repository\Contract\GMVRepositoryInterface;
use App\Service\ReportingService;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ReportingServiceTest extends TestCase
{
    private MockObject|GMVRepositoryInterface $gmvRepository;
    private mixed $vat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gmvRepository = $this->createMock(GMVRepositoryInterface::class);
        $this->vat = $this->container->get('vat_percentage');
    }

    /**
    * @test
    */
    public function createTurnoverPerBrandReport_withCorrectStartDateAndDuration_createCSVAndReturnFileName()
    {
        $data = [
            [
                'day' => '2018-05-01 00:00:00',
                'brand_name' => 'O-Brand',
                'turnover_excluding_vat' => 8400.2754
            ]
        ];
        $startDate = '2018-05-01';
        $duration = 6;
        $endDate = Carbon::parse($startDate)->addDays($duration)->toDateString();
        $expectedFileName = '7-days-turnover-per-brand-' . $startDate . '.csv';

        $this->gmvRepository
            ->expects($this->once())
            ->method('getSevenDayTurnoverPerBrand')
            ->with($startDate, $endDate, $this->vat)
            ->willReturn($data);

        $filename = (new ReportingService($this->gmvRepository, $this->container))->createTurnoverPerBrandReport($startDate, $duration);

        $this->assertSame($expectedFileName, $filename);
    }

    /**
    * @test
    */
    public function createTurnoverPerDayReport_withCorrectStartDateAndDuration_createCSVAndReturnFileName()
    {
        $data = [
            [
                'day' => '2018-05-01 00:00:00',
                'turnover_excluding_vat' => 8400.2754
            ]
        ];
        $startDate = '2018-05-01';
        $duration = 6;
        $endDate = Carbon::parse($startDate)->addDays($duration)->toDateString();
        $expectedFileName = '7-days-turnover-per-day-' . $startDate . '.csv';

        $this->gmvRepository
            ->expects($this->once())
            ->method('getSevenDayTurnoverPerDay')
            ->with($startDate, $endDate, $this->vat)
            ->willReturn($data);

        $filename = (new ReportingService($this->gmvRepository, $this->container))->createTurnoverPerDayReport($startDate, $duration);

        $this->assertSame($expectedFileName, $filename);
    }

}