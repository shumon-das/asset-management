<?php

namespace App\Common\Uploads;

use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

trait UploadEmployeesTrait
{
    public function importEmployees(Request $request, EntityManagerInterface $entityManager): array
    {
        try {
            $employeesFile = $request->files->get('employees-csv');
            if (null === $employeesFile) {
                return [
                    'error' => "File not found. please choose an csv file before click upload"
                ];
            }

            $spreadsheet = IOFactory::load($employeesFile);
            $data = $spreadsheet->getActiveSheet()->toArray();
            $validationError = $this->validateData($data);

            if (count($validationError) > 0) {
              return $validationError;
            }

//            foreach ($data as $key => $row) {
//                if (0 !== $key) {
//                    $roles = "ROLE_" . strtoupper($row[1]);
//                    $employees = new Employee();
//                    $employees
//                        ->setUuid(Uuid::v1())
//                        ->setName($row[0])
//                        ->setRoles([$roles])
//                        ->setReportingManager($employee->getId())
//                        ->setDepartment('')
//                        ->setContactNo($row[5])
//                        ->setLocation('')
//                        ->setPassword($row[7])
//                        ->setEmail($row[8])
//                        ->setCreatedAt(new DateTimeImmutable())
//                        ->setCreatedBy(1);
//                    $entityManager->persist($employees);
//                }
//            }
//            $entityManager->flush();
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return [
            'success' => 'Employees imported successfully'
        ];
    }

    public function validateData(array $data): array
    {
        $employeeEmails = $this->allEntityIdsAndNames()['empEmailsAndIds'];
        $missingData = [];
        foreach ($data as $key => $row) {
            if (0 !== $key) {
                if (false === in_array($row[3], $employeeEmails)) {
                    $missingData[] = [
                        'row' => ++$key,
                        'email' => $row[3],
                        'con' => in_array($row[3], $employeeEmails),
                    ];
                }
            }
        }

        return $missingData;
    }

}