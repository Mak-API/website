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
            /*
            * If you also want to include a plaintext version of the message
            ->addPart(
            $this->renderView(
            'emails/registration.txt.twig',
            ['name' => $name]
            ),
            'text/plain'
            )
            */



        $mailer->send($message);
    }

    public function resetPassword($name, $email, $url,  \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Mot de passe oublié ?'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/forgotPassword.html.twig',
                    ['name' => $name,
                        'url' => $url]
                ),
                'text/html'
            );
        $mailer->send($message);
    }
}

