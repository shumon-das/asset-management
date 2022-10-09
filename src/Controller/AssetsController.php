<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    #[Route('/assets', name: 'app_assets')]
    public function index(): Response
    {
        return $this->render('assets/asset-list.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }
}
