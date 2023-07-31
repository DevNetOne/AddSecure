<?php

declare(strict_types=1);

namespace App\Domain\Service\PersisterVehicle;

use App\Domain\Factory\VehicleFactory;
use App\Domain\Repository\PersistVehicleRepositoryInterface;
use App\Domain\ValueObject\PersistedVehicle;
use App\Domain\ValueObject\ValidVehicle;
use App\Domain\ValueObject\RequestedVehicle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class PersisterVehicle implements PersisterVehicleInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private PersistVehicleRepositoryInterface $persistVehicleRepository,
    ) {
    }

    public function persist(RequestedVehicle $vehicle): ValidVehicle|PersistedVehicle
    {
        $validator = $this->validator->validate($vehicle);

        if ($validator->count() > 0) {
            return new ValidVehicle($validator);
        }

        $persistedVehicle =  $this->persistVehicleRepository->persist(
            VehicleFactory::createVehicleFromRequest($vehicle)
        );

        return new PersistedVehicle(
            $persistedVehicle->registrationNumber,
            $persistedVehicle->carModel,
            $persistedVehicle->createdAt->format('Y-m-d H:i')
        );
    }
}