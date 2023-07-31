<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Vehicle;

interface PersistVehicleRepositoryInterface
{
    public function persist(Vehicle $vehicle): Vehicle;
}