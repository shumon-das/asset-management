<?php

namespace App\Entity\Common;

use App\Entity\Department;

trait DepartmentMethodsTrait
{
    use CommonMethodsTrait;
    public function departmentMethods(Department $department, $request, string $update): Department
    {
        $department
            ->setDepartmentName($request->get('departmentName'))
            ->setContactPerson($request->get('contactPerson'))
            ->setContactPersonEmail($request->get('contactPersonEmail'))
            ->setContactPersonPhone($request->get('contactPersonPhone'))
        ;

        return $this->commonMethods($department, $update);
    }
}