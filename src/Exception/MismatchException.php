<?php

namespace App\Exception;

class MismatchException extends \Exception
{
    const AIM_RELATION = 'relation';

    public function __construct(string $typeOne, string $typeTwo, string $aim)
    {
        parent::__construct("Types '{$typeOne}' and '{$typeTwo}' mismatch, couldn't create {$aim}.");
    }
}