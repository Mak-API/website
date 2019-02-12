<?php
namespace App\Service;

use App\Entity\User;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Doctrine\Common\Persistence\ObjectManager;
use mysql_xdevapi\Exception;

class EmailService {

    /**
     * @var \Swift_Mailer
     *
     */
    private $mailer;
    private $templating;
    private $manager;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating,ObjectManager $manager)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->manager = $manager;
    }

    //Email function for sending the confirmation email
    //$email, $login and $token are string value
    public function confirmRegistration($login, $email, $token)
    {
        $message = (new \Swift_Message('Confirmez votre adresse email'))
            ->setFrom(getenv('MAILER_FROM'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig',
                    ['login' => $login,
                        'token' => $token]
                ),
                'text/html'
            );

        try{
            $send = $this->mailer->send($message);
            if($send) {
                return true;
            }
        }catch(\Swift_TransportException $e){
            return false;
        }

    }

    public function sendNewEmail($email) {

        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        $token = $this->gen_uuid();
        $user->setEmailToken($token);
        $this->manager->flush();

        if($this->confirmRegistration($user->getLogin(), $user->getEmail(), $token)) {
            return true;
        } else {
            return false;
        }
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

    //Code found in https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid/2040279#2040279
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

