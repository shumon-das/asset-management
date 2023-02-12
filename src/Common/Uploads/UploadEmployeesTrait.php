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

    public function validateData(array $data): array
    {
        $names = $this->allEntityIdsAndNames();
        $missingData = [];
        $employee = new Employee();
        $uniqueEmailError = 0;
        foreach ($data as $key => $row) {
            if (0 !== $key && false === empty($row[0]) && false === empty($row[7])) {
                if (in_array($row[7], $names['empEmailsAndIds'])) {
                    ++$uniqueEmailError;
                }

                $missingData[] = [
                    'row' => ++$key,
                    'name' => $row[0],
                    'roles' => $row[1],
                    'reportingManager' => array_flip($names['empEmailsAndIds'])[$row[2]] ?? 0,
                    'reportingMCondition' => in_array($row[2], $names['empEmailsAndIds']),
                    'email' => $row[2],
                    'uniqueEmailError' => in_array($row[7], $names['empEmailsAndIds']),
                    'department' => array_flip($names['departmentsIds'])[$row[3]] ?? 0,
                    'showDepartment' => $row[3],
                    'depCondition' => in_array(ucfirst($row[3]), $names['departmentsIds']),
                    'contactNo' => $row[4],
                    'location' => array_flip($names['locationsIds'])[$row[5]] ?? 0,
                    'showLocation' => $row[5],
                    'locCondition' => in_array(ucfirst($row[5]), $names['locationsIds']),
                    'password' => $this->hasher->hashPassword($employee, $row[6]),
                    'employeeEmail' => $row[7],
                    'itemError' => $this->getItemError($row, $names),
                ];
            }
        }

        $uploadError = [];
        foreach ($missingData as $row) {
            if (false === $row['depCondition']) {
                $uploadError[] = [
                    'dep' => $row['department']
                ];
            }
            if (false === $row['locCondition']) {
                $uploadError[] = [
                    'loc' => $row['location']
                ];
            }
            if (false === $row['reportingMCondition']) {
                $uploadError[] = [
                    'rep' => $row['reportingManager']
                ];
            }
        }

        return [
            'data' => $missingData,
            'uploadError' => $uploadError,
            'uniquerEmailError' => $uniqueEmailError,
        ];
    }

    private function getItemError(array $row, array $names): bool
    {
        if (false === in_array($row[2], $names['empEmailsAndIds'])) {
            return true;
        }
        if (false === in_array(ucfirst($row[3]), $names['departmentsIds'])) {
            return true;
        }
        if (false === in_array(ucfirst($row[5]), $names['locationsIds'])) {
            return true;
        }

        return false;
    }
}