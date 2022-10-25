<?php

namespace App\Common\Uploads;

use App\Entity\Products;
use App\Entity\Vendors;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

trait UploadProductsTrait
{
    public function importProducts(Request $request, EntityManagerInterface $entityManager): void
    {
        $productFile = $request->files->get('products-csv');
        $spreadsheet = IOFactory::load($productFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $product = new Products();
                $product
                    ->setCategory($row[0])
                    ->setType($row[1])
                    ->setName($row[2])
                    ->setManufacturer($row[3])
                    ->setDescription($row[4])
                    ->setStatus(true)
                    ->setIsDeleted(false)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDeletedBy(null)
                    ->setCreatedBy(1)
                ;
                $entityManager->persist($product);
            }
        }
        $entityManager->flush();
    }
}