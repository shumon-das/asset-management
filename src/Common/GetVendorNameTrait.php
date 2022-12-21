<?php

namespace App\Common;

use App\Repository\EmployeeRepository;
use App\Repository\VendorsRepository;

trait GetVendorNameTrait
{
    private VendorsRepository $vendorsRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(VendorsRepository $vendorsRepository, EmployeeRepository $employeeRepository)
    {
        $this->vendorsRepository = $vendorsRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function getVendorNameById(int $id): ?string
    {
        return $this->vendorsRepository->find($id)?->getVendorName();

    }

    public function getEmployeeNameById(int $id): ?string
    {
        return $this->employeeRepository->find($id)?->getName();

    }
}