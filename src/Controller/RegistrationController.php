<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route(name="app_default_")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/confirm/{token}", name="ConfirmRegistration", methods={"GET"})
     */
    public function confirmRegistration($token, UserService $userService)
    {

        $result = $userService->verifyToken($token);

        return $this->render('authentication/registration.html.twig', [
            'isVerified' => $result["isVerified"],
            'username' => $result["user"]
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
     * @Route("/send_confirmation_email", name="send_confirmation_email", methods={"GET"})
     */
    public function sendConfirmationEmail($token)
    {
        return $this->render('authentication/send_confirmation_email.html.twig', []);

    }
}