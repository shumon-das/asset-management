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
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportsController extends AbstractApiController
{
    use DrawAssetsSheetHeadTrait;
    use DrawLocationsSheetHeadTrait;
    use DownloadExcelFileHeaderTrait;
    use DrawDepartmentsSheetHeadTrait;
    use DrawVendorsSheetHeadTrait;
    use DrawAssignedAssetsSheetHeadTrait;
    use DrawProductsSheetHeadTrait;
    use DrawManufacturesSheetHeadTrait;
    use DrawEmployeesSheetHeadTrait;

    #[Route('/ams/exports', name: 'app_exports')]
    public function index(): Response
    {
        return $this->render('exports/exports.html.twig');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/assets', name: 'app_exports_assets')]
    public function exportAssets(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Assets List');
        $this->drawAssetsSheetHead($sheet);

        $this->downloadExcelFile('asset_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/vendors', name: 'app_exports_vendors')]
    public function exportVendors(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Vendors List');
        $this->drawVendorsSheetHead($sheet);

        $this->downloadExcelFile('vendor_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/assigned', name: 'app_exports_assigned')]
    public function exportAssignedAsset(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Assigned Assets List');
        $this->drawAssignedAssetsSheetHead($sheet);

        $this->downloadExcelFile('assigned_assets_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/products', name: 'app_exports_products')]
    public function exportProducts(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Products List');
        $this->drawProductsSheetHead($sheet);

        $this->downloadExcelFile('product_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/departments', name: 'app_exports_departments')]
    public function exportDepartments(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('Department List');
        $this->drawDepartmentsSheetHead($sheet);

        $this->downloadExcelFile('department_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/manufacturers', name: 'app_exports_manufacturers')]
    public function exportManufactures(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('manufacturer List');
        $this->drawManufacturesSheetHead($sheet);

        $this->downloadExcelFile('manufacturer_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/employees', name: 'app_exports_employees')]
    public function exportEmployees(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('employee List');
        $this->drawEmployeesSheetHead($sheet);

        $this->downloadExcelFile('employee_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    #[Route('/ams/exports/locations', name: 'app_exports_locations')]
    public function exportLocations(): RedirectResponse
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();

        $sheet->setTitle('location List');
        $this->drawLocationsSheetHead($sheet);

        $this->downloadExcelFile('location_exports', $excel);

        return new RedirectResponse('/ams/exports');
    }
}
