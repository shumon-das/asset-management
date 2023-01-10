<?php

namespace App\Controller;

use App\Common\Asset\AssetListDataTrait;
use App\Common\NamesTrait;
use App\Entity\Assets;
use App\Entity\Common\AssetMethodsTrait;
use App\Repository\AssetsRepository;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractApiController
{
    use AssetListDataTrait;
    use AssetMethodsTrait;
    use NamesTrait;

    #[Route('/ams/assets', name: 'app_assets')]
    public function assets(): Response
    {
        $assets = $this->assetsRepository->findBy(['isDeleted' => 0]);
        $assignedAssetIds = array_column($this->assigningAssetsRepository->findIds(), 'id');
        $data = [];
        foreach ($assets as $asset) {
            $data[$asset->getId()] = $this->assetsListData($assignedAssetIds, $asset);
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
    public function saveAssets(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $id
            ? $this->assetMethods($this->assetsRepository->find($id), $request, true)
            :$this->assetMethods(new Assets(), $request);
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
        $this->deleteItem($this->assetsRepository, $id);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/delete-asset-permanently/{id}', name: 'delete_asset_permanently')]
    public function deletePermanently($id, Request $request): Response
    {
        $this->deleteItem($this->assetsRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }

    private function singleAsset(?Assets $asset): array
    {
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $asset->getVendor() ? $this->allEntityIdsAndNames()['vendorsIds'][$asset->getVendor()] : null,
            'vendorId' => $asset->getVendor(),
            'assetName' => $asset->getAssetName(),
            'serialNumber' => $asset->getSerialNumber(),
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
            'createdBy' => ucwords($this->allEntityIdsAndNames()['employeesIds'][$asset->getCreatedBy()]),
            'status' => $asset->isStatus() ? "Active" : "Not Active",
        ];
    }
}
