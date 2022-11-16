<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminsController extends AbstractController
{
    #[Route('/ams/admins', name: 'app_admins_location')]
    public function index(): Response
    {
        return $this->render('admins/locations.html.twig', [
            'controller_name' => 'AdminsController',
        ]);
    }

    #[Route('/ams/add', name: 'admins_add_location')]
    public function addLocation(): Response
    {
        return $this->render('admins/add-locations.html.twig', [
            'controller_name' => 'AdminsController',
        ]);
    }
}
