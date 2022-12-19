<?php

namespace App\Common\Asset;

use App\Entity\Assets;
use App\Repository\VendorsRepository;

trait AssetListDataTrait
{
    private function assetsListData(array $assignedAssetsIds, Assets $asset, VendorsRepository $vendorsRepository): array
    {
        $vendor = $vendorsRepository->find($asset->getVendor());
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $vendor?->getVendorName(),
            'assetName' => $asset->getAssetName(),
            'assign' => in_array($asset->getId(),$assignedAssetsIds),
            'serialNumber' => $asset->getSeriulNumber(),
        ];
    }
}