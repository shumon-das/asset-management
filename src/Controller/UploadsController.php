<?php

namespace App\Controller;

use App\Entity\Vendors;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/upload-vendors-file', name: 'app_upload_vendors_file')]
    public function uploadVendorsFile(): Response
    {
        return $this->render('uploads/upload-vendors-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/upload-files', name: 'app_upload_files')]
    public function uploadFiles(Request $request): RedirectResponse
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
                $this->entityManager->persist($vendor);
            }
        }
        $this->entityManager->flush();
        return new RedirectResponse('upload-vendors');
    }
}
