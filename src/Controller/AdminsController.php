<?php

namespace App\Controller;

use App\Entity\Common\LocationMethodsTrait;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminsController extends AbstractController
{
    use LocationMethodsTrait;
    private LocationRepository $locationRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(LocationRepository $locationRepository, EntityManagerInterface $entityManager, Security $security)
    {
        $this->locationRepository = $locationRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

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
        $request = $request->request;
        if ($request->get('id')) {
            $location = $this->locationRepository->find($request->get('id'));
            $update = true;
        } else {
            $update = false;
            if (false === empty($request->get('office-name'))
                && false === empty($request->get('country'))) {
                $location = new Location();
            } else {
                $this->addFlash('error', 'Sorry, you must have to provide Office Name & Country');
                return $this->render('admins/add-locations.html.twig', [
                    'controller_name' => 'AdminsController',
                ]);
            }
        }
        $locationData = $this->locationMethods($location, $request, $update);
        $this->entityManager->persist($locationData);
        $this->entityManager->flush();
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
    public function deleteAsset(int $id, Request $request): Response
    {
        $location = $this->locationRepository->find($id);
        $this->entityManager->remove($location);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
