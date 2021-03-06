<?php

namespace App\Controller\Rest;

use App\Entity\Api;
use App\Service\ApiService;
use App\Service\Generator\Framework\SymfonyGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller\Rest
 *
 * @Route("apis")
 */
class ApiController extends RestController
{
    /**
     * @var ApiService
     */
    private $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function getApis(): Response
    {
        return new Response($this->serialize($this->apiService->getApis()), Response::HTTP_OK);
    }

    /**
     * @param Api $api
     * @return Response
     * @Route("/{id}", methods={"GET"})
     */
    public function getApi(Api $api): Response
    {
        if ($api->isDeleted()) {
            return new JsonResponse(['error' => 'no_api_found'], Response::HTTP_NOT_FOUND);
        }
        return new Response($this->serialize($api), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", methods={"POST"})
     */
    public function createApi(Request $request): Response
    {
        $api = $this->apiService->createApi($request->get('name'), $request->get('description'), $this->getUser());
        return new Response($this->serialize($api), Response::HTTP_CREATED);
    }

    /**
     * @param Api $api
     * @return Response
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteApi(Api $api): Response
    {
        $this->apiService->deleteApi($api);
        return new Response(null, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param Api $api
     * @return Response
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateApi(Request $request, Api $api): Response
    {
        $api = $this->apiService->updateApi($api, $request->get('name'), $request->get('description'), $this->getUser());
        return new Response($this->serialize($api), Response::HTTP_OK);
    }

    /**
     * @param Api $api
     * @param SymfonyGenerator $symfonyGenerator
     * @return Response
     * @Route("/{id}/generate", methods={"POST", "GET"})
     */
    public function generateApi(Api $api, SymfonyGenerator $symfonyGenerator): Response
    {
        $symfonyGenerator->setApi($api);
        $symfonyGenerator->generate();
        $symfonyGenerator->generateZipArchive();
        $symfonyGenerator->uploadArchive();

        return $this->getApi($api);
    }
}