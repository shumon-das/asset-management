<?php

namespace App\Controller;

use App\Entity\AssigningAssets;
use App\Entity\Employee;
use App\Repository\AssetsRepository;
use App\Repository\AssigningAssetsRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Repository\LocationRepository;
use App\Repository\ProductsRepository;
use App\Repository\VendorsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AssigningController extends AbstractController
{
    private AssigningAssetsRepository $assigningAssetsRepository;
    private ProductsRepository $productsRepository;
    private VendorsRepository $vendorsRepository;
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private AssetsRepository $assetsRepository;
    private LocationRepository $locationRepository;
    private DepartmentRepository $departmentRepository;

    public function __construct(
        AssigningAssetsRepository $assigningAssetsRepository,
        ProductsRepository        $productsRepository,
        VendorsRepository         $vendorsRepository,
        AssetsRepository          $assetsRepository,
        LocationRepository        $locationRepository,
        DepartmentRepository      $departmentRepository,
        EmployeeRepository        $employeeRepository,
        EntityManagerInterface    $entityManager,
        Security                  $security
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
    }

    #[Route('/ams/assigned', name: 'assigned_assets')]
    public function assignedAsset(AssigningAssetsRepository $assigningAssetsRepository): Response
    {
        $assignedProduct = $assigningAssetsRepository->findBy(['isDeleted' => 0]);
        $data = [];
        foreach ($assignedProduct as $product) {
            $data[$product->getId()] = $this->assignedData($product);
        }

        return $this->render('assets/assigned-asset-list.html.twig', [
            'assets' => $data
        ]);
    }

    #[Route('/ams/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        $products = $this->productsRepository->findBy(['isDeleted' => 0]);
        $vendors = $this->vendorsRepository->findBy(['isDeleted' => 0]);
        $employee = $this->employeeRepository->findBy(['isDeleted' => 0]);
        $assets = $this->assetsRepository->findBy(['isDeleted' => 0]);
        $locations = $this->locationRepository->findBy(['isDeleted' => 0]);
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);

        return $this->render('assets/assigning-asset.html.twig', [
            'products' => $products,
            'vendors' => $vendors,
            'users' => $employee,
            'assets' => $assets,
            'locations' => $locations,
            'departments' => $departments,
        ]);
    }

    #[Route('/ams/view-assigned/{id}', name: 'view_assigned_asset')]
    public function viewAssignedAsset(int $id): Response
    {
        $asset = $this->assigningAssetsRepository->find($id);
        return $this->render('assets/view-assigned.html.twig', [
            'asset' => $this->assignedAssetData($asset)
        ]);
    }

    #[Route('/ams/delete-assigned/{id}', name: 'delete_assigned')]
    public function deleteAssigned(int $id, Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $assignedAsset = $this->assigningAssetsRepository->find($id);
        $assignedAsset->setIsDeleted($user->getId())
            ->setDeletedAt(new DateTimeImmutable());
        $this->entityManager->persist($assignedAsset);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/save-assign-asset', name: 'save_assign_asset')]
    public function saveAssignAsset(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $assignAsset = new AssigningAssets();
        $assignAsset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product-name'))
            ->setVendor($request->get('vendor'))
            ->setLocation($request->get('location'))
            ->setAssetName($request->get('asset-name'))
            ->setDepartment($request->get('department'))
            ->setAssignTo($request->get('assign-to'))
            ->setDescription($request->get('description'))
//            ->setAssignComponent($request->get(''))
            ->setIsDeleted(0)
            ->setCreatedBy($user->getId())
            ->setUpdatedBy(null)
            ->setDeletedBy(null)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null)
            ->setStatus(true);
        $this->entityManager->persist($assignAsset);
        $this->entityManager->flush();

        return new RedirectResponse('assigned');
    }

    #[Route('/ams/edit/assigning/{id}', name: 'edit_assigned_asset')]
    public function editAssigningAsset(int $id): Response
    {
        $assignedAsset = $this->assigningAssetsRepository->find($id);
        $products = $this->productsRepository->findBy(['isDeleted' => 0]);
        $vendors = $this->vendorsRepository->findBy(['isDeleted' => 0]);
        $employee = $this->employeeRepository->findBy(['isDeleted' => 0]);
        $assets = $this->assetsRepository->findBy(['isDeleted' => 0]);
        $locations = $this->locationRepository->findBy(['isDeleted' => 0]);
        $departments = $this->departmentRepository->findBy(['isDeleted' => 0]);

        return $this->render('assets/assigning-asset.html.twig', [
            'assignedAsset' => $this->assignedData($assignedAsset),
            'products' => $products,
            'vendors' => $vendors,
            'users' => $employee,
            'assets' => $assets,
            'locations' => $locations,
            'departments' => $departments,
        ]);
    }

    #[Route('/ams/update-assigned-asset', name: 'update_assigned_asset')]
    public function updateAssignedAsset(Request $request): RedirectResponse
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $request = $request->request;
        $assignAsset = $this->assigningAssetsRepository->find($request->get('id'));
        $assignAsset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product-name'))
            ->setVendor($request->get('vendor'))
            ->setLocation($request->get('location'))
            ->setAssetName($request->get('asset-name'))
            ->setDepartment($request->get('department'))
            ->setAssignTo($request->get('assign-to'))
            ->setDescription($request->get('description'))
            ->setUpdatedBy($user->getId())
            ->setUpdatedAt(new DateTimeImmutable());
        $this->entityManager->persist($assignAsset);
        $this->entityManager->flush();

        return new RedirectResponse('assigned');
    }

    private function assignedData(?AssigningAssets $assignedProduct): array
    {
        $vendor = $this->vendorsRepository->find($assignedProduct->getVendor());
        $assigned = $this->employeeRepository->find($assignedProduct->getAssignTo());
        return [
            'id' => $assignedProduct->getId(),
            'assetName' => $this->assetsRepository->findOneBy(['id' => $assignedProduct->getAssetName()])->getAssetName(),
            'assetId' => $assignedProduct->getAssetName(),
            'department' => $assignedProduct->getDepartment(),
            'departmentName' => $this->departmentRepository->findOneBy(['id' => $assignedProduct->getDepartment()])?->getDepartmentName(),
            'location' => $this->locationRepository->findOneBy(['id' => $assignedProduct->getLocation()])->getOfficName(),
            'locationId' => $assignedProduct->getLocation(),
            'assigned' => $assigned?->getName(),
            'assignedId' =>$assignedProduct->getAssignTo(),
            'vendor' => $vendor?->getVendorName(),
            'vendorId' => $assignedProduct->getVendor(),
            'currentState' => 'current state',
            'status' => $assignedProduct->isStatus() ? 'Assigned' : 'Not Assigned',
        ];
    }

    private function assignedAssetData(?AssigningAssets $asset): array
    {
        $vendor = $this->vendorsRepository->find($asset->getVendor());
        return [
            'id' => $asset->getId(),
            'productCategory' => $asset->getProductCategory(),
            'productType' => $asset->getProductType(),
            'product' => $asset->getProduct(),
            'vendor' => $vendor->getVendorName(),
            'vendorId' => $asset->getVendor(),
            'location' => $this->locationRepository->findOneBy(['id' => $asset->getLocation()])->getOfficName(),
            'assetName' => $asset->getAssetName(),
            'department' => $this->departmentRepository->findOneBy(['id' => $asset->getDepartment()])?->getDepartmentName(),
            'assigned' => $this->employeeRepository->find($asset->getAssignTo())?->getName(),
            'description' => $asset->getDescription(),
            'createdBy' => ucwords($this->employeeRepository->find($asset->getCreatedBy())->getName()),
            'status' => $asset->isStatus(),
            'currentState' => 'current state',
            'createdAt' => $asset->getCreatedAt()->format('Y-M-d'),
        ];
    }
}