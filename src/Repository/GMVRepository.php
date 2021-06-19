<?php

namespace App\Repository;

use App\Exception\NotFoundHttpException;
use App\Repository\Contract\GMVRepositoryInterface;
use Doctrine\DBAL\Connection;
use League\Csv\Writer;
use Psr\Container\ContainerInterface;
use SplTempFileObject;
use function DI\create;

class GMVRepository implements GMVRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAll()
    {
        $sql = "
            select date as Day, b.name as 'Brand Name', sum(turnover * (1-.21)) as 'Turnover Excluding Vat'
            from gmv
            join brands b on b.id = gmv.brand_id
            where (date between '2018-05-01' and '2018-05-07')
            group by name, date
            order by date
            ";

        $stmt = $this->connection->prepare($sql);
//        $stmt->bindValue("name", $name);
        $resultSet = $stmt->executeQuery();


//        $writer->insertAll(new ArrayIterator($records));
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function getSevenDayTurnoverPerBrand(string $startDate, string $endDate, float $vat): array
    {

        $sql = "
            select date as Day, b.name as 'Brand Name', sum(turnover * :vat_deduction) as 'Turnover Excluding Vat'
            from gmv
            join brands b on b.id = gmv.brand_id
            where (date between :start and :end)
            group by name, date
            order by date
            ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':start', $startDate);
        $stmt->bindValue(':end', $endDate);
        $stmt->bindValue(':vat_deduction', 1- $vat);

        $resultSet = $stmt->executeQuery();
        $data = $resultSet->fetchAllAssociative();

        if (count($data) === 0) {
            throw new NotFoundHttpException('No data found for given time period');
        }

        return $data;
    }
}