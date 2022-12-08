<?php

namespace App\Controller;

use App\Common\Product\ProductDataTrait;
use App\Entity\Employee;
use App\Entity\Products;
use App\Repository\EmployeeRepository;
use App\Repository\ProductsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProductsController extends AbstractController
{
    private ProductsRepository $productsRepository;
    private EntityManagerInterface $entityManager;
    private EmployeeRepository $employeeRepository;
    private Security $security;
    use ProductDataTrait;

    public function __construct(
        ProductsRepository $productsRepository,
        EntityManagerInterface $entityManager,
        EmployeeRepository $employeeRepository,
        Security $security
    ){
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
        $this->security = $security;
    }

    #[Route('/ams/products', name: 'app_products')]
    public function products(): Response
    {
        $products = $this->productsRepository->findBy(['isDeleted' => 0]);
        foreach ($products as $key => $row) {
            $products[$key] = $this->productData($row, $this->employeeRepository);
        }

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
    public function saveProducts(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $product = new Products();
        $product
            ->setCategory($request->get('product-category'))
            ->setType($request->get('product-type'))
            ->setName($request->get('product-name'))
            ->setManufacturer($request->get('manufacturer'))
            ->setDescription($request->get('description'))
            ->setStatus(true)
            ->setIsDeleted(0)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null)
            ->setCreatedBy($user->getId())
            ->setUpdatedBy(null)
            ->setDeletedBy(null);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return new RedirectResponse('products');
    }

    #[Route('/ams/edit-product/{id}', name: 'edit_product')]
    public function editProduct(int $id): Response
    {
        $product = $this->productsRepository->find($id);

        return $this->render('products/add-product.html.twig', [
            'product' => $this->productData($product, $this->employeeRepository),
        ]);
    }

    #[Route('/ams/update-product', name: 'app_product_update')]
    public function updateVendor(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $product = $this->productsRepository->find($request->get('id'));
        $product
            ->setCategory($request->get('product-category'))
            ->setType($request->get('product-type'))
            ->setName($request->get('product-name'))
            ->setManufacturer($request->get('manufacturer'))
            ->setDescription($request->get('description'))
            ->setUpdatedAt(new DateTimeImmutable())
            ->setUpdatedBy($user->getId());
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return new RedirectResponse('products');
    }

    #[Route('/ams/view-product/{id}', name: 'view_product')]
    public function viewProduct(int $id): Response
    {
        $product = $this->productsRepository->find($id);

        return $this->render('products/view-product.html.twig', [
            'product' => $this->productData($product, $this->employeeRepository),
            'createdBy' => ucwords($this->employeeRepository->find($product->getCreatedBy())->getName()),
        ]);
    }

    #[Route('/ams/delete-product/{id}', name: 'delete_product')]
    public function deleteProduct(int $id, Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $product = $this->productsRepository->find($id);
        $product->setIsDeleted(1)
                ->setDeletedBy($user->getId())
                ->setDeletedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
