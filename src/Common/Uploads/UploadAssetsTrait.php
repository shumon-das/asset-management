<?php

namespace App\Common\Uploads;

use App\Entity\Assets;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

trait UploadAssetsTrait
{
    public function importAssets(Request $request, EntityManagerInterface $entityManager): void
    {
        $assetFile = $request->files->get('assets-csv');
        $spreadsheet = IOFactory::load($assetFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $asset = new Assets();
                $asset
                    ->setProductCategory($row[0])
                    ->setProductType($row[1])
                    ->setProduct($row[2])
                    ->setVendor($row[3])
                    ->setAssetName($row[4])
                    ->setSerialNumber($row[5])
                    ->setPrice($row[6])
                    ->setDescriptionType($row[7])
                    ->setLocation($row[8])
                    ->setPurchaseDate($row[9])
                    ->setWarrantyExpiryDate($row[10])
                    ->setDescription($row[11])
                    ->setUsefulLife($row[12])
                    ->setResidualValue($row[13])
                    ->setRate($row[14])
                    ->setDescription($row[4])
                    ->setStatus(true)
                    ->setIsDeleted(false)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDeletedBy(null)
                    ->setCreatedBy(1)
                ;
                $entityManager->persist($asset);
            }
        }
        $entityManager->flush();
    }
}