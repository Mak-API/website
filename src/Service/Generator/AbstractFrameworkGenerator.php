<?php

namespace App\Service\Generator;


use App\Entity\Api;
use App\Utils\StringTools;
use http\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractFrameworkGenerator
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * The path of the root folder.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * The path of the API folder.
     *
     * @var string
     */
    protected $projectPath;

    /**
     * @var string
     */
    protected $apiPath;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(KernelInterface $kernel, LoggerInterface $logger)
    {
        $this->rootPath = sprintf('%s/storage', $kernel->getProjectDir());
        $this->logger = $logger;
    }

    /**
     * Sets the API.
     *
     * @param Api $api
     * @return AbstractFrameworkGenerator
     */
    public function setApi(Api $api): AbstractFrameworkGenerator
    {
        $this->api = $api;
        return $this;
    }

    /**
     * Creates the API folder.
     *
     * @return AbstractFrameworkGenerator
     */
    public function createFolder(): AbstractFrameworkGenerator
    {
        $apiPath = sprintf('%s/%s-%s', $this->rootPath, $this->api->getName(), StringTools::generateUUID4());
        if (mkdir($apiPath, 0777, true)) {
            $this->projectPath = $apiPath;
            return $this;
        } else {
            throw new RuntimeException("Folder '${apiPath}' could not be created.");
        }
    }

    /**
     * Runs a bash command and returns it.
     *
     * @param string $command
     * @return string
     */
    protected function bash(string $command): string
    {
        $command = sprintf('cd %s && %s', isset($this->apiPath) ? $this->apiPath : $this->projectPath, $command);
        $this->logger->info("Launching command: '$command'.");
        $output = exec($command);
        $this->logger->info("Output: $output.");
        return exec($command);
    }


    public function generate(): bool
    {
        return $this->createFolder()
            ->generateProject()
            ->generateEntities();
    }

    /**
     * Generates the project.
     *
     * @return AbstractFrameworkGenerator
     */
    abstract protected function generateProject(): AbstractFrameworkGenerator;

    /**
     * Generates the entities.
     *
     * @return AbstractFrameworkGenerator
     */
    abstract protected function generateEntities(): string;
}