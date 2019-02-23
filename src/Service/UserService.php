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
use http\Exception\RuntimeException;

class UserService
{
    private $manager;
    private $templating;


    /**
     * UserService constructor.
     * @param ObjectManager $manager
     * @param \Twig_Environment $templating
     */
    public function __construct(ObjectManager $manager, \Twig_Environment $templating)
    {
        $this->manager = $manager;
        $this->templating = $templating;
    }

    /**
     * @param string $token
     * @return array
     * @throws \Exception
     */
    public function verifyToken(string $token) {
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email_token' => $token));

        if ($user && !$user->getVerified()) {
            $user->setVerified(true);
            $this->manager->flush();
            return ["isVerified" => true,
                "login" => $user->getEmail()];
        } else if($user && $user->getVerified()) {
            throw new \Exception('Something went wrong!');
        } else if(!$user) {
            throw new \Exception('Something went wrong!');
        }
        throw new \Exception('Something went wrong!');
    }

    /**
     * @param string $email
     * @return bool|null
     */
    public function isVerified(string $email) {
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        if ($user) {
            return $user->getVerified();
        }
        return false;
    }

    public function isDeleted($email) {
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(array('email' => $email));

        if ($user && $user->getStatus() === 0) {
            return $user;
        }
        return false;
    }

    /**
     * @param bool $verified
     * @param string $user
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function redirectAfterEmailChecking(bool $verified, string $user) {
        return $this->templating->render('authentication/registration.html.twig', [
            'isVerified' => $verified,
            'login' => $user
        ]);
    }

    public function deleteUser($id, User $user)
    {
        try {
            //if ($user && $user->getStatus() > 0) {
            if ($user) {
                $user = $this->manager->getRepository(User::class)
                    ->findOneBy(array('id' => $id));
                $user->setStatus('0');
                $this->manager->flush();
            }
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('Something went wrong' . + $e);
        }
    }

}