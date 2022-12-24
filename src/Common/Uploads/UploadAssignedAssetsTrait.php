<?php

namespace App\Common\Uploads;

use App\Entity\Assets;
use App\Entity\AssigningAssets;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

trait UploadAssignedAssetsTrait
{
    public function importAssignedAssets(Request $request, UserInterface $user, EntityManagerInterface $entityManager): void
    {
        $assignedAssetFile = $request->files->get('assigned-assets-csv');
        $spreadsheet = IOFactory::load($assignedAssetFile);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {
            if (0 !== $key) {
                $assignedAsset = new AssigningAssets();
                $assignedAsset
                    ->setProductCategory($row[0])
                    ->setProductType($row[1])
                    ->setProduct($row[2])
                    ->setVendor($row[3])
                    ->setLocation($row[4])
                    ->setAssetName($row[5])
                    ->setDepartment($row[6])
                    ->setAssignTo($row[7])
                    ->setDescription($row[8])
                    ->setStatus(true)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setCreatedBy($user->getId())
                ;
                $entityManager->persist($assignedAsset);
            }
        }
        $entityManager->flush();
    }
}