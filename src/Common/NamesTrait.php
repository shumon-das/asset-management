<?php

namespace App\Common;

trait NamesTrait
{
    public function allEntityIdsAndNames(): array
    {
        return [
            'vendorsIds' => array_column($this->vendorsRepository->findIds(), 'name','id'),
            'employeesIds' => array_column($this->employeeRepository->findIds(), 'name', 'id'),
            'assetsIds' => array_column($this->assetsRepository->findIds(),'name', 'id'),
            'locationsIds' => array_column($this->locationRepository->findIds(),'name', 'id'),
            'departmentsIds' => array_column($this->departmentRepository->findIds(),'name', 'id'),
            'productsIds' => array_column($this->productsRepository->findIds(), 'name','id'),
            'productTypeIds' => array_column($this->productsRepository->findTypeIds(), 'type','id'),
            'productCategoryIds' => array_column($this->productsRepository->findCategoryIds(), 'category','id'),
        ];
    }
}