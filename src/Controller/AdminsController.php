<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminsController extends AbstractController
{
    #[Route('/ams/location', name: 'app_admins_location')]
    public function index(LocationRepository $locationRepository): Response
    {
        $data = $locationRepository->findAll();

        return $this->render('admins/locations.html.twig', [
            'locations' => $data,
        ]);
    }

    #[Route('/ams/add-location', name: 'admins_add_location')]
    public function addLocation(): Response
    {
        return $this->render('admins/add-locations.html.twig', [
            'controller_name' => 'AdminsController',
        ]);
    }

    #[Route('/ams/save-location', name: 'admins_save_location')]
    public function saveLocation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $request = $request->request;
        if (false === empty($request->get('office-name'))
            && false === empty($request->get('country'))) {
            $location = new Location();
            $location
                ->setOfficName($request->get('office-name'))
                ->setCountry($request->get('country'))
                ->setState($request->get('state'))
                ->setCity($request->get('city'))
                ->setZipCode($request->get('zip-code'))
                ->setContactPersonName($request->get('contact-person-name'))
                ->setAddress1($request->get('address1'))
                ->setAddress2($request->get('address2'))
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(null)
                ->setDeletedAt(null)
                ->setCreatedBy(1)
                ->setUpdatedBy(null)
                ->setDeletedBy(null);
            $entityManager->persist($location);
            $entityManager->flush();
            return $this->render('admins/locations.html.twig', [
                'controller_name' => 'AdminsController',
            ]);
        }

        $this->addFlash('error', 'Sorry, you must have to provide Office Name & Country');
        return $this->render('admins/add-locations.html.twig', [
            'controller_name' => 'AdminsController',
        ]);
    }
}
