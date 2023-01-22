<?php

namespace App\Controller;

use App\Common\Asset\AssetListDataTrait;
use App\Common\Product\ProductDataTrait;
use App\Entity\Employee;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecycleBinController extends AbstractApiController
{
    use ProductDataTrait;
    use AssetListDataTrait;

    #[Route('/ams/recycle/vendors', name: 'app_recycle_vendors')]
    public function recycleVendors(): Response
    {
        return $this->render(
            'vendors/vendors.html.twig', [
                'vendors' => $this->vendorsRepository->findBy(['isDeleted' => 1]),
            'recycle' => 'recycle'
        ]);
    }

    #[Route('/ams/revert-vendor/{id}', name: 'revert_vendor')]
    public function revertVendor($id, Request $request): Response
    {
        $result = $this->revertItem($this->vendorsRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/recycle/assets', name: 'app_recycle_assets')]
    public function recycleAssets(): Response
    {
        $assets = $this->assetsRepository->findBy(['isDeleted' => 1]);
        $assignedAssetIds = array_column($this->assigningAssetsRepository->findIds(), 'id');
        $data = [];
        foreach ($assets as $asset) {
            $data[$asset->getId()] = $this->assetsListData($assignedAssetIds, $asset);
        }

        return $this->render('assets/asset-list.html.twig', [
            'assets' => $data,
            'recycle' => 'recycle'
        ]);
    }

    #[Route('/ams/revert-asset/{id}', name: 'revert_asset')]
    public function revertAsset(int $id, Request $request): Response
    {
        $result = $this->revertItem($this->assetsRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/recycle/products', name: 'app_recycle_products')]
    public function recycleProducts(): Response
    {
        $products = $this->productsRepository->findBy(['isDeleted' => 1]);
        foreach ($products as $key => $row) {
            $products[$key] = $this->productData($row);
        }

        return $this->render('products/products.html.twig', [
            'products' => $products,
            'recycle' => 'recycle'
        ]);
    }

    #[Route('/ams/revert-product/{id}', name: 'revert_product')]
    public function revertProduct(int $id, Request $request): Response
    {
        $result = $this->revertItem($this->productsRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/recycle/employees', name: 'app_recycle_employees')]
    public function employee(): Response
    {
        return $this->returnResponseWithData(
            $this->employeeRepository,
            'employees/employees.html.twig',
            true
        );
    }

    #[Route('/ams/revert-employee/{id}', name: 'revert_employee')]
    public function deleteEmployee(int $id, Request $request): Response
    {
        $result = $this->revertItem($this->employeeRepository, $id);

        $this->addFlash('message', $result);
        return $this->redirect($request->headers->get('referer'));
    }

    private function revertItem($repository, $id): array
    {
        try {
            /** @var Employee $user */
            $user = $this->security->getUser();
            $item = $repository->find($id);
            $item
                ->setIsDeleted(0)
                ->setDeletedBy($user->getId())
                ->setDeletedAt(new DateTimeImmutable())
            ;
            $this->entityManager->persist($item);
            $this->entityManager->flush();
            return ['success' => 'Item reverted successfully'];
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}
