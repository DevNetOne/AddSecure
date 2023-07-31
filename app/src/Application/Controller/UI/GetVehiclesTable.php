<?php

declare(strict_types=1);

namespace App\Application\Controller\UI;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'vehicles', name: 'vehicles', methods: ['GET'])]
final class GetVehiclesTable extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('base.html.twig');
    }
}