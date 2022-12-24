<?php

namespace App\Common\Exports;

use App\Repository\VendorsRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawVendorsSheetHeadTrait
{
    /**
     * @throws Exception
     */
    private function drawVendorsSheetHead(Worksheet $sheet): void
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
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Name');
        $sheet->getCell('C1')->setValue('Email');
        $sheet->getCell('D1')->setValue('Phone');
        $sheet->getCell('E1')->setValue('Contact Person');
        $sheet->getCell('F1')->setValue('Country');
        $sheet->getCell('G1')->setValue('State');
        $sheet->getCell('H1')->setValue('City');
        $sheet->getCell('I1')->setValue('Zip Code');
        $sheet->getCell('J1')->setValue('Address');
        $sheet->getCell('K1')->setValue('Designation');
        $sheet->getCell('L1')->setValue('GSTIN No');

        $assets = $this->vendorsRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getVendorName());
            $sheet->getCell('C'.$rowAct)->setValue($row->getEmail());
            $sheet->getCell('D'.$rowAct)->setValue($row->getPhone());
            $sheet->getCell('E'.$rowAct)->setValue($row->getContactPerson());
            $sheet->getCell('F'.$rowAct)->setValue($row->getCountry());
            $sheet->getCell('G'.$rowAct)->setValue($row->getState());
            $sheet->getCell('H'.$rowAct)->setValue($row->getCity());
            $sheet->getCell('I'.$rowAct)->setValue($row->getZipCode());
            $sheet->getCell('J'.$rowAct)->setValue($row->getAddress());
            $sheet->getCell('K'.$rowAct)->setValue($row->getDesignation());
            $sheet->getCell('L'.$rowAct)->setValue($row->getGstinNo());

            $rowAct++;
        }
    }
}