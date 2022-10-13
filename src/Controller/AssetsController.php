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

    #[Route('/asset', name: 'add_assets')]
    public function addAsset(): Response
    {
        return $this->render('assets/asset-add.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/assigned', name: 'assigned_assets')]
    public function assignedAsset(): Response
    {
        return $this->render('assets/assigned-asset-list.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        return $this->render('assets/assigning-asset.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }
}
