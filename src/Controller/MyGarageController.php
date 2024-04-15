<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyGarageController extends AbstractController
{
    #[Route('/my/garage', name: 'app_my_garage')]
    public function index(): Response
    {
        return $this->render('myGarage/index.html.twig', [
            'controller_name' => 'MyGarageController',
        ]);
    }
}
