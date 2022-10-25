<?php

namespace App\Common\Uploads;

use App\Entity\Vendors;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

trait UploadVendorsTrait
{
    public function importVendors(Request $request, EntityManagerInterface $entityManager): void
    {
        $vendorFile = $request->files->get('vendors-csv');
        $spreadsheet = IOFactory::load($vendorFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $vendor = new Vendors();
                $vendor
                    ->setVendorName($row[0])
                    ->setEmail($row[1])
                    ->setPhone($row[2])
                    ->setContactPerson($row[3])
                    ->setCountry($row[4])
                    ->setState($row[5])
                    ->setCity($row[6])
                    ->setZipCode($row[7])
                    ->setAddress($row[8])
                    ->setDesignation($row[9])
                    ->setGstinNo($row[10])
                    ->setStatus(false)
                    ->setIsDeleted(false)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDeletedBy(null)
                    ->setCreatedBy(1)
                ;
                $entityManager->persist($vendor);
            }
        }
        $entityManager->flush();
    }
}