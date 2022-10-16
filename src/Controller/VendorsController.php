<?php

namespace App\Controller;

use App\Entity\Vendors;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VendorsController extends AbstractController
{
    #[Route('/vendors', name: 'app_vendors')]
    public function vendors(): Response
    {
        return $this->render('vendors/vendors.html.twig', [
            'controller_name' => 'VendorsController',
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
            ->setContactPerson($request->get('contact-person'))
            ->setEmail($request->get('vendor-email'))
            ->setCreatedAt(new \DateTimeImmutable())
        ;

        $entityManager->persist($vendor);
        $entityManager->flush();

        return new RedirectResponse('vendors');
    }
}
