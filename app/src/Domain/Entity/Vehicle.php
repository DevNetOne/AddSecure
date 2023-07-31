<?php

namespace App\Domain\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Application\Controller\API\CreateVehicle;
use App\Application\Controller\API\GetCollectionVehicle;
use App\Application\Controller\API\UpdateVehicle;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\ValueObject\RequestedVehicle;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            controller: GetCollectionVehicle::class,
            paginationEnabled: true,
            paginationMaximumItemsPerPage: 2,
            paginationUseOutputWalkers: true,
        ),
        new Post(
            controller: CreateVehicle::class,
            input: RequestedVehicle::class
        ),
        new Patch(
            controller: UpdateVehicle::class
        ),
        new Delete()
    ]
)]
#[ORM\Entity]
class Vehicle
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public string $registrationNumber,
        #[ORM\Column(type: 'string')]
        #[Assert\NotNull]
        #[Assert\NotBlank]
        public string $carModel,
        #[ORM\Column(type: 'datetime')]
        public \DateTimeInterface $createdAt,
        #[ORM\Column(type: 'datetime', nullable: true)]
        public ?\DateTimeInterface $updatedAt = null,
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        public ?int $id = null,
    ) {
    }
}
