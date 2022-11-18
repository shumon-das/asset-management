<?php

namespace App\Common\Exports;

use App\Common\GetVendorNameTrait;
use App\Repository\AssigningAssetsRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawAssignedAssetsSheetHeadTrait
{
    private AssigningAssetsRepository $assigningAssetsRepository;
    use GetVendorNameTrait;

    public function __construct(AssigningAssetsRepository $assigningAssetsRepository)
    {
        $this->assigningAssetsRepository = $assigningAssetsRepository;
    }

    /**
     * @throws Exception
     */
    private function drawAssignedAssetsSheetHead(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Product Category');
        $sheet->getCell('C1')->setValue('Product Type');
        $sheet->getCell('D1')->setValue('Product Name');
        $sheet->getCell('E1')->setValue('Vendor (employee id)');
        $sheet->getCell('F1')->setValue('Location');
        $sheet->getCell('G1')->setValue('Asset Name');
        $sheet->getCell('H1')->setValue('Department');
        $sheet->getCell('I1')->setValue('Assign To');
        $sheet->getCell('J1')->setValue('Description');

        $assets = $this->assigningAssetsRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getProductCategory());
            $sheet->getCell('C'.$rowAct)->setValue($row->getProductType());
            $sheet->getCell('D'.$rowAct)->setValue($row->getProduct());
            $sheet->getCell('E'.$rowAct)->setValue($this->getVendorNameById($row->getVendor()));
            $sheet->getCell('F'.$rowAct)->setValue($row->getLocation());
            $sheet->getCell('G'.$rowAct)->setValue($row->getAssetName());
            $sheet->getCell('H'.$rowAct)->setValue($row->getDepartment());
            $sheet->getCell('I'.$rowAct)->setValue($this->getEmployeeNameById($row->getAssignTo()));
            $sheet->getCell('J'.$rowAct)->setValue($row->getDescription());

            $rowAct++;
        }
    }
}