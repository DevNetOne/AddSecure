<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final readonly class ValidVehicle
{
    public function __construct(
        public ConstraintViolationListInterface $constraintViolationList
    ) {
    }
}