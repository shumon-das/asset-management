<?php

namespace App\Controller;

use App\Entity\Common\DepartmentMethodsTrait;
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
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);

        return $this->render('departments/departments.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/add-department', name: 'app_add_department')]
    public function addDepartment(Request $request): RedirectResponse|Response
    {
        $this->departmentMethods(new Department(), $request);
//        $this->addFlash('errors', 'department name should not be empty');
        return new RedirectResponse('departments');
    }

    #[Route('/ams/delete-department/{id}', name: 'delete_department')]
    public function deleteDepartment(int $id, Request $request): Response
    {
        $this->deleteItem($this->departmentRepository, $id, true);
        return $this->redirect($request->headers->get('referer'));
    }
}
