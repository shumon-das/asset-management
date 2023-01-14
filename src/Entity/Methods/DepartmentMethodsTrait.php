<?php

namespace App\Entity\Methods;

use App\Entity\Department;
use Exception;

trait DepartmentMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function departmentMethods(Department $department, $request, bool $update = false): Department
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