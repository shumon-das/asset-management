<?php

namespace App\Entity\Common;

use App\Entity\Products;

trait ProductMethodsTrait
{
    use CommonMethodsTrait;
    public function productMethods(Products $product, $request, bool $update): Products
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