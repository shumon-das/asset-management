<?php

namespace App\Controller;

use App\Common\NamesTrait;
use App\Entity\Employee;
use App\Entity\Location;
use App\Repository\AssetsRepository;
use App\Repository\AssigningAssetsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LocationRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AbstractApiController extends AbstractController
{
    public AssigningAssetsRepository $assigningAssetsRepository;
    public ProductsRepository $productsRepository;
    public VendorsRepository $vendorsRepository;
    public EmployeeRepository $employeeRepository;
    public EntityManagerInterface $entityManager;
    public Security $security;
    public AssetsRepository $assetsRepository;
    public LocationRepository $locationRepository;
    public DepartmentRepository $departmentRepository;
    public UserPasswordHasherInterface $hasher;

    /** @var Employee $user */
    public null|UserInterface $user;
    use NamesTrait;

    public function __construct(
        AssigningAssetsRepository $assigningAssetsRepository,
        ProductsRepository        $productsRepository,
        VendorsRepository         $vendorsRepository,
        AssetsRepository          $assetsRepository,
        LocationRepository        $locationRepository,
        DepartmentRepository      $departmentRepository,
        EmployeeRepository        $employeeRepository,
        EntityManagerInterface    $entityManager,
        Security                  $security,
        UserPasswordHasherInterface $hasher,
    )
    {
        $this->assigningAssetsRepository = $assigningAssetsRepository;
        $this->productsRepository = $productsRepository;
        $this->vendorsRepository = $vendorsRepository;
        $this->employeeRepository = $employeeRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->assetsRepository = $assetsRepository;
        $this->locationRepository = $locationRepository;
        $this->departmentRepository = $departmentRepository;
        $this->hasher = $hasher;
        $this->user = $this->security->getUser();
    }

    public function getRepositoriesData(): array
    {
        return [
            'products' => $this->productsRepository->findBy(['isDeleted' => 0]),
            'vendors' => $this->vendorsRepository->findBy(['isDeleted' => 0]),
            'users' => $this->employeeRepository->findBy(['isDeleted' => 0]),
            'assets' => $this->assetsRepository->findBy(['isDeleted' => 0]),
            'locations' => $this->locationRepository->findBy(['isDeleted' => 0]),
            'departments' => $this->departmentRepository->findBy(['isDeleted' => 0]),
        ];
    }

    protected function persistData(Location $entityData): RedirectResponse
    {
        $this->entityManager->persist($entityData);
        $this->entityManager->flush();
        return new RedirectResponse('location');
    }

    public function deleteItem(mixed $repository, int $id, bool $permanently = false): bool
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $item = $repository->find($id);
        $this->entityManager->remove($item);
        $item
            ->setIsDeleted(1)
            ->setDeletedAt(new \DateTimeImmutable())
            ->setDeletedBy($user->getId())
        ;
        true === $permanently
            ? $this->entityManager->remove($item)
            : $this->entityManager->persist($item);
        $this->entityManager->flush();
        return true;
    }
}