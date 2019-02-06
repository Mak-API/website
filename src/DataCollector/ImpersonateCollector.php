<?php

namespace App\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonateCollector extends DataCollector
{

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'method' => $request->getMethod(),
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
        ];
    }

    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'app.impersonate_collector';
    }

    public function getMethod()
    {
        return "Coucou";
    }

    public function getAcceptableContentTypes()
    {
        return [
            'test 1',
            'test 2',
            'test 3'
        ];
    }
}