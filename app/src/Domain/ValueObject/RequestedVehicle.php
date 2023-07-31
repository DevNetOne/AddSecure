<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

final class RequestedVehicle
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public string $registrationNumber,
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public readonly string $carModel
    ) {
        $this->registrationNumber = strtoupper($this->registrationNumber);
    }
}