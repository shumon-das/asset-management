<?php

namespace App\Controller;

use App\Common\NamesTrait;
use App\Entity\Methods\EmployeeMethodsTrait;
use App\Entity\Employee;
use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeesController extends AbstractApiController
{
    use EmployeeMethodsTrait;
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

    /**
     * @throws Exception
     */
    #[Route('/ams/save-employee', name: 'app_save_employee')]
    public function saveEmployee(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $result = $id
            ? $this->employeeMethods($this->employeeRepository->find($id), $request, true)
            : $this->employeeMethods(new Employee(), $request);

        $this->addFlash('message', $result);
        return new RedirectResponse('add-employee');
    }

    #[Route('/ams/edit-employee/{id}', name: 'edit_employee')]
    public function editEmployee(int $id, Request $request): Response
    {

        $employee = $this->employeeRepository->find($id);
        $locations = $this->locationRepository->findAll();
        $departments = $this->departmentRepository->findAll();
        $employees = $this->employeeRepository->findAll();
        $names = $this->allEntityIdsAndNames();
        return $this->render('employees/add-employee.html.twig', [
            'locations' => $locations,
            'departments' => $departments,
            'employees' => $employees,
            'employee' => $employee,
            'departmentName' => $names['departmentsIds'][$employee->getDepartment()],
            'locationName' => $names['locationsIds'][$employee->getLocation()],
            'reportingManager' => $names['employeesIds'][$employee->getReportingManager()],
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
