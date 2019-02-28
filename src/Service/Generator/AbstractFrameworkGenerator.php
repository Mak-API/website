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

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    public function __construct(KernelInterface $kernel, LoggerInterface $logger, \Twig_Environment $twigEnvironment)
    {
        $this->rootPath = realpath(sprintf('%s/../generatedApis', $kernel->getProjectDir()));
        $this->logger = $logger;
        $this->twigEnvironment = $twigEnvironment;
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

    /**
     * Renders a file from a $template using the given $context.
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    public function renderFile(string $template, array $context): string
    {
        $templatePath = sprintf('services/generator/%s.html.twig', $template);
        try {
            $render = $this->twigEnvironment->render($templatePath, $context);
            $this->logger->info($render);
        } catch (\Twig_Error $e) {
            $this->logger->error("Could not render template, error: '{$e->getMessage()}'.", $context);
            $render = ''; // TODO Throw a custom exception to be caught and handled properly.
        }

        return $render;
    }

    /**
     * Creates a file and writes in it if $content is specified.
     *
     * @param string $path
     * @param string $fileName
     * @param string $content
     * @return bool
     */
    protected function createFile(string $path, string $fileName, string $content = ''): bool
    {
        $fileHandle = fopen(sprintf('%s/%s/%s', $this->getApiPath(), $path, $fileName), 'a+');
        return (bool) fwrite($fileHandle, $content);
    }

    /**
     * Generates the API.
     *
     * @return bool
     */
    public function generate(): bool
    {
        $this->createFolder()
            ->generateProject()
            ->generateEntities();

        return true;
    }

    /**
     * @return string
     */
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
    abstract protected function generateEntities(): AbstractFrameworkGenerator;
}