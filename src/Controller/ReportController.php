<?php

namespace App\Controller;

use App\Repository\Contract\GMVRepositoryInterface;
use Carbon\Carbon;
use League\Csv\Writer;
use Psr\Container\ContainerInterface;
use Twig\Environment;

class ReportController
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
        $vat = .21;

        $data = $this->gmvRepository->getSevenDayTurnoverPerBrand($startDate, $endDate, $vat); // TODO@Gayan: VO?

        $writer = Writer::createFromPath($this->container->get('report_store') . '/file.csv', 'w+');
        $writer->insertOne(['Day', 'Brand Name', 'Turnover Excluding Vat']);
        $writer->insertAll($data);
    }
}