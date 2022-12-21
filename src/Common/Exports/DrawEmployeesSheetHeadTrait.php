<?php

namespace App\Common\Exports;

use App\Common\NamesTrait;
use App\Repository\EmployeeRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawEmployeesSheetHeadTrait
{
    use NamesTrait;
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @throws Exception
     */
    private function drawEmployeesSheetHead(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('D1')->setValue('Name');
        $sheet->getCell('B1')->setValue('Email');
        $sheet->getCell('C1')->setValue('Role');
        $sheet->getCell('D1')->setValue('Contact No');
        $sheet->getCell('E1')->setValue('Location');
        $sheet->getCell('F1')->setValue('Reporting Manager');
        $sheet->getCell('G1')->setValue('Department');
        $sheet->getCell('H1')->setValue('Created At');

        $names = $this->allEntityIdsAndNames();
        $assets = $this->employeeRepository->findBy(['isDeleted' => 0]);
        $rowAct = 3;
        foreach ($assets as $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getName());
            $sheet->getCell('C'.$rowAct)->setValue($row->getEmail());
            $roles = substr(implode(",", $row->getRoles()),5);
            $sheet->getCell('D'.$rowAct)->setValue($row->getContactNo());
            $sheet->getCell('E'.$rowAct)->setValue($names['locationsIds'][$row->getLocation()] ?? 0);
            $sheet->getCell('F'.$rowAct)->setValue($names['employeesIds'][$row->getReportingManager()] ?? 0);
            $sheet->getCell('G'.$rowAct)->setValue($names['departmentsIds'][$row->getDepartment()] ?? 0);
            $sheet->getCell('H'.$rowAct)->setValue($row->getCreatedAt()->format('Y-M-d'));

            $rowAct++;
        }
    }
}