<?php

namespace App\Controller;

use App\Common\NamesTrait;
use App\Entity\Employee;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractApiController
{
    use NamesTrait;
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
        $locations = $this->locationRepository->findAll();
        $departments = $this->departmentRepository->findAll();
        $employees = $this->employeeRepository->findAll();
        return $this->render('employees/add-employee.html.twig', [
            'locations' => $locations,
            'departments' => $departments,
            'employees' => $employees,
        ]);
    }

    #[Route('/ams/save-employee', name: 'app_save_employee')]
    public function saveEmployee(Request $request): RedirectResponse|Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $email = $request->get('email');
        $password = $request->get('password');
        $employee = new Employee();
        $hashedPassword = $this->hasher->hashPassword($employee, $password);
        $existEmail = $this->employeeRepository->findOneBy(['email' => $email]);

        if (false === empty($email) && false === empty($password)) {
            if($existEmail?->getEmail() < 1) {
                $employee
                    ->setName($request->get('name'))
                    ->setEmail($email)
                    ->setPassword($hashedPassword)
                    ->setLocation($request->get('location'))
                    ->setContactNo($request->get('contact-no'))
                    ->setDepartment($request->get('department'))
                    ->setReportingManager($request->get('reporting-manager'))
                    ->setRoles(['ROLE_USER'])
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setCreatedBy($user->getId())
                    ->setIsDeleted(0)
                ;
                $this->entityManager->persist($employee);
                $this->entityManager->flush();

                return new RedirectResponse('employees');
            }

            $this->addFlash("error", "Sorry, ".$email." already exists. please try with another new email" );

            return new RedirectResponse('add-employee');
        }
        $this->addFlash("error", "Sorry, you must have to fill email and password field");

        return $this->render('employees/add-employee.html.twig', [
            'controller_name' => 'EmployeesController',
        ]);
    }

    #[Route('/ams/delete-employee/{id}', name: 'delete_employee')]
    public function deleteEmployee(int $id, Request $request): Response
    {
        $this->deleteItem($this->employeeRepository, $id);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/delete-employee-permanently/{id}', name: 'delete_employee_permanently')]
    public function deleteEmployeePermanently($id, Request $request): Response
    {
        $this->deleteItem($this->employeeRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }
}
