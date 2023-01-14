<?php

namespace App\Controller;

use App\Entity\Methods\DepartmentMethodsTrait;
use App\Entity\Department;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentsController extends AbstractApiController
{
    use DepartmentMethodsTrait;
    #[Route('/ams/departments', name: 'app_departments')]
    public function departments(): Response
    {
        $employeeIds = $this->employeeRepository->findIds();
        $ids = array_values(array_column($employeeIds, 'id'));
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);
        foreach ($departments as $key => $department) {
            $departments[$key] = $this->getDepartments($department, $ids);
        }
        return $this->render('departments/departments.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/add-department', name: 'app_add_department')]
    public function addDepartment(Request $request): Response
    {
        return $this->render('departments/add-department.html.twig');
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/save-department', name: 'app_save_department')]
    public function saveDepartment(Request $request): RedirectResponse|Response
    {
        $this->departmentMethods(new Department(), $request);
        return new RedirectResponse('departments');
    }

    #[Route('/ams/delete-department/{id}', name: 'delete_department')]
    public function deleteDepartment(int $id, Request $request): Response
    {
        $this->deleteItem($this->departmentRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }

    private function getDepartments(Department $department, array $ids): array
    {
        $names = $this->allEntityIdsAndNames();
        return [
            'id' => $department->getId(),
            'departmentName' => $department->getDepartmentName(),
            'contactPerson' => $department->getContactPerson(),
            'contactPersonEmail' => $department->getContactPersonEmail(),
            'contactPersonPhone' => $department->getContactPersonPhone(),
            'createdAt' => $department->getCreatedAt()->format('Y-M-d'),
            'createdBy' => $names['employeesIds'][$department->getCreatedBy()],
            'use' => in_array($department->getId(), $ids),
        ];
    }
}
