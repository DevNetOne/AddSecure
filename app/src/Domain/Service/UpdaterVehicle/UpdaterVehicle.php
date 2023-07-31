<?php

declare(strict_types=1);

namespace App\Domain\Service\UpdaterVehicle;

use App\Domain\Entity\Vehicle;
use App\Domain\Repository\PersistVehicleRepositoryInterface;
use App\Domain\ValueObject\PersistedVehicle;
use App\Domain\ValueObject\ValidVehicle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UpdaterVehicle implements UpdaterVehicleInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private PersistVehicleRepositoryInterface $persistVehicleRepository,
    ) {
    }

    public function update(Vehicle $vehicle): ValidVehicle|PersistedVehicle
    {
        $validator = $this->validator->validate($vehicle);

        if ($validator->count() > 0) {
            return new ValidVehicle($validator);
        }

        $vehicle->updatedAt = new \DateTime();
        $this->persistVehicleRepository->persist($vehicle);

        return new PersistedVehicle(
            $vehicle->registrationNumber,
            $vehicle->carModel,
            $vehicle->createdAt->format('Y-m-d H:i'),
            $vehicle->updatedAt->format('Y-m-d H:i')
        );
    }
}