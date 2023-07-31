<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Vehicle;
use App\Domain\ValueObject\RequestedVehicle;

final class VehicleFactory
{
    public static function createVehicleFromRequest(RequestedVehicle $requestedVehicle): Vehicle
    {
        return new Vehicle(
            registrationNumber: $requestedVehicle->registrationNumber,
            carModel: $requestedVehicle->carModel,
            createdAt: new \DateTime()
        );
    }
}