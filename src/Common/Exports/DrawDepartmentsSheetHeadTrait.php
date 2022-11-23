<?php

namespace App\Common\Exports;

use App\Common\GetVendorNameTrait;
use App\Repository\DepartmentRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawDepartmentsSheetHeadTrait
{
    private DepartmentRepository $departmentRepository;
    use GetVendorNameTrait;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    /**
     * @throws Exception
     */
    private function drawDepartmentsSheetHead(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Department Name');
        $sheet->getCell('C1')->setValue('Contact Person');
        $sheet->getCell('D1')->setValue('Contact Person Email');
        $sheet->getCell('E1')->setValue('Contact Person Phone');

        $departments = $this->departmentRepository->findAll();
        $rowAct = 3;
        foreach ($departments as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getDepartmentName());
            $sheet->getCell('C'.$rowAct)->setValue($row->getContactPerson());
            $sheet->getCell('D'.$rowAct)->setValue($row->getContactPersonEmail());
            $sheet->getCell('E'.$rowAct)->setValue($row->getContactPersonPhone());

            $rowAct++;
        }
    }
}