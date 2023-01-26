<?php

namespace App\Common\Product;

use App\Common\NamesTrait;
use App\Entity\Products;

trait ProductDataTrait
{
    use NamesTrait;
    private function productData(Products $product, array $ids = []): array
    {
        $names = $this->allEntityIdsAndNames();
        return [
            'id' => $product->getId(),
            'category' => $product->getCategory(),
            'type' => $product->getType(),
            'name' => $product->getName(),
            'manufacturer' => $product->getManufacturer(),
            'description' => $product->getDescription(),
            'status' => $product->isStatus() ? 'active' : 'not active',
            'createdAt' => $product->getCreatedAt()->format('Y-M-d'),
            'createdBy' => ucwords($names['employeesIds'][$product->getCreatedBy()]),
            'use' => in_array($product->getId(), $ids),
        ];
    }
}