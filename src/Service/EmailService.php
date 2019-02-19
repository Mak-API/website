<?php
namespace App\Service;

use App\Entity\User;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Doctrine\Common\Persistence\ObjectManager;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\Types\Boolean;

class EmailService {

    /**
     * @var $mailer
     * @var $templating
     * @var $manager
     *
     */
    private $mailer;
    private $templating;
    private $manager;

    /**
     * EmailService constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     * @param ObjectManager $manager
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating,ObjectManager $manager)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->manager = $manager;
    }

    //Email function for sending the confirmation email
    //$email, $login and $token are string value
    /**
     * @param string $login
     * @param string $email
     * @param string $token
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function confirmRegistration(string $login, string $email, string $token) : bool
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

    /**
     * @param string $email
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendNewEmail( string $email) : Boolean
    {

        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        $token = $this->gen_uuid();
        $user->setEmailToken($token);
        $this->manager->flush();

        if($this->confirmRegistration($user->getLogin(), $user->getEmail(), $token)) {
            return true;
        }
        return false;
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
    /**
     * @return string
     */
    function gen_uuid() : string
    {
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

