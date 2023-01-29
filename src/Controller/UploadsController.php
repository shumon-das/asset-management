<?php

namespace App\Controller;

use App\Common\Uploads\UploadAssetsTrait;
use App\Common\Uploads\UploadAssignedAssetsTrait;
use App\Common\Uploads\UploadEmployeesTrait;
use App\Common\Uploads\UploadProductsTrait;
use App\Common\Uploads\UploadVendorsTrait;
use App\Entity\Employee;
use App\Entity\Methods\EmployeeMethodsTrait;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadsController extends AbstractApiController
{
    use UploadVendorsTrait;
    use UploadProductsTrait;
    use UploadAssetsTrait;
    use UploadAssignedAssetsTrait;
    use UploadEmployeesTrait;
    use EmployeeMethodsTrait;

    #[Route('/ams/upload-vendors-file', name: 'app_upload_vendors_file')]
    public function uploadVendorsFile(): Response
    {
        return $this->render('uploads/upload-vendors-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/upload-files', name: 'app_upload_files')]
    public function uploadFiles(Request $request): RedirectResponse
    {
        $this->importVendors($request, $this->entityManager);
        return new RedirectResponse('vendors');
    }

    #[Route('/ams/upload-products-file', name: 'app_upload_products_file')]
    public function uploadProducts(): Response
    {
        return $this->render('uploads/upload-products-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/upload-products-files', name: 'app_upload_products_files')]
    public function uploadProductsFiles(Request $request): RedirectResponse
    {
        $this->importProducts($request, $this->entityManager);
        return new RedirectResponse('products');
    }

    #[Route('/ams/upload-assets-file', name: 'app_upload_assets_files', methods: 'GET')]
    public function uploadAssets(): Response
    {
        return $this->render('uploads/upload-assets-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/upload-assets', name: 'app_upload_assets', methods: 'POST')]
    public function uploadAssetsFiles(Request $request): RedirectResponse
    {
        $this->importAssets($request, $this->entityManager);
        return new RedirectResponse('assets');
    }

    #[Route('/upload-employees-file', name: 'app_upload_employees_files', methods: 'GET')]
    public function uploadEmployees(): Response
    {
        return $this->render('uploads/upload-employees-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/upload-employees', name: 'app_upload_employees')]
    public function uploadEmployeesFiles(Request $request): RedirectResponse|Response
    {
         $this->importEmployees($request, $this->entityManager);
//        $this->addFlash('message', $result);
        return new RedirectResponse('upload-employees-file');
    }

    #[Route('/ams/upload-assigned-assets-file', name: 'app_upload_assigned_assets_files', methods: 'GET')]
    public function uploadAssignedAssets(): Response
    {
        return $this->render('uploads/upload-assigned-assets-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/upload-assigned-assets', name: 'app_upload_assigned_assets', methods: 'POST')]
    public function uploadAssignedAssetsFiles(Request $request): RedirectResponse
    {
        $this->importAssignedAssets($request, $this->user, $this->entityManager);
        return new RedirectResponse('assigned');
    }
}
