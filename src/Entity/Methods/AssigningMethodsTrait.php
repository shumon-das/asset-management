<?php

namespace App\Entity\Methods;

use App\Entity\AssigningAssets;
use Exception;

trait AssigningMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function assigningMethods(AssigningAssets $assigningAsset, $request, bool $update = false): array
    {
        $assigningAsset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product-name'))
            ->setVendor($request->get('vendor'))
            ->setLocation($request->get('location'))
            ->setAssetName($request->get('asset-name'))
            ->setDepartment($request->get('department'))
            ->setAssignTo($request->get('assign-to'))
            ->setDescription($request->get('description'))
            ->setIsDeleted(0)
            ->setStatus(true);

        return $this->commonMethods($assigningAsset, $update);
    }
}