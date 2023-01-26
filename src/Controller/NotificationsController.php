<?php

namespace App\Controller;

use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractApiController
{
    #[Route('/ams/notifications', name: 'app_notifications')]
    public function index(): Response
    {
        return $this->render('notifications/notifications.html.twig', [
            'controller_name' => 'NotificationsController',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/ams/assets-expire-info', name: 'assets_expire_info', methods: ['POST'])]
    public function getAssetsExpiredInfo(Request $request): JsonResponse
    {
        $date = $request->request->get('date');
//        return $this->json(['data' => $date]);
        $assets = $this->assetsRepository->getAssetsBetweenDate(new DateTimeImmutable($date), new DateTimeImmutable());
        $date1 = new DateTimeImmutable($date);
        return $this->json(['data' => $assets]);
    }
}
