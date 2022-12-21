<?php

namespace App\Common\Uploads;

use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

trait UploadEmployeesTrait
{
    public function importEmployees(Request $request, EntityManagerInterface $entityManager): void
    {
        $employeesFile = $request->files->get('employees-csv');
        $spreadsheet = IOFactory::load($employeesFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $employees = new Employee();
                $employees
                    ->setName($row[0])
                    ->setRoles($row[1])
                    ->setReportingManager($row[2])
                    ->setDepartment($row[3])
                    ->setContactNo($row[4])
                    ->setLocation($row[5])
                    ->setPassword($row[6])
                    ->setEmail($row[7])
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setCreatedBy(1)
                ;
                $entityManager->persist($employees);
            }
        }
        $entityManager->flush();
    }
}