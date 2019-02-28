<?php

namespace App\Service\Generator;


use App\Entity\Api;
use App\Service\ApiService;
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
     * @var string
     */
    protected $zipArchiveName;

    /**
     * @var string
     */
    protected $zipStoragePath;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @var ApiService
     */
    protected $apiService;

    public function __construct(KernelInterface $kernel, LoggerInterface $logger, \Twig_Environment $twigEnvironment, ApiService $apiService)
    {
        $this->rootPath = realpath(sprintf('%s/../generatedApis', $kernel->getProjectDir()));
        $this->logger = $logger;
        $this->twigEnvironment = $twigEnvironment;
        $this->apiService = $apiService;
        $this->zipStoragePath = sprintf('%s/public/generated-apis', $kernel->getProjectDir());
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
        $this->apiService->setPath($this->api, sprintf('%s/%s', $this->projectPath, $this->api->getName()));
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
        $this->logger->info("Creating file '$path/$fileName'.");
        $fileHandle = fopen(sprintf('%s/%s/%s', $this->apiPath, $path, $fileName), 'a+');
        return (bool) fwrite($fileHandle, $content);
    }

    /**
     * Generates the ZIP archive and returns its path.
     *
     * @return string
     */
    public function generateZipArchive(): string
    {
        $this->bash('rm -rf vendor', $this->apiPath);
        $filename = sprintf('%s-%s.zip', $this->api->getName(), StringTools::generateUUID4());
        $this->bash(sprintf('zip -r %s %s', $filename, $this->api->getName()), $this->projectPath);
        $this->logger->info("Created Zip archive for api {$this->api->getName()}: $filename.");
        $this->zipArchiveName = $filename;
        return $this->zipArchiveName;
    }

    /**
     * Uploads the ZIP archive.
     *
     * @return bool
     */
    public function uploadArchive(): bool
    {
        $zipArchivePath = sprintf('%s/%s', $this->projectPath, $this->zipArchiveName);
        $this->bash(sprintf("mv %s %s/", $zipArchivePath, $this->zipStoragePath), $this->projectPath);
        $downloadLink = sprintf('%s/generated-apis/%s', getenv('WEBSITE_URL'), $this->zipArchiveName);
        $this->apiService->setDownloadLink($this->api, $downloadLink);
        $this->logger->info($downloadLink);
        return true;
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