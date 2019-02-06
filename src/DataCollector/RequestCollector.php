<?php

namespace App\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestCollector extends DataCollector
{

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'testssss' => 'testtttt'
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
}