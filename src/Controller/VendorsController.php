<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Vendors;
use App\Repository\EmployeeRepository;
use App\Repository\VendorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class VendorsController extends AbstractController
{
    private VendorsRepository $vendorsRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private EmployeeRepository $employeeRepository;

    public function __construct(
        VendorsRepository $vendorsRepository,
        EntityManagerInterface $entityManager,
        Security $security,
        EmployeeRepository $employeeRepository
    ){
        $this->vendorsRepository = $vendorsRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->employeeRepository = $employeeRepository;
    }

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
            ->setCreatedBy($user->getId())
        ;

        $this->entityManager->persist($vendor);
        $this->entityManager->flush();

        return new RedirectResponse('vendors');
    }

    #[Route('/ams/update-vendor', name: 'app_update_vendor')]
    public function updateVendor(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $vendor = $this->vendorsRepository->find($request->get('id'));
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
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setUpdatedBy($user->getId())
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
        /** @var Employee $user */
        $user = $this->security->getUser();
        $vendor = $this->vendorsRepository->find($id);
        if(false === empty($vendor)) {
            $vendor->setIsDeleted(1)
                ->setDeletedAt(new \DateTimeImmutable())
                ->setDeletedBy($user->getId())
            ;
            $this->entityManager->persist($vendor);
            $this->entityManager->flush();
        }
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/ams/delete-vendor-permanently/{id}', name: 'delete_vendor_permanently')]
    public function deletePermanently($id, Request $request): Response
    {
        $record = $this->vendorsRepository->find($id);
        if(false === empty($record)) {
            $this->entityManager->remove($record);
            $this->entityManager->flush();
        }
        $route = $request->headers->get('referer');
        return $this->redirect($route);
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
