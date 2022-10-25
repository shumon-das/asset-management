<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadProductsController extends AbstractController
{
    #[Route('/upload/products', name: 'app_upload_products')]
    public function index(): Response
    {
        return $this->render('upload_products/index.html.twig', [
            'controller_name' => 'UploadProductsController',
        ]);
    }
}
