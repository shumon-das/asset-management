<?php

namespace App\Common\Product;

use App\Entity\Products;
use App\Repository\EmployeeRepository;

trait ProductDataTrait
{
    private function productData(?Products $product, EmployeeRepository $employeeRepository): array
    {
        return [
            'id' => $product->getId(),
            'category' => $product->getCategory(),
            'type' => $product->getType(),
            'name' => $product->getName(),
            'manufacturer' => $product->getManufacturer(),
            'description' => $product->getDescription(),
            'status' => $product->isStatus() ? 'active' : 'not active',
            'createdAt' => $product->getCreatedAt()->format('Y-M-d'),
            'createdBy' => ucwords($employeeRepository->find($product->getCreatedBy())->getName()),
        ];
    }
}