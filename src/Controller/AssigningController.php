<?php

namespace App\Controller;

use App\Entity\AssigningAssets;
use App\Entity\Methods\AssigningMethodsTrait;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssigningController extends AbstractApiController
{
    use AssigningMethodsTrait;
    #[Route('/ams/assigned', name: 'assigned_assets')]
    public function assignedAsset(): Response
    {
        $assignedProduct = $this->assigningAssetsRepository->findBy(['isDeleted' => 0]);

        $data = [];
        foreach ($assignedProduct as $product) {
            $data[$product->getId()] = $this->assignedData($product, $this->allEntityIdsAndNames());
        }

        return $this->render('assets/assigned-asset-list.html.twig', [
            'assets' => $data
        ]);
    }

    #[Route('/ams/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        return $this->render('assets/assigning-asset.html.twig',
            $this->getRepositoriesData(),
        );
    }

    #[Route('/ams/view-assigned/{id}', name: 'view_assigned_asset')]
    public function viewAssignedAsset(int $id): Response
    {
        $asset = $this->assigningAssetsRepository->find($id);
        return $this->render('assets/view-assigned.html.twig', [
            'asset' => $this->assignedAssetData($asset, $this->allEntityIdsAndNames())
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/save-assign-asset', name: 'save_assign_asset')]
    public function saveAssignAsset(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $id
            ? $this->assigningMethods($this->assigningAssetsRepository->find($id), $request, true)
            : $this->assigningMethods(new AssigningAssets(), $request);
        return new RedirectResponse('assigned');
    }

    #[Route('/ams/edit/assigning/{id}', name: 'edit_assigned_asset')]
    public function editAssigningAsset(int $id): Response
    {
        $assignedAsset = $this->assigningAssetsRepository->find($id);
        $data = [];
        if (false === empty($assignedAsset)) {
            $data = [
                ...['assignedAsset' => $this->assignedData($assignedAsset, $this->allEntityIdsAndNames())],
                ...$this->getRepositoriesData()
            ];
        }

        return $this->render('assets/assigning-asset.html.twig', $data);
    }

    private function assignedData(AssigningAssets $assignedProduct, ?array $idsAndNames = []): array
    {
        $assigned = $assignedProduct->getAssignTo();
        $asset = $assignedProduct->getAssetName();
        $department = $assignedProduct->getDepartment();
        $location = $assignedProduct->getLocation();
        $vendor = $assignedProduct->getVendor();
        $product = $assignedProduct->getProduct();
        $productType = $assignedProduct->getProductType();
        $productCategory = $assignedProduct->getProductCategory();
        return [
            'id' => $assignedProduct->getId(),
            'assetName' => array_key_exists($asset, $idsAndNames['assetsIds']) ? $idsAndNames['assetsIds'][$asset] : null,
            'assetId' => $asset,
            'department' => $department,
            'departmentName' => array_key_exists($department, $idsAndNames['departmentsIds']) ? $idsAndNames['departmentsIds'][$department] : null,
            'location' => array_key_exists($location, $idsAndNames['locationsIds']) ? $idsAndNames['locationsIds'][$location] : null,
            'locationId' => $location,
            'assigned' => $idsAndNames['employeesIds'][$assigned],
            'assignedId' => $assigned,
            'vendor' => array_key_exists($vendor, $idsAndNames['vendorsIds']) ? $idsAndNames['vendorsIds'][$vendor] : null,
            'vendorId' => $vendor,
            'product' => array_key_exists($product, $idsAndNames['productsIds']) ? $idsAndNames['productsIds'][$product] : null,
            'productId' => $product,
            'productType' => array_key_exists($productType, $idsAndNames['productTypeIds']) ? $idsAndNames['productTypeIds'][$productType] : null,
            'productCategory' => array_key_exists($productCategory, $idsAndNames['productCategoryIds']) ? $idsAndNames['productCategoryIds'][$productCategory] : null,
            'status' => $assignedProduct->isStatus() ? 'Assigned' : 'Not Assigned',
        ];
    }

    private function assignedAssetData(?AssigningAssets $asset, array $idsAndNames): array
    {
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $idsAndNames['vendorsIds'][$asset->getVendor()],
            'vendorId' => $asset->getVendor(),
            'location' => $idsAndNames['locationsIds'][$asset->getLocation()],
            'assetName' => $idsAndNames['assetsIds'][$asset->getAssetName()],
            'department' => $idsAndNames['departmentsIds'][$asset->getDepartment()],
            'assigned' => $idsAndNames['employeesIds'][$asset->getAssignTo()],
            'description' => $asset->getDescription(),
            'createdBy' => ucwords($idsAndNames['employeesIds'][$asset->getCreatedBy()]),
            'status' => $asset->isStatus(),
            'createdAt' => $asset->getCreatedAt()->format('Y-M-d'),
        ];
    }
}