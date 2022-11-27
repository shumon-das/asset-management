<?php

namespace App\Common\Exports;

use App\Common\GetVendorNameTrait;
use App\Repository\ProductsRepository;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DrawManufacturesSheetHeadTrait
{
    private ProductsRepository $productsRepository;
    use GetVendorNameTrait;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    /**
     * @throws Exception
     */
    public function drawManufacturesSheetHead(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Manufacturer');
//        $sheet->getCell('C1')->setValue('Added At');

        $uniqManufacturer = $this->productsRepository->getUniqueManufacturer();
        $products = [];
        foreach ($uniqManufacturer as $row) {
            $products[] = $this->productsRepository->findOneBy(['manufacturer' => $row['manufacturer']]);
        }

        $rowAct = 3;
        foreach ($products as $row) {
            $sheet->getCell('A' . $rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B' . $rowAct)->setValue($row->getManufacturer());
//            $sheet->getCell('C' . $rowAct)->setValue($row->getCreatedAt()->format('Y-m-d'));

            $rowAct++;
        }
    }
}