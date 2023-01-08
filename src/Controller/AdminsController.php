<?php

namespace App\Controller;

use App\Entity\Common\LocationMethodsTrait;
use App\Entity\Location;
use App\Entity\Vendors;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminsController extends AbstractApiController
{
    use LocationMethodsTrait;
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

    /**
     * @throws Exception
     */
    #[Route('/ams/save-location', name: 'admins_save_location')]
    public function saveLocation(Request $request): Response|RedirectResponse
    {
        $id = $request->request->get('id');
        $id
            ? $this->locationMethods($this->locationRepository->find($id), $request, true)
            : $this->locationMethods(new Location(), $request);
        return new RedirectResponse('location');
    }

    #[Route('/ams/edit-location/{id}', name: 'admin_edit_location')]
    public function editLocation(int $id): Response
    {
        return $this->render('admins/add-locations.html.twig', [
            'location' => $this->locationRepository->find($id),
        ]);
    }

    #[Route('/ams/delete-location/{id}', name: 'delete_location')]
    public function deleteLocation(int $id, Request $request): Response
    {
        $location = $this->locationRepository->find($id);
        $this->entityManager->remove($location);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
