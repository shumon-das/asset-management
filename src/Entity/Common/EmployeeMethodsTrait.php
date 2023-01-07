<?php

namespace App\Entity\Common;

use App\Entity\Employee;

trait EmployeeMethodsTrait
{
    use CommonMethodsTrait;
    public function employeeMethods(Employee $employee, $request, bool $update): Employee
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $hashedPassword = $this->hasher->hashPassword($employee, $password);
        $employee
            ->setName($request->get('name'))
            ->setEmail($email)
            ->setPassword($hashedPassword)
            ->setLocation($request->get('location'))
            ->setContactNo($request->get('contact-no'))
            ->setDepartment($request->get('department'))
            ->setReportingManager($request->get('reporting-manager'))
            ->setRoles(['ROLE_USER'])
        ;
        return $this->commonMethods($employee, $update);
    }

}