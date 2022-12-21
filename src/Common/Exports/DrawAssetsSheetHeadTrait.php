<?php

namespace App\Common\Exports;

use App\Common\GetVendorNameTrait;
use App\Repository\AssetsRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawAssetsSheetHeadTrait
{
    private AssetsRepository $assetsRepository;
    use GetVendorNameTrait;

    public function __construct(AssetsRepository $assetsRepository)
    {
        $this->assetsRepository = $assetsRepository;
    }

    /**
     * @throws Exception
     */
    private function drawAssetsSheetHead(Worksheet $sheet): void
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
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Product Category');
        $sheet->getCell('C1')->setValue('Product Type');
        $sheet->getCell('D1')->setValue('Product');
        $sheet->getCell('E1')->setValue('Vendor');
        $sheet->getCell('F1')->setValue('Asset Name');
        $sheet->getCell('G1')->setValue('Serial Number');
        $sheet->getCell('H1')->setValue('Price');
        $sheet->getCell('I1')->setValue('Description Type');
        $sheet->getCell('J1')->setValue('Location');
        $sheet->getCell('K1')->setValue('Purchase Date');
        $sheet->getCell('L1')->setValue('Warranty Expire Date');
        $sheet->getCell('M1')->setValue('Description');
        $sheet->getCell('N1')->setValue('Useful Life');
        $sheet->getCell('O1')->setValue('Residual Value');
        $sheet->getCell('P1')->setValue('Rate');

        $assets = $this->assetsRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getProductCategory());
            $sheet->getCell('C'.$rowAct)->setValue($row->getProductType());
            $sheet->getCell('D'.$rowAct)->setValue($row->getProduct());
            $sheet->getCell('E'.$rowAct)->setValue($this->getVendorNameById($row->getVendor()));
            $sheet->getCell('F'.$rowAct)->setValue($row->getAssetName());
            $sheet->getCell('G'.$rowAct)->setValue($row->getSerialNumber());
            $sheet->getCell('H'.$rowAct)->setValue($row->getPrice());
            $sheet->getCell('I'.$rowAct)->setValue($row->getDescriptionType());
            $sheet->getCell('J'.$rowAct)->setValue($row->getLocation());
            $sheet->getCell('K'.$rowAct)->setValue($row->getPurchaseDate()?->format('d-M-Y'));
            $sheet->getCell('L'.$rowAct)->setValue($row->getWarrantyExpiryDate()?->format('d-M-Y'));
            $sheet->getCell('M'.$rowAct)->setValue($row->getDescription());
            $sheet->getCell('N'.$rowAct)->setValue($row->getUsefulLife());
            $sheet->getCell('O'.$rowAct)->setValue($row->getResidualValue());
            $sheet->getCell('P'.$rowAct)->setValue($row->getRate());

            $rowAct++;
        }
    }
}