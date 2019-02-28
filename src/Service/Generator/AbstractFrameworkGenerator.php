<?php

namespace App\Service\Generator;


use App\Entity\Api;
use App\Utils\StringTools;
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
        $this->rootPath = realpath(sprintf('%s/../generatedApis', $kernel->getProjectDir()));
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
        $projectPath = sprintf('%s/%s-%s', $this->rootPath, $this->api->getName(), StringTools::generateUUID4());
        $this->projectPath = $projectPath;
        $this->bash(sprintf('mkdir %s', $projectPath), $this->rootPath);
        return $this;
    }

    /**
     * Runs a bash command and returns it.
     *
     * @param string $command
     * @param string|null $directory
     * @return string
     */
    protected function bash(string $command, string $directory): string
    {
        $command = sprintf('cd %s && %s', $directory, $command);
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

    public function getApiPath(): string
    {
        return $this->apiPath;
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