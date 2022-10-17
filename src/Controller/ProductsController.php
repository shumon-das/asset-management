<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function products(): Response
    {
        return $this->render('products/products.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/add-product', name: 'app_add_product')]
    public function addProducts(): Response
    {
        return $this->render('products/add-product.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
