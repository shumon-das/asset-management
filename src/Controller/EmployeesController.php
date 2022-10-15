<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractController
{
    #[Route('/employees', name: 'app_employees')]
    public function employee(): Response
    {
        return $this->render('employees/employees.html.twig', [
            'controller_name' => 'EmployeesController',
        ]);
    }

    #[Route('/add-employee', name: 'app_add_employee')]
    public function addEmployee(): Response
    {
        return $this->render('employees/add-employee.html.twig', [
            'controller_name' => 'EmployeesController',
        ]);
    }
}
