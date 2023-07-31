<?php

declare(strict_types=1);

namespace App\Persistence;

use App\Domain\Entity\Vehicle;
use App\Domain\Repository\PersistVehicleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PersistVehicleRepository implements PersistVehicleRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function persist(Vehicle $vehicle): Vehicle
    {
        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return $vehicle;
    }
}