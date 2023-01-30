<?php

namespace App\Common\Uploads;

use App\Entity\Employee;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;
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

            foreach ($data as $key => $row) {
                if (0 !== $key) {
                    $roles = "ROLE_" . strtoupper($row[1]);
                    $employees = new Employee();
                    $employees
                        ->setUuid(Uuid::v1())
                        ->setName($row[0])
                        ->setRoles([$roles])
                        ->setReportingManager($row[2])
                        ->setDepartment($row[4])
                        ->setContactNo($row[5])
                        ->setLocation($row[6])
                        ->setPassword($row[7])
                        ->setEmail($row[8])
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setCreatedBy(1);
                    $entityManager->persist($employees);
                }
            }
            $entityManager->flush();
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
//
        return [
            'success' => 'Employees imported successfully'
        ];
    }

    public function validateData(array $data): array
    {
        $names = $this->allEntityIdsAndNames();
        $missingData = [];
        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $missingData[] = [
                    'row' => ++$key,
                    'reportingManager' => $row[2],
                    'email' => $row[3],
                    'department' => $row[4],
                    'location' => $row[6],
                    'depCondition' => in_array($row[3], $names['empEmailsAndIds']),
                    'locCondition' => in_array($row[3], $names['locationsIds']),
                    'reportingMCondition' => in_array($row[3], $names['empEmailsAndIds']),
                ];
            }
        }

        return $missingData;
    }

}