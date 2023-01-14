<?php

namespace App\Entity\Methods;

use App\Entity\Products;
use Exception;

trait ProductMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function productMethods(Products $product, $request, bool $update = false): Products
    {
        $product
            ->setCategory($request->get('product-category'))
            ->setType($request->get('product-type'))
            ->setName($request->get('product-name'))
            ->setManufacturer($request->get('manufacturer'))
            ->setDescription($request->get('description'))
            ->setStatus(true);
        return $this->commonMethods($product, $update);
    }
}