<?php
namespace App\Service;

use App\Entity\User;
use App\Utils\StringTools;
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
    public function sendNewEmail( string $email) : bool
    {

        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        $token = StringTools::generateUUID4();
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
}

