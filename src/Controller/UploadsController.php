<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadsController extends AbstractController
{
    #[Route('/upload-vendors', name: 'app_upload_vendors')]
    public function uploadVendors(): Response
    {
        return $this->render('uploads/upload-vendors.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/upload-vendors-file', name: 'app_upload_vendors_file')]
    public function uploadVendorsFile(): Response
    {
        return $this->render('uploads/upload-vendors-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }
}
