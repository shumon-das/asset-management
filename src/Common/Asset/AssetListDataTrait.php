<?php

namespace App\Common\Asset;

use App\Entity\Assets;
use App\Repository\VendorsRepository;

trait AssetListDataTrait
{
    private function assetsListData(array $assignedAssetsIds, Assets $asset): array
    {
        $names = $this->allEntityIdsAndNames();
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $names['productsIds'][$asset->getProduct()],
            'vendor' => $names['vendorsIds'][$asset->getVendor()],
            'assetName' => $asset->getAssetName(),
            'assign' => in_array($asset->getId(),$assignedAssetsIds),
            'serialNumber' => $asset->getSerialNumber(),
        ];
    }
}