<?php

declare(strict_types=1);

namespace App\Application\Controller\API;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Domain\Service\PersisterVehicle\PersisterVehicleInterface;
use App\Domain\ValueObject\RequestedVehicle;
use App\Domain\ValueObject\ValidVehicle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final readonly class CreateVehicle
{
    public function __construct(
        private PersisterVehicleInterface $persisterVehicle
    ) {
    }

    public function __invoke(RequestedVehicle $vehicle): JsonResponse
    {
        $persistedVehicle = $this->persisterVehicle->persist($vehicle);

        if ($persistedVehicle instanceof ValidVehicle) {
            throw new ValidationException($persistedVehicle->constraintViolationList);
        }

        return new JsonResponse(data: $persistedVehicle, status: Response::HTTP_CREATED);
    }
}