<?php

namespace App\Common\Uploads;

use App\Entity\Employee;
use App\Entity\Methods\CategoriesMethodsTrait;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

trait UploadEmployeesTrait
{
    use CategoriesMethodsTrait;

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
            $names = $this->allEntityIdsAndNames();
            foreach ($data as $key => $row) {
                $reportingManagerId = array_flip($names['empEmailsAndIds'])[$row[3]] ?? 0;
                $departmentId = array_flip($names['departmentsIds'])[$row[4]] ?? 0;
                $locationId = array_flip($names['locationsIds'])[$row[6]] ?? 0;

                if (0 !== $key) {
                    $roles = "ROLE_" . strtoupper($row[1]);
                    $employees = new Employee();
                    $employees
                        ->setUuid(Uuid::v1())->setName($row[0])
                        ->setRoles([$roles])
                        ->setReportingManager($reportingManagerId)
                        ->setDepartment($departmentId)
                        ->setContactNo($row[5])
                        ->setLocation($locationId)
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

        return [
            'success' => 'Employees imported successfully'
        ];
    }

    public function validateData(array $data): array
    {
        $names = $this->allEntityIdsAndNames();
        $missingData = [];
        $employee = new Employee();
        $uniquerEmailError = 0;
        foreach ($data as $key => $row) {
            if (0 !== $key) {
                if (in_array($row[8], $names['empEmailsAndIds'])) {
                    ++$uniquerEmailError;
                }
                $missingData[] = [
                    'row' => ++$key,
                    'name' => $row[0],
                    'roles' => $row[1],
                    'reportingManager' => array_flip($names['empEmailsAndIds'])[$row[2]] ?? 0,
                    'showReportManager' => $row[2],
                    'reportingMCondition' => in_array($row[3], $names['empEmailsAndIds']),
                    'email' => $row[3],
                    'uniqueEmailError' => in_array($row[8], $names['empEmailsAndIds']),
                    'department' => array_flip($names['departmentsIds'])[$row[4]] ?? 0,
                    'showDepartment' => $row[4],
                    'depCondition' => in_array($row[4], $names['departmentsIds']),
                    'contactNo' => $row[5],
                    'location' => array_flip($names['locationsIds'])[$row[6]] ?? 0,
                    'showLocation' => $row[6],
                    'locCondition' => in_array($row[6], $names['locationsIds']),
                    'password' => $this->hasher->hashPassword($employee, $row[7]),
                    'employeeEmail' => $row[8],
                    'itemError' => $this->getItemError($row, $names),
                ];
            }
        }

        $error = [];
        foreach ($missingData as $row) {
            if (false === $row['depCondition']) {
                $error[] = [
                    'dep' => $row['department']
                ];
            }
            if (false === $row['locCondition']) {
                $error[] = [
                    'loc' => $row['location']
                ];
            }
            if (false === $row['reportingMCondition']) {
                $error[] = [
                    'rep' => $row['reportingManager']
                ];
            }
        }

        return [
            'data' => $missingData,
            'error' => $error,
            'uniquerEmailError' => $uniquerEmailError !== 0,
        ];
    }

    private function getItemError(array $row, array $names): bool
    {
        $itemError = false;
        if (false === in_array($row[3], $names['empEmailsAndIds'])) {
            $itemError = true;
        } elseif (false === in_array($row[4], $names['departmentsIds'])) {
            $itemError = true;
        } elseif (false === in_array($row[6], $names['locationsIds'])) {
            $itemError = true;
        }
        return $itemError;
    }
}