<?php

namespace App\Controller;

use App\Common\Exports\DownloadExcelFileHeaderTrait;
use App\Common\Exports\DrawAssetsSheetHeadTrait;
use App\Common\Exports\DrawEmployeesSheetHeadTrait;
use App\Common\Exports\DrawLocationsSheetHeadTrait;
use App\Common\Exports\DrawDepartmentsSheetHeadTrait;
use App\Common\Exports\DrawManufacturesSheetHeadTrait;
use App\Common\Exports\DrawProductsSheetHeadTrait;
use App\Common\Exports\DrawAssignedAssetsSheetHeadTrait;
use App\Common\Exports\DrawVendorsSheetHeadTrait;
use App\Repository\AssetsRepository;
use App\Repository\AssigningAssetsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LocationRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportsController extends AbstractController
{
    private Spreadsheet $excel;
    private AssetsRepository $assetsRepository;
    private VendorsRepository $vendorsRepository;
    private AssigningAssetsRepository $assigningAssetsRepository;
    private ProductsRepository $productsRepository;
    private EmployeeRepository $employeeRepository;
    private DepartmentRepository $departmentRepository;
    private LocationRepository $locationRepository;
    use DrawAssetsSheetHeadTrait;
    use DrawLocationsSheetHeadTrait;
    use DownloadExcelFileHeaderTrait;
    use DrawDepartmentsSheetHeadTrait;
    use DrawVendorsSheetHeadTrait;
    use DrawAssignedAssetsSheetHeadTrait;
    use DrawProductsSheetHeadTrait;
    use DrawManufacturesSheetHeadTrait;
    use DrawEmployeesSheetHeadTrait;


    public function __construct(
        AssetsRepository $assetsRepository,
        VendorsRepository $vendorsRepository,
        AssigningAssetsRepository $assigningAssetsRepository,
        ProductsRepository $productsRepository,
        EmployeeRepository $employeeRepository,
        DepartmentRepository $departmentRepository,
        LocationRepository $locationRepository
    ){
        $this->excel = new Spreadsheet();
        $this->assetsRepository = $assetsRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->assigningAssetsRepository = $assigningAssetsRepository;
        $this->productsRepository = $productsRepository;
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
        $this->locationRepository = $locationRepository;
    }

    #[Route('/ams/exports', name: 'app_exports')]
    public function index(): Response
    {
        return $this->render('exports/exports.html.twig', [
            'controller_name' => 'ExportsController',
        ]);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/assets', name: 'app_exports_assets')]
    public function exportAssets(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Assets List');
        $this->drawAssetsSheetHead($sheet);

        $this->downloadExcelFile('asset_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/vendors', name: 'app_exports_vendors')]
    public function exportVendors(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Vendors List');
        $this->drawVendorsSheetHead($sheet);

        $this->downloadExcelFile('vendor_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/assigned', name: 'app_exports_assigned')]
    public function exportAssignedAsset(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Assigned Assets List');
        $this->drawAssignedAssetsSheetHead($sheet);

        $this->downloadExcelFile('assigned_assets_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/products', name: 'app_exports_products')]
    public function exportProducts(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Products List');
        $this->drawProductsSheetHead($sheet);

        $this->downloadExcelFile('product_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/departments', name: 'app_exports_departments')]
    public function exportDepartments(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Department List');
        $this->drawDepartmentsSheetHead($sheet);

        $this->downloadExcelFile('department_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/manufacturers', name: 'app_exports_manufacturers')]
    public function exportManufactures(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('manufacturer List');
        $this->drawManufacturesSheetHead($sheet);

        $this->downloadExcelFile('manufacturer_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/employees', name: 'app_exports_employees')]
    public function exportEmployees(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('employee List');
        $this->drawEmployeesSheetHead($sheet);

        $this->downloadExcelFile('employee_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    #[Route('/ams/exports/locations', name: 'app_exports_locations')]
    public function exportLocations(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('location List');
        $this->drawLocationsSheetHead($sheet);

        $this->downloadExcelFile('location_exports', $this->excel);

        return new RedirectResponse('/ams/exports');
    }
}
