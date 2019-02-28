<?php

namespace App\Service\Generator\Framework;


use App\Service\Generator\AbstractFrameworkGenerator;

class SymfonyGenerator extends AbstractFrameworkGenerator
{
    /**
     * Generates the project.
     *
     * @return AbstractFrameworkGenerator
     */
    protected function generateProject(): AbstractFrameworkGenerator
    {
        $this->bash(sprintf('composer create-project symfony/skeleton %s', $this->api->getName()));
        $this->apiPath = sprintf('%s/%s', $this->projectPath, $this->apiPath);

        $this->bash('touch composer.json');
        $this->bash("echo '{}' > composer.json");
        $this->bash('composer require api | yes');

        return $this;
    }

    /**
     * Generates the entities.
     *
     * @return string
     */
    protected function generateEntities(): string
    {
        return $this->bash('pwd');
    }
}