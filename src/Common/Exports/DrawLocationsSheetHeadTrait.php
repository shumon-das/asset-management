<?php

namespace App\Common\Exports;

use App\Repository\LocationRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawLocationsSheetHeadTrait
{
    /**
     * @throws Exception
     */
    private function drawLocationsSheetHead(Worksheet $sheet): void
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
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Office Name');
        $sheet->getCell('C1')->setValue('Country');
        $sheet->getCell('D1')->setValue('State');
        $sheet->getCell('E1')->setValue('City');
        $sheet->getCell('F1')->setValue('Zip Code');
        $sheet->getCell('G1')->setValue('Contact Person Name');
        $sheet->getCell('H1')->setValue('Address Line 1');
        $sheet->getCell('I1')->setValue('Address Line 2');
        $sheet->getCell('J1')->setValue('Created At');

        $locations = $this->locationRepository->findAll();
        $rowAct = 3;
        foreach ($locations as $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getOfficeName());
            $sheet->getCell('C'.$rowAct)->setValue($row->getCountry());
            $sheet->getCell('D'.$rowAct)->setValue($row->getState());
            $sheet->getCell('E'.$rowAct)->setValue($row->getCity());
            $sheet->getCell('F'.$rowAct)->setValue($row->getZipCode());
            $sheet->getCell('G'.$rowAct)->setValue($row->getContactPersonName());
            $sheet->getCell('H'.$rowAct)->setValue($row->getAddress1());
            $sheet->getCell('I'.$rowAct)->setValue($row->getAddress2());
            $sheet->getCell('J'.$rowAct)->setValue($row->getCreatedAt()->format('Y-M-d'));

            $rowAct++;
        }
    }
}