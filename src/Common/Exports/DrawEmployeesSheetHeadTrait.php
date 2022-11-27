<?php

namespace App\Common\Exports;

use App\Repository\EmployeeRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawEmployeesSheetHeadTrait
{

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
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('D1')->setValue('Name');
        $sheet->getCell('B1')->setValue('Email');
        $sheet->getCell('C1')->setValue('Role');

        $assets = $this->employeeRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getName());
            $sheet->getCell('C'.$rowAct)->setValue($row->getEmail());
            $roles = substr(implode(",", $row->getRoles()),5);
            $sheet->getCell('D'.$rowAct)->setValue($roles);

            $rowAct++;
        }
    }
}