<?php

namespace App\Controller;

use App\Common\Uploads\UploadAssetsTrait;
use App\Common\Uploads\UploadAssignedAssetsTrait;
use App\Common\Uploads\UploadEmployeesTrait;
use App\Common\Uploads\UploadProductsTrait;
use App\Common\Uploads\UploadVendorsTrait;
use App\Entity\Employee;
use App\Entity\Methods\EmployeeMethodsTrait;
use App\Entity\Upload;
use DateTimeImmutable;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

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

    #[Route('/select-employees-file', name: 'app_select_employees_files', methods: 'GET')]
    public function uploadEmployees(): Response
    {
        return $this->render('uploads/upload-employees-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/show-employees-file', name: 'app_show_employees_files', methods: [ 'POST'])]
    public function uploadEmployeesFile(Request $request): Response|RedirectResponse
    {
        $employeesFile = $request->files->get('employees-csv');
        if (null === $employeesFile) {
            $this->addFlash('error', 'File not found. please choose an csv file before click upload');
            return new RedirectResponse('upload-employees-file');
        }
        $spreadsheet = IOFactory::load($employeesFile);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $dataAnalyse = $this->validateData($data);
        $employeeInUpload = $this->uploadRepository->findOneBy(['entityName' => 'employee']);
        $this->entityManager->remove($employeeInUpload);
        $this->entityManager->flush();

        $upload = new Upload();
        $upload
            ->setEntityName('employee')
            ->setData($dataAnalyse['data'])
            ->setIsDeleted(false)
            ->setCreatedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($upload);
        $this->entityManager->flush();

        return $this->render('uploads/upload-employees-file.html.twig', [
            'data' => $dataAnalyse['data'],
            'errors' => count($dataAnalyse['error']) > 0,
            'errorData' => $dataAnalyse['error'],
            'errorCount' => count($dataAnalyse['error']),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/upload-employees', name: 'app_upload_employees', methods: ['POST'])]
    public function uploadEmployeesFiles(): JsonResponse
    {
        $employees = $this->uploadRepository->findOneBy(['entityName' => 'employee']);
        $data = $employees->getData();
        foreach ($data as $employee) {
            $empl = new Employee();
            $roles = "ROLE_" . strtoupper($employee['roles']);
            $empl
                ->setUuid(Uuid::v1())
                ->setName($employee['name'])
                ->setEmail($employee['employeeEmail'])
                ->setLocation($employee['location'])
                ->setContactNo($employee['contactNo'])
                ->setDepartment($employee['department'])
                ->setReportingManager($employee['reportingManager'])
                ->setRoles([$roles])
                ->setPassword($employee['password'])
            ;
            $this->commonMethods($empl, false);
        }

        return new JsonResponse(['success' => 'Employees saved successfully']);
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
