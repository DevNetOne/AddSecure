<?php

namespace App\Domain\Service\PersisterVehicle;

use App\Domain\ValueObject\PersistedVehicle;
use App\Domain\ValueObject\ValidVehicle;
use App\Domain\ValueObject\RequestedVehicle;

interface PersisterVehicleInterface
{
    public function persist(RequestedVehicle $vehicle): ValidVehicle|PersistedVehicle;
}