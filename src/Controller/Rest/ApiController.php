<?php

namespace App\Controller\Rest;

use App\Entity\Api;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller\Rest
 *
 * @Route("apis")
 */
class ApiController extends AbstractController
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
    public function getEntities(): Response
    {
        return $this->json($this->apiService->getApis());
    }

    /**
     * @param Api $api
     * @Route("/{id}", methods={"GET"})
     * @return Response
     */
    public function getEntity(Api $api): Response
    {
        return $this->json($api);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/", methods={"POST"})
     */
    public function createApi(Request $request): Response
    {
        return $this->json($this->apiService->createApi($request->get('name'), $request->get('description'), $this->getUser()));
    }

    /**
     * @param Api $api
     * @return Response
     */
    public function deleteApi(Api $api): Response
    {
        return $this->json($this->apiService->deleteApi($api));
    }

    /**
     * @param Request $request
     *
     * @Route("/{id}", methods={"PUT"}
     */
    public function updateEntity(Request $request)
    {
        //
    }
}