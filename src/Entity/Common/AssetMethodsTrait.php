<?php

namespace App\Entity\Common;

use App\Entity\Assets;
use DateTimeImmutable;
use Exception;

trait AssetMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function assetMethods(Assets $asset, $request, bool $update): Assets
    {
        $asset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product'))
            ->setVendor($request->get('vendor'))
            ->setAssetName($request->get('asset-name'))
            ->setSerialNumber($request->get('serial-number'))
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
        ;
        return $this->commonMethods($asset, $update);
    }
}