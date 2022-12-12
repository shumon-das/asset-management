<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LocationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EmployeesController extends AbstractController
{
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private LocationRepository $locationRepository;
    private DepartmentRepository $departmentRepository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(
        EmployeeRepository          $employeeRepository,
        LocationRepository          $locationRepository,
        DepartmentRepository        $departmentRepository,
        EntityManagerInterface      $entityManager,
        Security                    $security,
        UserPasswordHasherInterface $hasher
    )
    {
        $this->employeeRepository = $employeeRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->locationRepository = $locationRepository;
        $this->departmentRepository = $departmentRepository;
        $this->hasher = $hasher;
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
        $locations = $this->locationRepository->findBy(['isDeleted' => 0]);
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);
        $employees = $this->employeeRepository->findBy(['isDeleted' => 0]);
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
                    ->setUpdatedAt(null)
                    ->setUpdatedBy(null);
                $this->entityManager->persist($employee);
                $this->entityManager->flush();
            }

            $this->addFlash("error", "Sorry, ".$email." already exists. please try with another new email" );

            return new RedirectResponse('employees');
        }
        $this->addFlash("error", "Sorry, you must have to fill email and password field");

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
