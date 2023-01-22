<?php

namespace App\Controller;

use App\Entity\Methods\VendorMethodsTrait;
use App\Entity\Employee;
use App\Entity\Vendors;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorsController extends AbstractApiController
{
    use VendorMethodsTrait;

    #[Route('/ams/vendors', name: 'app_vendors')]
    public function vendors(): Response
    {
        $vendors = $this->vendorsRepository->findBy(['isDeleted' => 0]);

        return $this->render('vendors/vendors.html.twig', [
            'vendors' => $vendors,
        ]);
    }

    #[Route('/ams/add-vendor', name: 'app_add_vendor')]
    public function addVendor(): Response
    {
        return $this->render('vendors/add-vendor.html.twig', [
            'controller_name' => 'VendorsController',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/save-vendor', name: 'app_save_vendor')]
    public function saveVendor(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $result = $id
            ? $this->vendorMethods($this->vendorsRepository->find($id), $request, true)
            : $this->vendorMethods(new Vendors(), $request);

        $this->addFlash('message', $result);
        return new RedirectResponse('add-vendor');
    }

    #[Route('/ams/view-vendor/{id}', name: 'view_vendor')]
    public function viewVendor(int $id): Response
    {
        $vendor = $this->vendorsRepository->find($id);
        return $this->render('vendors/view-vendor.html.twig', [
            'vendor' => $vendor,
            'createdAt' => $vendor->getCreatedAt()->format('Y-M-d'),
            'createdBy' => ucwords($this->employeeRepository->find($vendor->getCreatedBy())->getName()),
        ]);
    }

    #[Route('/ams/edit-vendor/{id}', name: 'edit_vendor')]
    public function editVendor(int $id): Response
    {
        $vendor = $this->vendorsRepository->find($id);
        return $this->render('vendors/add-vendor.html.twig', [
            'vendor' => $vendor,
        ]);
    }

    #[Route('/ams/delete-vendor/{id}', name: 'delete_vendor')]
    public function deleteVendor($id, Request $request): Response
    {
        $result = $this->deleteItem($this->vendorsRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/delete-vendor-permanently/{id}', name: 'delete_vendor_permanently')]
    public function deletePermanently($id, Request $request): Response
    {
        $result = $this->deleteItem($this->vendorsRepository, $id, true);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }
}
