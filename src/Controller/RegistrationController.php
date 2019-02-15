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


    /**
     * RegistrationController constructor.
     * @param EmailService $emailService
     * @param UserService $userService
     */
    public function __construct(EmailService $emailService, UserService $userService)
    {
        $this->emailService = $emailService;
        $this->userService = $userService;
    }

    /**
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
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
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reset_password/{token}", name="reset_password", methods={"GET"})
     */
    public function forgotPassword(string $token)
    {

        return $this->render('authentication/resetPassword.html.twig', [
            'token' => $token
        ]);
    }

    /**
     * @param string $email
     * @Route("/send_confirmation_email/{email}", name="send_confirmation_email", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendConfirmationEmail(string $email)
    {
        $this->emailService->sendNewEmail($email);

        return $this->render('authentication/registration.html.twig', [
        'isVerified' => false,
        'login' => $email
    ]);

    }
}