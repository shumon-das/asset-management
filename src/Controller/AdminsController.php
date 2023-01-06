<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Location;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminsController extends AbstractApiController
{
    #[Route('/ams/location', name: 'app_admins_location')]
    public function index(): Response
    {
        $data = $this->locationRepository->findBy(['isDeleted' => 0]);

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
    public function saveLocation(Request $request): Response|RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
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
                ->setIsDeleted(0)
                ->setUpdatedAt(null)
                ->setDeletedAt(null)
                ->setCreatedBy($user->getId())
                ->setUpdatedBy(null)
                ->setDeletedBy(null);
            $this->entityManager->persist($location);
            $this->entityManager->flush();
            return new RedirectResponse('location');
        }

        $this->addFlash('error', 'Sorry, you must have to provide Office Name & Country');
        return $this->render('admins/add-locations.html.twig', [
            'controller_name' => 'AdminsController',
        ]);
    }

    #[Route('/ams/edit-location/{id}', name: 'admin_edit_location')]
    public function editLocation(int $id): Response
    {
        return $this->render('admins/add-locations.html.twig', [
            'location' => $this->locationRepository->find($id),
        ]);
    }

    #[Route('/ams/update-location', name: 'admin_update_location')]
    public function updateLocation(Request $request): Response|RedirectResponse
    {
        $request = $request->request;
        $location = $this->locationRepository->find($request->get('id'));
        $location
            ->setOfficName($request->get('office-name'))
            ->setCountry($request->get('country'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setZipCode($request->get('zip-code'))
            ->setContactPersonName($request->get('contact-person-name'))
            ->setAddress1($request->get('address1'))
            ->setAddress2($request->get('address2'))
            ->setUpdatedAt(new DateTimeImmutable())
            ->setUpdatedBy(1)
        ;
        $this->entityManager->persist($location);
        $this->entityManager->flush();
        return new RedirectResponse('location');
    }

    #[Route('/ams/delete-location/{id}', name: 'delete_location')]
    public function deleteAsset(int $id, Request $request): Response
    {
        $this->deleteItem($this->locationRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }
}
