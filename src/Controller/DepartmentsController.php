<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Employee;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractApiController
{
    #[Route('/ams/departments', name: 'app_departments')]
    public function departments(): Response
    {
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);

        return $this->render('departments/departments.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/ams/add-department', name: 'app_add_department')]
    public function addDepartment(Request $request): RedirectResponse|Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        if (false === empty($request->get('departmentName'))) {
            $department = new Department();
            $department
                ->setDepartmentName($request->get('departmentName'))
                ->setContactPerson($request->get('contactPerson'))
                ->setContactPersonEmail($request->get('contactPersonEmail'))
                ->setContactPersonPhone($request->get('contactPersonPhone'))
                ->setCreatedBy($user->getCreatedBy())
                ->setCreatedAt(new DateTimeImmutable())
                ->setIsDeleted(0)
            ;
            $this->entityManager->persist($department);
            $this->entityManager->flush();
            return new RedirectResponse('departments');
        }

        $this->addFlash('errors', 'department name should not be empty');
        return $this->render('departments/add-department.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }

    #[Route('/ams/delete-department/{id}', name: 'delete_department')]
    public function deleteDepartment(int $id, Request $request): Response
    {
        $this->permanentlyDeleteItem($this->departmentRepository, $id);
        return $this->redirect($request->headers->get('referer'));
    }
}
