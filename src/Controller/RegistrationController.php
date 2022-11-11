<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function registration(): Response
    {
        return $this->render('registration/registration.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }

    #[Route('/submit-registration', name: 'app_submit_registration')]
    public function submitRegistration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): RedirectResponse
    {
        $request = $request->request;
        $employee = new Employee();
        $password = $request->get('password');
        if ($password) {
            $hashedPassword = $passwordHasher->hashPassword($employee, $password);
            $employee
                ->setEmail($request->get('email'))
                ->setRoles(['ROLE_USER'])
                ->setPassword($hashedPassword);
            $entityManager->persist($employee);
        }
        $entityManager->flush();
        return new RedirectResponse('dashboard');
    }
}
