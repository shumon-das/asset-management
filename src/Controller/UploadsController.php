<?php

namespace App\Controller;

use App\Common\Uploads\UploadAssetsTrait;
use App\Common\Uploads\UploadAssignedAssetsTrait;
use App\Common\Uploads\UploadProductsTrait;
use App\Common\Uploads\UploadVendorsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadsController extends AbstractController
{
    use UploadVendorsTrait;
    use UploadProductsTrait;
    use UploadAssetsTrait;
    use UploadAssignedAssetsTrait;

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
        $this->importVendors($request, $this->entityManager);
        return new RedirectResponse('vendors');
    }

    #[Route('/upload-products-file', name: 'app_upload_products_file')]
    public function uploadProducts(): Response
    {
        return $this->render('uploads/upload-products-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/upload-products-files', name: 'app_upload_products_files')]
    public function uploadProductsFiles(Request $request): RedirectResponse
    {
        $this->importProducts($request, $this->entityManager);
        return new RedirectResponse('products');
    }

    #[Route('/upload-assets-file', name: 'app_upload_assets_files', methods: 'GET')]
    public function uploadAssets(): Response
    {
        return $this->render('uploads/upload-assets-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/upload-assets', name: 'app_upload_assets', methods: 'POST')]
    public function uploadAssetsFiles(Request $request): RedirectResponse
    {
        $this->importAssets($request, $this->entityManager);
        return new RedirectResponse('assets');
    }

//    #[Route('/upload-assets-file', name: 'app_upload_assets_files')]
//    public function uploadEmployees(): Response
//    {
//        return $this->render('uploads/upload-assets-file.html.twig', [
//            'controller_name' => 'UploadsController',
//        ]);
//    }
//
//    #[Route('/upload-assets', name: 'app_upload_assets')]
//    public function uploadEmployeesFiles(Request $request): RedirectResponse
//    {
//        $this->importAssets($request, $this->entityManager);
//        return new RedirectResponse('assets');
//    }

    #[Route('/upload-assigned-assets-file', name: 'app_upload_assigned_assets_files', methods: 'GET')]
    public function uploadAssignedAssets(): Response
    {
        return $this->render('uploads/upload-assigned-assets-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/upload-assigned-assets', name: 'app_upload_assigned_assets', methods: 'POST')]
    public function uploadAssignedAssetsFiles(Request $request): RedirectResponse
    {
        $this->importAssignedAssets($request, $this->entityManager);
        return new RedirectResponse('assigned');
    }
}
