<?php

namespace App\Controller;

use App\Repository\AssetsRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportsController extends AbstractController
{
    private Spreadsheet $excel;
    private AssetsRepository $assetsRepository;

    public function __construct(AssetsRepository $assetsRepository)
    {
        $this->excel = new Spreadsheet();
        $this->assetsRepository = $assetsRepository;
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
     */
    #[Route('/ams/exports/assets', name: 'app_exports_assets')]
    public function exportAssets(): RedirectResponse
    {
        $sheet = $this->excel->getActiveSheet();

        $sheet->setTitle('Assets List');
        $this->drawSheetHead($sheet);

        $this->downloadExcelFile('asset_exports');

        return new RedirectResponse('/ams/exports');
    }

    /**
     * @throws Exception
     */
    private function drawSheetHead(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setWidth(30);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getCell('A1')->setValue('ID');
        $sheet->getCell('B1')->setValue('Product Category');
        $sheet->getCell('C1')->setValue('Product Type');
        $sheet->getCell('D1')->setValue('Product');
        $sheet->getCell('E1')->setValue('Vendor');
        $sheet->getCell('F1')->setValue('Asset Name');
        $sheet->getCell('G1')->setValue('Serial Number');
        $sheet->getCell('H1')->setValue('Price');
        $sheet->getCell('I1')->setValue('Description Type');
        $sheet->getCell('J1')->setValue('Location');
        $sheet->getCell('K1')->setValue('Purchase Date');
        $sheet->getCell('L1')->setValue('Warranty Expire Date');
        $sheet->getCell('M1')->setValue('Description');
        $sheet->getCell('N1')->setValue('Useful Life');
        $sheet->getCell('O1')->setValue('Residual Value');
        $sheet->getCell('P1')->setValue('Rate');

        $assets = $this->assetsRepository->findAll();
        $rowAct = 3;
        foreach ($assets as $key => $row) {
            $sheet->getCell('A'.$rowAct)->setValue('#'.$row->getId());
            $sheet->getCell('B'.$rowAct)->setValue($row->getProductCategory());
            $sheet->getCell('C'.$rowAct)->setValue($row->getProductType());
            $sheet->getCell('D'.$rowAct)->setValue($row->getProduct());
            $sheet->getCell('E'.$rowAct)->setValue($row->getVendor());
            $sheet->getCell('F'.$rowAct)->setValue($row->getAssetName());
            $sheet->getCell('G'.$rowAct)->setValue($row->getSeriulNumber());
            $sheet->getCell('H'.$rowAct)->setValue($row->getPrice());
            $sheet->getCell('I'.$rowAct)->setValue($row->getDescriptionType());
            $sheet->getCell('J'.$rowAct)->setValue($row->getLocation());
            $sheet->getCell('K'.$rowAct)->setValue($row->getPurchaseDate()?->format('d-M-Y'));
            $sheet->getCell('L'.$rowAct)->setValue($row->getWarrantyExpiryDate()?->format('d-M-Y'));
            $sheet->getCell('M'.$rowAct)->setValue($row->getDescription());
            $sheet->getCell('N'.$rowAct)->setValue($row->getUsefulLife());
            $sheet->getCell('O'.$rowAct)->setValue($row->getResidualValue());
            $sheet->getCell('P'.$rowAct)->setValue($row->getRate());

            $rowAct++;
        }
    }


    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadExcelFile(string $name)
    {
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->excel->setActiveSheetIndex(0);

        $filename = $name.date('Y-m-d').'.xlsx';

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->excel, 'Xlsx');
        $objWriter->save('php://output');
        exit;
    }

}
