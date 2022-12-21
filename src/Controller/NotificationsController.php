<?php

namespace App\Controller;

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
}
