<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

final readonly class PersistedVehicle
{
    public function __construct(
        public string $registrationNumber,
        public string $carModel,
        public string $createdAt,
        public ?string $updatedAt = null
    ) {
    }
}