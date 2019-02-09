<?php

namespace App\DataCollector;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RequestCollector
 * @package App\DataCollector
 *
 * This function is used by Twig : /templates/data_collector/template.html.twig
 * See the documentation here : https://symfony.com/doc/current/profiler/data_collector.html
 *
 * __construct and collect function add data in private $data array.
 *
 * How Twig get data ? `collector` variable calls the getters. So you need called getter and data's key by the same name.
 */
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