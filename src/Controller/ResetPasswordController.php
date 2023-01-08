<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\User;
use App\Message\PracticeMail;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;
    private EntityManagerInterface $entityManager;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $entityManager)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('/forgot-password', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer): Response
    {
        return $this->processSendingPasswordResetEmail(
            $request->request->get('email') ?? '',
                $mailer
        );
    }
    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @throws Exception
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(string $token = null): Response
    {
        try {
            $this->checkTokenValid($token);
        } catch (Exception $e) {
            return $this->render('reset_password/reset.html.twig', [
                'error' => $e->getMessage(),
            ]);
        }

        return $this->render('reset_password/reset.html.twig');
    }

    #[Route('/update-password', name: 'app_update_password', methods: 'post')]
    public function updatePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $newPassword     = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');
        $token           = $request->query->get('token');

        if($newPassword !== $confirmPassword){
            return $this->render('reset_password/reset.html.twig', [
                'error' => "the new password and confirm password doesn't matched",
            ]);
        }

        try {
            $user = $this->checkTokenValid($token);
        } catch (Exception $e) {
            return $this->render('reset_password/reset.html.twig', [
                'error' => $e->getMessage(),
            ]);
        }

        // Encode(hash) the plain password, and set it.
        $encodedPassword = $userPasswordHasher->hashPassword($user, $newPassword);

        $user->setPassword($encodedPassword);
        $this->entityManager->flush();

        // The session is cleaned up after the password has been changed.
        $this->cleanSessionAfterReset();

        // A password reset token should be used only once, remove it.
        $this->resetPasswordHelper->removeResetRequest($token);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): Response
    {
        $user = $this->entityManager->getRepository(Employee::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->render('reset_password/request.html.twig', [
                'error' => 'User not found',
            ]);
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            return $this->render('reset_password/request.html.twig', [
                'error' => $e->getReason(),
            ]);
        }

        $email = (new TemplatedEmail())
            ->from(new Address('shumonsb@gmail.com', 'CoVi Analytics'))
            ->to($user->getEmail())
            ->subject('Password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken
            ])
        ;
        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->render('reset_password/request.html.twig', [
            'success' => 'Mail sent successfully',
        ]);
    }

    private function checkTokenValid(string $token = null): array|object
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            throw new RuntimeException('There was a problem validating your password reset request: '.$e->getReason(), 401);
        }

        return $user;
    }
}
