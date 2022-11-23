<?php

namespace App\Controller;

use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractController
{
    #[Route('/ams/departments', name: 'app_departments')]
    public function departments(): Response
    {
        return $this->render('departments/departments.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }

    #[Route('/ams/add-department', name: 'app_add_department')]
    public function addDepartment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $request = $request->request;
        if (false === empty($request->get('departmentName'))) {
            $department = new Department();
            $department
                ->setDepartmentName($request->get('departmentName'))
                ->setContactPerson($request->get('contactPerson'))
                ->setContactPersonEmail($request->get('contactPersonEmail'))
                ->setContactPersonPhone($request->get('contactPersonPhone'));
            $entityManager->persist($department);
            $entityManager->flush();
            return $this->render('departments/departments.html.twig', [
                'controller_name' => 'DepartmentsController',
            ]);
        }
        return $this->render('departments/add-department.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }
}
