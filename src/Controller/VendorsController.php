<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Vendors;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorsController extends AbstractApiController
{
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

    #[Route('/ams/save-vendor', name: 'app_save_vendor')]
    public function saveVendor(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        if($request->get('id')) {
            $vendor = $this->vendorsRepository->find($request->get('id'));
            $vendor
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setUpdatedBy($user->getId());
        } else {
            $vendor = new Vendors();
        }
        $vendor
            ->setVendorName($request->get('vendor-name'))
            ->setEmail($request->get('vendor-email'))
            ->setPhone($request->get('phone'))
            ->setContactPerson($request->get('contact-person'))
            ->setDesignation($request->get('designation'))
            ->setCountry($request->get('country'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setZipCode($request->get('zip-code'))
            ->setGstinNo($request->get('gstin-no'))
            ->setAddress($request->get('address'))
            ->setDescription($request->get('description'))
            ->setStatus(false)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setDeletedBy(null)
            ->setCreatedBy($user->getId())
        ;

        $this->entityManager->persist($vendor);
        $this->entityManager->flush();

        return new RedirectResponse('vendors');
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
        $this->deleteItem($this->vendorsRepository, $id);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/delete-vendor-permanently/{id}', name: 'delete_vendor_permanently')]
    public function deletePermanently($id, Request $request): Response
    {
        $this->deleteItem($this->vendorsRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }
}
