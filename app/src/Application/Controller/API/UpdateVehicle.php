<?php

declare(strict_types=1);

namespace App\Application\Controller\API;

use App\Domain\Entity\Vehicle;
use App\Domain\Service\UpdaterVehicle\UpdaterVehicleInterface;
use App\Domain\ValueObject\ValidVehicle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;

#[AsController]
final readonly class UpdateVehicle
{
    public function __construct(
        private UpdaterVehicleInterface $updaterVehicle
    ) {
    }

    public function __invoke(Vehicle $vehicle): JsonResponse
    {
        $updatedVehicle = $this->updaterVehicle->update($vehicle);

        if ($updatedVehicle instanceof ValidVehicle) {
            throw new ValidationException($updatedVehicle->constraintViolationList);
        }

        return new JsonResponse(data: $updatedVehicle, status: Response::HTTP_OK);
    }
}