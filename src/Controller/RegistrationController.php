<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route(name="app_registration_")
 */
class RegistrationController extends AbstractController
{
    private $emailService;
    private $userService;

    public function __construct(EmailService $emailService, UserService $userService)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
    }

    /**
     * @Route("/confirm/{token}", name="confirm_registration", methods={"GET"})
     */
    public function confirmRegistration($token)
    {
        //Check if the the 'isVerified' Bool is ok or not
        $result = $this->userService->verifyToken($token);

        return $this->render('authentication/registration.html.twig', [
            'isVerified' => $result["isVerified"],
            'login' => $result["login"]
        ]);
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password", methods={"GET"})
     */
    public function forgotPassword($token)
    {
        //if token == token envoyé a "mot de passe oublié?"

        return $this->render('authentication/resetPassword.html.twig', [
            'token' => $token
        ]);

        //sinon return error
    }

    /**
     * @Route("/send_confirmation_email/{email}", name="send_confirmation_email", methods={"GET"})
     */
    public function sendConfirmationEmail($email)
    {
        $this->emailService->sendNewEmail($email);

        return $this->render('authentication/registration.html.twig', [
        'isVerified' => false,
        'login' => $email
    ]);

    }
}