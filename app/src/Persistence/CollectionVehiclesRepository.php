<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Domain\Entity\Vehicle;
use App\Domain\Repository\CollectionVehicleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Doctrine\Orm\Paginator;
use Doctrine\Common\Collections\Criteria;

final readonly class CollectionVehiclesRepository implements CollectionVehicleRepositoryInterface
{
    private const ITEMS_PER_PAGE = 2;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function getCollectionVehicles(int $page = 1): Paginator
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('v.id')
            ->addSelect('v.registrationNumber')
            ->addSelect('v.carModel')
            ->addSelect('DATE_FORMAT(v.createdAt, \'%Y-%m-%d %H:%i\') AS createdAt')
            ->addSelect('DATE_FORMAT(v.updatedAt, \'%Y-%m-%d %H:%i\') AS updatedAt')
            ->from(Vehicle::class, 'v');

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);

        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $doctrinePaginator->setUseOutputWalkers(false);

        return new Paginator($doctrinePaginator);
    }
}