<?php

namespace App\Controller;

use App\Entity\Assets;
use App\Entity\AssigningAssets;
use App\Repository\AssetsRepository;
use App\Repository\AssigningAssetsRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    private AssetsRepository $assetsRepository;
    private VendorsRepository $vendorsRepository;
    private ProductsRepository $productsRepository;
    private EntityManagerInterface $entityManager;
    private AssigningAssetsRepository $assigningAssetsRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(
        AssetsRepository $assetsRepository,
        VendorsRepository $vendorsRepository,
        ProductsRepository $productsRepository,
        EntityManagerInterface $entityManager,
        AssigningAssetsRepository $assigningAssetsRepository,
        EmployeeRepository $employeeRepository
    ){
        $this->assetsRepository = $assetsRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
        $this->assigningAssetsRepository = $assigningAssetsRepository;
        $this->employeeRepository = $employeeRepository;
    }

    #[Route('/ams/assets', name: 'app_assets')]
    public function assets(): Response
    {
        $assets = $this->assetsRepository->findBy(['isDeleted' => 0]);
        $data = [];
        foreach ($assets as $asset) {
            $data[$asset->getId()] = $this->assetsListData($asset);
        }

        return $this->render('assets/asset-list.html.twig', [
            'assets' => $data,
        ]);
    }

    #[Route('/ams/asset', name: 'add_assets')]
    public function addAsset(): Response
    {
        $products = $this->productsRepository->findAll();
        $vendors = $this->vendorsRepository->findAll();

        return $this->render('assets/asset-add.html.twig', [
            'data' => [
                'products' => $products,
                'vendors' => $vendors,
            ],
        ]);
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

    /**
     * @throws Exception
     */
    #[Route('/ams/save-assets', name: 'app_save_assets')]
    public function saveAssets(Request $request, ): RedirectResponse
    {
        $request = $request->request;
        $asset = new Assets();
        $asset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product'))
            ->setVendor($request->get('vendor'))
            ->setAssetName($request->get('asset-name'))
            ->setSeriulNumber($request->get('serial-number'))
            ->setPrice($request->get('price'))
            ->setDescriptionType($request->get('description-type'))
            ->setLocation($request->get('location'))
            ->setPurchaseDate(new DateTimeImmutable($request->get('purchase-date')))
            ->setWarrantyExpiryDate(new DateTimeImmutable($request->get('warranty-expiry-date')))
            ->setPurchaseType($request->get('purchase-type'))
            ->setDescription($request->get('description'))
            ->setUsefulLife($request->get('useful-life'))
            ->setResidualValue($request->get('residual-value'))
            ->setRate($request->get('rate'))
            ->setStatus(true)
            ->setCreatedBy(1)
            ->setUpdatedBy(null)
            ->setDeletedBy(null)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null);
        $this->entityManager->persist($asset);
        $this->entityManager->flush();

        return new RedirectResponse('assets');
    }

    #[Route('/ams/view-asset/{id}', name: 'view_asset')]
    public function viewAsset(int $id, AssetsRepository $assetsRepository): Response
    {
        $asset = $assetsRepository->find($id);

        return $this->render('assets/view-asset.html.twig', [
            'asset' => $this->singleAsset($asset),
            'title' => $asset->getAssetName(),
        ]);
    }

    #[Route('/ams/delete-asset/{id}', name: 'delete_asset')]
    public function deleteAsset(int $id, Request $request): Response
    {
        $product = $this->assetsRepository->find($id);
        $product->setIsDeleted(1);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    private function assetsListData(Assets $asset): array
    {
        $vendor = $this->vendorsRepository->find($asset->getVendor());
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $vendor->getVendorName(),
            'assetName' => $asset->getAssetName(),
            'serialNumber' => $asset->getSeriulNumber(),
        ];
    }

    private function singleAsset(?Assets $asset): array
    {
        $vendor = $this->vendorsRepository->find($asset->getVendor());
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $vendor->getVendorName(),
            'assetName' => $asset->getAssetName(),
            'seriulNumber' => $asset->getSeriulNumber(),
            'price' => $asset->getPrice(),
            'descriptionType' => $asset->getDescriptionType(),
            'location' => $asset->getLocation(),
            'purchaseDate' => $asset->getPurchaseDate()->format('Y-M-d'),
            'warrantyExpiryDate' => $asset->getWarrantyExpiryDate()->format('Y-M-d'),
            'purchaseType' => $asset->getPurchaseType(),
            'description' => $asset->getDescription(),
            'usefulLife' => $asset->getUsefulLife(),
            'residualValue' => $asset->getResidualValue(),
            'rate' => $asset->getRate(),
            'createdAt' => $asset->getCreatedAt()->format('Y-M-d'),
            'createdBy' => $asset->getCreatedBy(),
            'status' => $asset->isStatus()
        ];
    }

    private function assignedData(?AssigningAssets $assignedProduct): array
    {
        return [
            'id' => $assignedProduct->getId(),
            'assetName' => $assignedProduct->getAssetName(),
            'department' => $assignedProduct->getDepartment(),
            'location' => $assignedProduct->getLocation(),
            'assigned' => $assignedProduct->getAssignTo(),
            'currentState' => 'current state',
            'status' => $assignedProduct->isStatus(),
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
