<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
    public function confirmRegistration($token)
    {

        //if token == token envoyé à l'inscription

        return $this->render('authentication/successRegistration.html.twig', [
            'token' => $token
        ]);

        //sinon return error
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
}