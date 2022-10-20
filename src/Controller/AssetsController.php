<?php

namespace App\Controller;

use App\Entity\Assets;
use App\Entity\AssigningAssets;
use App\Repository\AssetsRepository;
use App\Repository\AssigningAssetsRepository;
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

    public function __construct(
        AssetsRepository $assetsRepository,
        VendorsRepository $vendorsRepository,
        ProductsRepository $productsRepository,
        EntityManagerInterface $entityManager
    ){
        $this->assetsRepository = $assetsRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->productsRepository = $productsRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/assets', name: 'app_assets')]
    public function assets(): Response
    {
        $assets = $this->assetsRepository->findAll();
        $data = [];
        foreach ($assets as $asset) {
            $data[$asset->getId()] = $this->assetsListData($asset);
        }

        return $this->render('assets/asset-list.html.twig', [
            'assets' => $data,
        ]);
    }

    #[Route('/asset', name: 'add_assets')]
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

    #[Route('/assigned', name: 'assigned_assets')]
    public function assignedAsset(AssigningAssetsRepository $assigningAssetsRepository): Response
    {
        $assignedProduct = $assigningAssetsRepository->findAll();
        $data = [];
        foreach ($assignedProduct as  $product) {
            $data[$product->getId()] = $this->assignedData($product);
        }
        
        return $this->render('assets/assigned-asset-list.html.twig', [
            'assets' => $data
        ]);
    }

    #[Route('/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        $products = $this->productsRepository->findAll();
        $vendors = $this->vendorsRepository->findAll();
        $users = [
            0 => ['id' => 1, 'name' => 'mono ranjan'],
            1 => ['id' => 2, 'name' => 'shumon babu'],
            2 => ['id' => 3, 'name' => 'mauro sebastianelli'],
            3 => ['id' => 4, 'name' => 'MR Mauro'],
        ];
        return $this->render('assets/assigning-asset.html.twig', [
                'products' => $products,
                'vendors' => $vendors,
                'users' => $users,
        ]);
    }

    #[Route('/save-assign-asset', name: 'save_assign_asset')]
    public function saveAssignAsset(Request $request): RedirectResponse
    {
//        dd($request);
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

        return new RedirectResponse('assigning');
    }

    /**
     * @throws Exception
     */
    #[Route('/save-assets', name: 'app_save_assets')]
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

    #[Route('/view-asset/{id}', name: 'view_asset')]
    public function viewAsset(int $id, AssetsRepository $assetsRepository): Response
    {
        $asset = $assetsRepository->find($id);

        return $this->render('assets/view-asset.html.twig', [
            'asset' => $this->singleAsset($asset),
            'title' => $asset->getAssetName(),
        ]);
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
}
