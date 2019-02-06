<?php

namespace App\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;

class RequestCollector extends DataCollector
{
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'users' => $this->getUsers()
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

    public function getUsers()
    {
        return $this->user->findAll();
    }
}