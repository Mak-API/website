<?php
namespace App\Service;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Swift_Mailer;

class EmailService {

    /**
     * @var \Swift_Mailer
     *
     */
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    //Email function for sending the confirmation email
    public function confirmRegistration($username, $email, $token)
    {
        $message = (new \Swift_Message('Confirmez votre adresse email'))
            ->setFrom(getenv('MAILER_FROM'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig',
                    ['name' => $username,
                        'token' => $token]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    //[NOTE] Keep for later
    //public function resetPassword($email, $url,  \Swift_Mailer $mailer)
    //{
    //    $message = (new \Swift_Message('Mot de passe oublié ?'))
    //        ->setFrom(getenv('MAILER_FROM'))
    //        ->setTo($email)
    //        ->setBody(
    //            $this->templating->render(
    //                'emails/forgotPassword.html.twig',
    //                ['url' => $url]
    //            ),
    //            'text/html'
    //        );
    //    $this->mailer->send($message);
    //}

    //Generate a token for the email_token (use in UserController
    function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}

