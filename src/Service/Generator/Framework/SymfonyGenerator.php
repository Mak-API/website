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
        $this->bash(sprintf('composer create-project symfony/skeleton %s', $this->api->getName()), $this->projectPath);
        $this->apiPath = sprintf('%s/%s', $this->projectPath, $this->api->getName());

        $this->bash('composer require api', $this->apiPath);

        return $this;
    }

    /**
     * Generates the entities.
     *
     * @return string
     */
    protected function generateEntities(): string
    {
    }

    /**
     * Runs a Symfony command <php bin/console $command>
     *
     * @param string $command
     * @return string
     */
    private function symfonyCommand(string $command): string
    {
        return $this->bash(sprintf('php bin/console %s', $command), $this->apiPath);
    }
}