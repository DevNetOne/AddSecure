<?php

namespace App\Domain\Service\UpdaterVehicle;

use App\Domain\Entity\Vehicle;
use App\Domain\ValueObject\PersistedVehicle;
use App\Domain\ValueObject\ValidVehicle;

interface UpdaterVehicleInterface
{
    public function update(Vehicle $vehicle): ValidVehicle|PersistedVehicle;
}