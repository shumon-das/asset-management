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
    private ProductsRepository $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    #[Route('/ams/products', name: 'app_products')]
    public function products(): Response
    {
        $products = $this->productsRepository->findAll();

        return $this->render('products/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/ams/add-product', name: 'app_add_product')]
    public function addProduct(): Response
    {
        return $this->render('products/add-product.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/ams/save-products', name: 'app_save_products')]
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

    #[Route('/ams/view-product/{id}', name: 'view_product')]
    public function viewProduct(int $id): Response
    {
        $product = $this->productsRepository->find($id);

        return $this->render('products/view-product.html.twig', [
            'product' => $this->productData($product),
        ]);
    }

    private function productData(?Products $product): array
    {
        return [
            'id' => $product->getId(),
            'category' => $product->getCategory(),
            'type' => $product->getType(),
            'name' => $product->getName(),
            'manufacturer' => $product->getManufacturer(),
            'description' => $product->getDescription(),
            'status' => true === $product->isStatus() ? 'active' : 'not active',
            'createdAt' => $product->getCreatedAt()->format('Y-M-d'),
            'createdBy' => $product->getCreatedBy(),
        ];
    }
}
