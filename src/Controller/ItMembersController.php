<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItMembersController extends AbstractController
{
    #[Route('/members', name: 'app_it_members')]
    public function members(): Response
    {
        return $this->render('it_members/members.html.twig', [
            'controller_name' => 'ItMembersController',
        ]);
    }

    #[Route('/add-members', name: 'app_add_it_member')]
    public function addMembers(): Response
    {
        return $this->render('it_members/add-member.html.twig', [
            'controller_name' => 'ItMembersController',
        ]);
    }
}
