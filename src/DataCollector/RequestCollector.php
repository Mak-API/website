<?php

namespace App\DataCollector;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestCollector extends DataCollector
{
    /**
     * @var User
     */
    private $arrayUsers;

    public function __construct(UserRepository $userRepository)
    {
        $this->arrayUsers = $userRepository->findAll();
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'impersonateArrayUser' => $this->getImpersonateArrayUser(),
            'impersonateUser' => $this->getImpersonateUser(),
        ];
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'app.request_collector';
    }

    public function getImpersonateArrayUser()
    {
        return $this->arrayUsers;
    }

    public function getImpersonateUser()
    {
        return 'Switch User';
    }
}