<?php

namespace App\Entity\Methods;

use App\Entity\Assets;
use DateTimeImmutable;
use Exception;

trait AssetMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function assetMethods(Assets $asset, $request, bool $update = false): array
    {
        $asset
            ->setProduct($request->get('product'))
            ->setVendor($request->get('vendor'))
            ->setAssetName($request->get('asset-name'))
            ->setSerialNumber($request->get('serial-number'))
            ->setPrice($request->get('price'))
            ->setLocation($request->get('location'))
            ->setPurchaseDate(new DateTimeImmutable($request->get('purchase-date')))
            ->setWarrantyExpiryDate(new DateTimeImmutable($request->get('warranty-expiry-date')))
            ->setPurchaseType($request->get('purchase-type'))
            ->setDescription($request->get('description'))
            ->setUsefulLife($request->get('useful-life'))
            ->setResidualValue($request->get('residual-value'))
            ->setRate($request->get('rate'))
            ->setStatus(true)
        ;
        return $this->commonMethods($asset, $update);
    }
}