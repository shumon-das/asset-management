<?php

namespace App\Controller;

use App\Entity\Vendors;
use App\Repository\VendorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorsController extends AbstractController
{
    private VendorsRepository $vendorsRepository;

    public function __construct(VendorsRepository $vendorsRepository)
    {
        $this->vendorsRepository = $vendorsRepository;
    }

    #[Route('/ams/vendors', name: 'app_vendors')]
    public function vendors(): Response
    {
        $vendors = $this->vendorsRepository->findAll();

        return $this->render('vendors/vendors.html.twig', [
            'vendors' => $vendors,
        ]);
    }

    #[Route('/add-vendor', name: 'app_add_vendor')]
    public function addVendor(): Response
    {
        return $this->render('vendors/add-vendor.html.twig', [
            'controller_name' => 'VendorsController',
        ]);
    }

    #[Route('/save-vendor', name: 'app_save_vendor')]
    public function saveVendor(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $request = $request->request;
        $vendor = new Vendors();
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
            ->setCreatedBy(1)
        ;

        $entityManager->persist($vendor);
        $entityManager->flush();

        return new RedirectResponse('vendors');
    }

    #[Route('/view-vendor/{id}', name: 'view_vendor')]
    public function viewVendor(int $id): Response
    {
        $vendor = $this->vendorsRepository->find($id);

        return $this->render('vendors/view-vendor.html.twig', [
            'vendor' => $this->vendorData($vendor),
        ]);
    }

    private function vendorData(?Vendors $vendor): array
    {
        return [
            'id' => $vendor->getId(),
            'vendorName' => $vendor->getVendorName(),
            'contactPerson' => $vendor->getContactPerson(),
            'email' => $vendor->getEmail(),
            'gstinNo' => $vendor->getGstinNo(),
            'phone' => $vendor->getPhone(),
            'status' => $vendor->isStatus(),
            'createdAt' => $vendor->getCreatedAt()->format('Y-M-d'),
            'createdBy' => $vendor->getCreatedBy(),
            'designation' => $vendor->getDescription(),
            'country' => $vendor->getCountry(),
            'state' => $vendor->getState(),
            'city' => $vendor->getCity(),
            'zipCode' => $vendor->getZipCode(),
            'address' => $vendor->getAddress(),
            'description' => $vendor->getDescription(),
        ];
    }
}
