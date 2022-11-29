<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager)
    {
        $this->employeeRepository = $employeeRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/ams/employees', name: 'app_employees')]
    public function employee(): Response
    {
        $employees = $this->employeeRepository->findBy(['isDeleted' => 0]);

        return $this->render('employees/employees.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/ams/add-employee', name: 'app_add_employee')]
    public function addEmployee(): Response
    {
        return $this->render('employees/add-employee.html.twig', [
            'controller_name' => 'EmployeesController',
        ]);
    }

    #[Route('/ams/delete-employee/{id}', name: 'delete_employee')]
    public function deleteAsset(int $id, Request $request): Response
    {
        $location = $this->employeeRepository->find($id);
        $location->setIsDeleted(1);
        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
