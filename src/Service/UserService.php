<?php
/**
 * Created by PhpStorm.
 * User: backin
 * Date: 04/02/2019
 * Time: 18:21
 */

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use App\Entity\User;

class UserService
{
    private $manager;
    private $templating;

    public function __construct(ObjectManager $manager, \Twig_Environment $templating)
    {
        $this->manager = $manager;
        $this->templating = $templating;
    }

    public function verifyToken($token) {
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email_token' => $token));
        if ($user && !$user->getVerified()) {
            $user->setVerified(true);
            $this->manager->flush();
            return ["isVerified" => true,
                "user" => $user->getLogin()];
        } else if($user && $user->getVerified()) {
            return ["isVerified" => true,
                "user" => $user->getLogin()];
        } else if(!$user) {
            throw new \Exception('Something went wrong!');
        } else {
            throw new \Exception('Something went wrong!');
        }
    }

    public function isVerified($email) {
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        if ($user) {
            return $user->getVerified();
        } else {
            return false;
        }
    }

    public function redirectVerified($verified, $user) {
        return $this->templating->render('authentication/registration.html.twig', [
            'isVerified' => $verified,
            'username' => $user
        ]);
    }

}