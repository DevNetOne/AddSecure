<?php

namespace App\Domain\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;

interface CollectionVehicleRepositoryInterface
{
    public function getCollectionVehicles(int $page): Paginator;
}