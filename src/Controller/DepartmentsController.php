<?php

namespace App\Controller;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractController
{
    private DepartmentRepository $departmentRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(DepartmentRepository $departmentRepository, EntityManagerInterface $entityManager)
    {
        $this->departmentRepository = $departmentRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/ams/departments', name: 'app_departments')]
    public function departments(): Response
    {
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);

        return $this->render('departments/departments.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/ams/add-department', name: 'app_add_department')]
    public function addDepartment(Request $request): Response
    {
        $request = $request->request;
        if (false === empty($request->get('departmentName'))) {
            $department = new Department();
            $department
                ->setDepartmentName($request->get('departmentName'))
                ->setContactPerson($request->get('contactPerson'))
                ->setContactPersonEmail($request->get('contactPersonEmail'))
                ->setContactPersonPhone($request->get('contactPersonPhone'));
            $this->entityManager->persist($department);
            $this->entityManager->flush();
            return $this->render('departments/departments.html.twig', [
                'controller_name' => 'DepartmentsController',
            ]);
        }
        return $this->render('departments/add-department.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }

    #[Route('/ams/delete-department/{id}', name: 'delete_department')]
    public function deleteAsset(int $id, Request $request): Response
    {
        $location = $this->departmentRepository->find($id);
        $location->setIsDeleted(1);
        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
