<?php

namespace App\Controller;

use App\Service\EmailService;
use App\Service\UserService;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @param string $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/confirm/{token}", name="confirm_registration", methods={"GET"})
     */
    public function confirmRegistration(string $token) : Response
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
    public function forgotPassword(string $token) : Response
    {

        return $this->render('authentication/resetPassword.html.twig', [
            'token' => $token
        ]);
    }

    /**
     * @param string $email
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @Route("/send_confirmation_email/{email}", name="send_confirmation_email", methods={"GET"})
     * @throws \Twig_Error_Syntax
     */
    public function sendConfirmationEmail(string $email) : Response
    {
        $this->emailService->sendNewEmail($email);

        return $this->render('authentication/registration.html.twig', [
        'isVerified' => false,
        'login' => $email
    ]);

    }
}