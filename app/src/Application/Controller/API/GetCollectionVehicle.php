<?php

declare(strict_types=1);

namespace App\Application\Controller\API;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Domain\Repository\CollectionVehicleRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final readonly class GetCollectionVehicle
{
    public function __construct(
        private CollectionVehicleRepositoryInterface $collectionVehiclesRepository
    ) {
    }

    public function __invoke(Request $request): Paginator
    {
        $page = (int) $request->query->get('page', 1);

        return $this->collectionVehiclesRepository->getCollectionVehicles($page);
    }
}