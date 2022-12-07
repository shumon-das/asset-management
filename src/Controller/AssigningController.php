<?php

namespace App\Controller;

use App\Entity\AssigningAssets;
use App\Repository\AssigningAssetsRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssigningController extends AbstractController
{
    private AssigningAssetsRepository $assigningAssetsRepository;
    private ProductsRepository $productsRepository;
    private VendorsRepository $vendorsRepository;
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AssigningAssetsRepository $assigningAssetsRepository,
        ProductsRepository $productsRepository,
        VendorsRepository $vendorsRepository,
        EmployeeRepository $employeeRepository,
        EntityManagerInterface $entityManager
    ){
        $this->assigningAssetsRepository = $assigningAssetsRepository;
        $this->productsRepository = $productsRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->employeeRepository = $employeeRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/ams/assigned', name: 'assigned_assets')]
    public function assignedAsset(AssigningAssetsRepository $assigningAssetsRepository): Response
    {
        $assignedProduct = $assigningAssetsRepository->findBy(['isDeleted' => 0]);
        $data = [];
        foreach ($assignedProduct as  $product) {
            $data[$product->getId()] = $this->assignedData($product);
        }

        return $this->render('assets/assigned-asset-list.html.twig', [
            'assets' => $data
        ]);
    }

    #[Route('/ams/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        $products = $this->productsRepository->findAll();
        $vendors = $this->vendorsRepository->findAll();
        $employee = $this->employeeRepository->findAll();

        return $this->render('assets/assigning-asset.html.twig', [
                'products' => $products,
                'vendors' => $vendors,
                'users' => $employee,
        ]);
    }

    #[Route('/ams/view-assigned/{id}', name: 'view_assigned_asset')]
    public function viewAssignedAsset(int $id): Response
    {
        $asset = $this->assigningAssetsRepository->find($id);
        return $this->render('assets/view-assigned.html.twig', [
                'asset' => $this->assignedAssetData($asset)
        ]);
    }

    #[Route('/ams/delete-assigned/{id}', name: 'delete_assigned')]
    public function deleteAssigned(int $id, Request $request): Response
    {
        $assignedAsset = $this->assigningAssetsRepository->find($id);
        $assignedAsset->setIsDeleted(1);
        $this->entityManager->persist($assignedAsset);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/save-assign-asset', name: 'save_assign_asset')]
    public function saveAssignAsset(Request $request): RedirectResponse
    {
        $request = $request->request;
        $assignAsset = new AssigningAssets();
        $assignAsset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product-name'))
            ->setVendor($request->get('vendor'))
            ->setLocation($request->get('location'))
            ->setAssetName($request->get('asset-name'))
            ->setDepartment($request->get('department'))
            ->setAssignTo($request->get('assign-to'))
            ->setDescription($request->get('description'))
//            ->setAssignComponent($request->get(''))
            ->setIsDeleted(0)
            ->setCreatedBy(1)
            ->setUpdatedBy(null)
            ->setDeletedBy(null)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null)
            ->setStatus(true)
        ;
        $this->entityManager->persist($assignAsset);
        $this->entityManager->flush();

        return new RedirectResponse('assigned');
    }

    #[Route('/ams/edit/assigning/{id}', name: 'edit_assigned_asset')]
    public function editAssigningAsset(int $id): Response
    {
        $assignedAsset = $this->assigningAssetsRepository->find($id);
        $products = $this->productsRepository->findAll();
        $vendors = $this->vendorsRepository->findAll();
        $employee = $this->employeeRepository->findAll();

        return $this->render('assets/assigning-asset.html.twig', [
                'assignedAsset' => $assignedAsset,
                'products' => $products,
                'vendors' => $vendors,
                'users' => $employee,
        ]);
    }

        #[Route('/ams/update-assigned-asset', name: 'update_assigned_asset')]
    public function updateAssignedAsset(Request $request): RedirectResponse
    {
        $request = $request->request;
        $assignAsset = $this->assigningAssetsRepository->find($request->get('id'));
        $assignAsset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product-name'))
            ->setVendor($request->get('vendor'))
            ->setLocation($request->get('location'))
            ->setAssetName($request->get('asset-name'))
            ->setDepartment($request->get('department'))
            ->setAssignTo($request->get('assign-to'))
            ->setDescription($request->get('description'))
            ->setUpdatedBy(1)
            ->setUpdatedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($assignAsset);
        $this->entityManager->flush();

        return new RedirectResponse('assigned');
    }

    private function assignedData(?AssigningAssets $assignedProduct): array
    {
        return [
            'id' => $assignedProduct->getId(),
            'assetName' => $assignedProduct->getAssetName(),
            'department' => $assignedProduct->getDepartment(),
            'location' => $assignedProduct->getLocation(),
            'assigned' => $this->employeeRepository->find($assignedProduct->getAssignTo())?->getName(),
            'vendor' => $this->vendorsRepository->find($assignedProduct->getVendor())?->getVendorName(),
            'currentState' => 'current state',
            'status' => $assignedProduct->isStatus() ? 'Assigned' : 'Not Assigned',
        ];
    }
    private function assignedAssetData(?AssigningAssets $asset): array
    {
        $vendor = $this->vendorsRepository->find($asset->getVendor());
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $vendor->getVendorName(),
            'location' => $asset->getLocation(),
            'assetName' => $asset->getAssetName(),
            'department' => $asset->getDepartment(),
            'assigned' => $asset->getAssignTo(),
            'description' => $asset->getDescription(),
            'createdBy' => $asset->getCreatedBy(),
            'status' => $asset->isStatus(),
            'currentState' => 'current state',
            'createdAt' => $asset->getCreatedAt()->format('Y-M-d'),
        ];
    }
}