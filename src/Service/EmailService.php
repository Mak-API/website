<?php
namespace App\Service;

class EmailService {
    public function confirmRegistration($name, $email, $url, \Swift_Mailer $mailer)
    {
            $message = (new \Swift_Message('Confirmez votre adresse email'))
                ->setFrom('send@example.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/registration.html.twig',
                        ['name' => $name,
                            'url' => $url]
                    ),
                    'text/html'
                );



        $mailer->send($message);
    }

    public function resetPassword($email, $url,  \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Mot de passe oublié ?'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/forgotPassword.html.twig',
                    ['url' => $url]
                ),
                'text/html'
            );
        $mailer->send($message);
    }
}

