<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function products(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();

        return $this->render('products/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/add-product', name: 'app_add_product')]
    public function addProducts(): Response
    {
        return $this->render('products/add-product.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/save-products', name: 'app_save_products')]
    public function saveProducts(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $request = $request->request;
        $product = new Products();
        $product
            ->setCategory($request->get('product-category'))
            ->setType($request->get('product-type'))
            ->setName($request->get('product-name'))
            ->setManufacturer($request->get('manufacturer'))
            ->setDescription($request->get('description'))
            ->setStatus(true)
            ->setIsDeleted(null)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null)
            ->setCreatedBy(1)
            ->setUpdatedBy(null)
            ->setDeletedBy(null)
        ;
        $entityManager->persist($product);
        $entityManager->flush();

        return new RedirectResponse('products');
    }
}
