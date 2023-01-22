<?php

namespace App\Controller;

use App\Common\Product\ProductDataTrait;
use App\Entity\Methods\ProductMethodsTrait;
use App\Entity\Products;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractApiController
{
    use ProductDataTrait;
    use ProductMethodsTrait;

    #[Route('/ams/products', name: 'app_products')]
    public function products(): Response
    {
        $products = $this->productsRepository->findBy(['isDeleted' => 0]);
        foreach ($products as $key => $row) {
            $products[$key] = $this->productData($row);
        }

        return $this->render('products/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/ams/add-product', name: 'app_add_product')]
    public function addProduct(): Response
    {
        return $this->render('products/add-product.html.twig');
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/save-products', name: 'app_save_products')]
    public function saveProducts(Request $request): RedirectResponse
    {
        $id =  $request->request->get('id');
        $result = $id
            ? $this->productMethods($this->productsRepository->find($id), $request, true)
            : $this->productMethods(new Products(), $request);

        $this->addFlash('message', $result);
        return new RedirectResponse('add-product');
    }

    #[Route('/ams/edit-product/{id}', name: 'edit_product')]
    public function editProduct(int $id): Response
    {
        $product = $this->productsRepository->find($id);
        return $this->render('products/add-product.html.twig', [
            'product' => $this->productData($product),
        ]);
    }

    #[Route('/ams/view-product/{id}', name: 'view_product')]
    public function viewProduct(int $id): Response
    {
        $product = $this->productsRepository->find($id);
        return $this->render('products/view-product.html.twig', [
            'product' => $this->productData($product),
            'createdBy' => ucwords($this->employeeRepository->find($product->getCreatedBy())->getName()),
        ]);
    }

    #[Route('/ams/delete-product/{id}', name: 'delete_product')]
    public function deleteProduct(int $id, Request $request): Response
    {
        $result = $this->deleteItem($this->productsRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/delete-product-permanently/{id}', name: 'delete_product_permanently')]
    public function deletePermanently($id, Request $request): Response
    {
        $result = $this->deleteItem($this->productsRepository, $id, true);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }
}
