<?php

namespace App\Controller;

use App\Entity\Assets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    #[Route('/assets', name: 'app_assets')]
    public function index(): Response
    {
        return $this->render('assets/asset-list.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/asset', name: 'add_assets')]
    public function addAsset(): Response
    {
        return $this->render('assets/asset-add.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/assigned', name: 'assigned_assets')]
    public function assignedAsset(): Response
    {
        return $this->render('assets/assigned-asset-list.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/assigning', name: 'assigning_assets')]
    public function assigningAsset(): Response
    {
        return $this->render('assets/assigning-asset.html.twig', [
            'controller_name' => 'AssetsController',
        ]);
    }

    #[Route('/save-assets', name: 'app_save_assets')]
    public function saveAssets(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $request = $request->request;
        $asset = new Assets();
        $asset
            ->setProductCategory($request->get('product-category'))
            ->setProductType($request->get('product-type'))
            ->setProduct($request->get('product'))
            ->setVendor($request->get('vendor'))
            ->setAssetName($request->get('asset-name'))
            ->setSeriulNumber($request->get('serial-number'))
            ->setPrice($request->get('price'))
            ->setDescriptionType($request->get('description-type'))
            ->setLocation($request->get('location'))
            ->setPurchaseDate($request->get('purchase-date'))
            ->setWarrantyExpiryDate($request->get('warranty-expiry-date'))
            ->setPurchaseType($request->get('purchase-type'))
            ->setDescription($request->get('description'))
            ->setUsefulLife($request->get('useful-life'))
            ->setResidualValue($request->get('residual-value'))
            ->setRate($request->get('rate'))
            ->setCreatedBy(1)
            ->setUpdatedBy(null)
            ->setDeletedBy(null)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(null)
            ->setDeletedAt(null)
        ;
        $entityManager->persist($asset);
        $entityManager->flush();

        return new RedirectResponse('assets');
    }
}
