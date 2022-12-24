<?php

namespace App\Common\Exports;

use App\Common\GetVendorNameTrait;
use App\Repository\ProductsRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawProductsSheetHeadTrait
{
//    use GetVendorNameTrait;

    /**
     * @throws Exception
     */
    private function drawProductsSheetHead(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Category');
        $sheet->getCell('C1')->setValue('Type');
        $sheet->getCell('D1')->setValue('Name');
        $sheet->getCell('E1')->setValue('Manufacturer');
        $sheet->getCell('F1')->setValue('Description');

        $assets = $this->productsRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getCategory());
            $sheet->getCell('C'.$rowAct)->setValue($row->getType());
            $sheet->getCell('D'.$rowAct)->setValue($row->getName());
            $sheet->getCell('E'.$rowAct)->setValue($row->getManufacturer());
            $sheet->getCell('F'.$rowAct)->setValue($row->getDescription());

            $rowAct++;
        }
    }
}