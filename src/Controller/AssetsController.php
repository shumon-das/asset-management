<?php

namespace App\Controller;

use App\Entity\Assets;
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
            ->setIsDeleted(0)
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

    #[Route('/ams/edit-asset/{id}', name: 'edit_asset')]
    public function editAsset(int $id, AssetsRepository $assetsRepository): Response
    {
        $asset = $assetsRepository->find($id);
        $products = $this->productsRepository->findAll();
        $vendors = $this->vendorsRepository->findAll();

        return $this->render('assets/asset-add.html.twig', [
            'data' => [
                'asset' => $this->singleAsset($asset),
                'products' => $products,
                'vendors' => $vendors,
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/update-assets', name: 'app_update_assets')]
    public function updateAssets(Request $request): RedirectResponse
    {
        $request = $request->request;
        $asset = $this->assetsRepository->find($request->get('id'));
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
            ->setUpdatedBy(1)
            ->setUpdatedAt(new DateTimeImmutable());
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
            'purchaseDate' => $asset->getPurchaseDate()->format('d-m-Y'),
            'warrantyExpiryDate' => $asset->getWarrantyExpiryDate()->format('d-m-Y'),
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
}
