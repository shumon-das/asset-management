<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorsController extends AbstractController
{
    #[Route('/vendors', name: 'app_vendors')]
    public function index(): Response
    {
        return $this->render('vendors/vendors.html.twig', [
            'controller_name' => 'VendorsController',
        ]);
    }
}
