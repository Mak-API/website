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
    public function __construct(UserRepository $userRepository)
    {
        $this->data = [
            'users' => $userRepository->findAll(),
            'labelToolbarUsers' => 'Switch User',
            'labelSelectUsers' => 'User list',
        ];
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {

    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'app.request_collector';
    }

    public function getUsers()
    {
        return $this->data['users'];
    }

    public function getLabelToolbarUsers()
    {
        return $this->data['labelToolbarUsers'];
    }

    public function getLabelSelectUsers()
    {
        return $this->data['labelSelectUsers'];
    }
}