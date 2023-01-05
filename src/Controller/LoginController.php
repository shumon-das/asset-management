<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response|RedirectResponse
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): RedirectResponse
    {
        // controller can be blank: it will never be called!
        return new RedirectResponse('login');
    }

    #[Route('/send-test-email', name: 'send_test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail())
                    ->from('fabien@example.com')
                    ->to(new Address('ryan@example.com'))
                    ->subject('Thanks for signing up!')

                    // path of the Twig template to render
                    ->htmlTemplate('emails/testmail.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                        'expiration_date' => new \DateTime('+7 days'),
                        'username' => 'foo',
                    ])
                ;
        $mailer->send($email);
        return new Response('mail sent');
    }
}
