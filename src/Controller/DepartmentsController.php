<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractController
{
    #[Route('/departments', name: 'app_departments')]
    public function departments(): Response
    {
        return $this->render('departments/departments.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }

    #[Route('/add-department', name: 'app_add_department')]
    public function addDepartment(): Response
    {
        return $this->render('departments/add-department.html.twig', [
            'controller_name' => 'DepartmentsController',
        ]);
    }
}
