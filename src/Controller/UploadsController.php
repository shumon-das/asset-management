<?php

namespace App\Controller;

use App\Common\Uploads\UploadAssetsTrait;
use App\Common\Uploads\UploadAssignedAssetsTrait;
use App\Common\Uploads\UploadEmployeesTrait;
use App\Common\Uploads\UploadProductsTrait;
use App\Common\Uploads\UploadVendorsTrait;
use App\Entity\Assets;
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

    #[Route('/ams/select-assets-file', name: 'app_select_assets_files', methods: 'GET')]
    public function selectAssetsFile(): Response
    {
        return $this->render('uploads/upload-assets-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/show-assets-file', name: 'app_show_assets_files', methods: 'POST')]
    public function showAssetsFile(Request $request): Response|RedirectResponse
    {
        $assetsFile = $request->files->get('assets-csv');
        if (null === $assetsFile) {
            $this->addFlash('error', 'File not found. please choose an csv file before click upload');
            return new RedirectResponse('select-assets-file');
        }
        $spreadsheet = IOFactory::load($assetsFile);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $dataAnalyse = $this->validateAssetsData($data);

        $this->uploadDataTemporarily($dataAnalyse['data'], 'asset');

        return $this->render('uploads/upload-assets-file.html.twig', [
            'entity' => 'asset',
            'data' => $dataAnalyse['data'],
            'uploadError' => count($dataAnalyse['uploadError']) > 0,
            'uniqueEmailError' => '',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/upload-assets', name: 'app_upload_assets', methods: 'POST')]
    public function uploadAssetsFiles(Request $request): JsonResponse
    {
        $assets = $this->uploadRepository->findOneBy(['entityName' => 'asset']);
        $data = $assets->getData();
        foreach ($data as $row) {
            $asset = new Assets();
            $asset
                ->setProduct($row['product'])
                ->setVendor($row['vendor'])
                ->setAssetName($row['assetName'])
                ->setSerialNumber($row['serialNumber'])
                ->setPrice($row['price'])
                ->setLocation($row['location'])
                ->setPurchaseDate(new DateTimeImmutable($row['purchaseDate']))
                ->setWarrantyExpiryDate(new DateTimeImmutable($row['warrantyExpireDate']))
                ->setPurchaseType($row['purchaseType'])
                ->setUsefulLife($row['usefulLife'])
                ->setResidualValue($row['residualValue'])
                ->setRate($row['rate'])
                ->setDescription($row['description'])
            ;
            $this->commonMethods($asset, false);
        }

        return new JsonResponse(['success' => 'Assets saved successfully']);
    }

    #[Route('/ams/select-employees-file', name: 'app_select_employees_files', methods: 'GET')]
    public function uploadEmployees(): Response
    {
        return $this->render('uploads/upload-employees-file.html.twig', [
            'controller_name' => 'UploadsController',
        ]);
    }

    #[Route('/ams/show-employees-file', name: 'app_show_employees_files', methods: [ 'POST'])]
    public function showEmployeesFile(Request $request): Response|RedirectResponse
    {
        $employeesFile = $request->files->get('employees-csv');
        if (null === $employeesFile) {
            $this->addFlash('error', 'File not found. please choose an csv file before click upload');
            return new RedirectResponse('select-employees-file');
        }
        $spreadsheet = IOFactory::load($employeesFile);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $dataAnalyse = $this->validateData($data);

        $this->uploadDataTemporarily($dataAnalyse['data'], 'employee');

        return $this->render('uploads/upload-employees-file.html.twig', [
            'entity' => 'employee',
            'data' => $dataAnalyse['data'],
            'uploadError' => count($dataAnalyse['uploadError']) > 0,
            'errorData' => $dataAnalyse['uploadError'],
            'errorCount' => count($dataAnalyse['uploadError']),
            'uniqueEmailError' => $dataAnalyse['uniquerEmailError'] > 0 ? 'disabled' : '',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/upload-employees', name: 'app_upload_employees', methods: ['POST'])]
    public function uploadEmployeesFile(): JsonResponse
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
                ->setLocation(ucfirst($employee['location']))
                ->setContactNo($employee['contactNo'])
                ->setDepartment(ucfirst($employee['department']))
                ->setReportingManager(ucfirst($employee['reportingManager']))
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

    private function uploadDataTemporarily(array $data, string $entity)
    {
        $employeeInUpload = $this->uploadRepository->findOneBy(['entityName' => $entity]);
        if (null !== $employeeInUpload) {
            $this->entityManager->remove($employeeInUpload);
            $this->entityManager->flush();
        }

        $upload = new Upload();
        $upload
            ->setEntityName($entity)
            ->setData($data)
            ->setIsDeleted(false)
            ->setCreatedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($upload);
        $this->entityManager->flush();
    }
}
