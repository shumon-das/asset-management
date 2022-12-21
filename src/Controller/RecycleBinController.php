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
        $vendors = $this->vendorsRepository->findBy(['isDeleted' => 1]);

        return $this->render('recycle_bin/vendors.html.twig', [
            'vendors' => $vendors,
        ]);
    }

    #[Route('/ams/revert-vendor/{id}', name: 'revert_vendor')]
    public function revertVendor($id, Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $vendor = $this->vendorsRepository->find($id);
        if(false === empty($vendor)) {
            $vendor->setIsDeleted(0)
                ->setDeletedAt(new \DateTimeImmutable())
                ->setDeletedBy($user->getId())
            ;
            $this->entityManager->persist($vendor);
            $this->entityManager->flush();
        }
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/ams/recycle/assets', name: 'app_recycle_assets')]
    public function recycleAssets(): Response
    {
        $assets = $this->assetsRepository->findBy(['isDeleted' => 1]);
        $assignedAssetIds = array_column($this->assigningAssetsRepository->findIds(), 'id');
        $data = [];
        foreach ($assets as $asset) {
            $data[$asset->getId()] = $this->assetsListData($assignedAssetIds, $asset, $this->vendorsRepository);
        }

        return $this->render('recycle_bin/asset-list.html.twig', [
            'assets' => $data,
        ]);
    }

    #[Route('/ams/revert-asset/{id}', name: 'revert_asset')]
    public function revertAsset(int $id, Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $product = $this->assetsRepository->find($id);
        $product->setIsDeleted(0)
                ->setDeletedBy($user->getId())
                ->setDeletedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/ams/recycle/products', name: 'app_recycle_products')]
    public function recycleProducts(): Response
    {
        $products = $this->productsRepository->findBy(['isDeleted' => 1]);
        foreach ($products as $key => $row) {
            $products[$key] = $this->productData($row, $this->employeeRepository);
        }

        return $this->render('recycle_bin/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/ams/revert-product/{id}', name: 'revert_product')]
    public function revertProduct(int $id, Request $request): Response
    {
        /** @var Employee $user */
        $user = $this->security->getUser();
        $product = $this->productsRepository->find($id);
        $product->setIsDeleted(0)
                ->setDeletedBy($user->getId())
                ->setDeletedAt(new DateTimeImmutable())
        ;
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
