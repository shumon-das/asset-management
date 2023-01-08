<?php

namespace App\Entity\Common;

use App\Entity\Employee;
use Exception;

trait EmployeeMethodsTrait
{
    use CommonMethodsTrait;

    /**
     * @throws Exception
     */
    public function employeeMethods(Employee $employee, $request, bool $update = false): Employee
    {
        $email = $request->get('email');
        false === empty($request->get('password'))
            ? $employee->setPassword($this->hasher->hashPassword($employee, $request->get('password')))
            : null;
        $employee
            ->setName($request->get('name'))
            ->setEmail($email)
            ->setLocation($request->get('location'))
            ->setContactNo($request->get('contact-no'))
            ->setDepartment($request->get('department'))
            ->setReportingManager($request->get('reporting-manager'))
            ->setRoles(['ROLE_USER'])
        ;
        return $this->commonMethods($employee, $update);
    }
}