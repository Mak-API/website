<?php

namespace App\Controller\Rest;

use App\Entity\ApiEntity;
use App\Service\ApiEntityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiEntityController
 * @package App\Controller\Rest
 *
 * @Route("entities")
 */
class ApiEntityController extends RestController
{
    /**
     * @var ApiEntityService
     */
    private $apiEntityService;

    public function __construct(ApiEntityService $apiEntityService)
    {
        $this->apiEntityService = $apiEntityService;
    }

    /**
 * @Route("/", methods={"GET"})
 */
    public function getEntities(): Response
    {
        return $this->json($this->apiEntityService->getEntities());
    }

    /**
     * @param ApiEntity $entity
     * @Route("/{id}", methods={"GET"})
     * @return Response
     */
    public function getEntity(ApiEntity $entity): Response
    {
        return $this->json($entity);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", methods={"POST"})
     */
    public function createEntity(Request $request): Response
    {
        return $this->json($this->apiEntityService->createEntity($request->get('apiId'), $request->get('name'), $this->getUser()));
    }
}