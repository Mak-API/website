<?php

namespace App\Controller\Rest;

use App\Entity\ApiEntity;
use App\Repository\UserRepository;
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
        return new Response($this->serialize($this->apiEntityService->getEntities()), Response::HTTP_OK);
    }

    /**
     * @param ApiEntity $entity
     * @Route("/{id}", methods={"GET"})
     * @return Response
     */
    public function getEntity(ApiEntity $entity): Response
    {
        return new Response($this->serialize($entity), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     *
     * @Route("/", methods={"POST"})
     */
    public function createEntity(Request $request, UserRepository $userRepository): Response
    {
        $api = $this->apiEntityService->createEntity($request->get('apiId'), $request->get('name'), $userRepository->find(1));
        return new Response($this->serialize($api));
    }

    /**
     * @param Request $request
     * @param ApiEntity $entity
     * @return Response
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateEntity(Request $request, ApiEntity $entity)
    {
        $entity = $this->apiEntityService->updateEntity($entity, $request->get('name'));
        return new Response($this->serialize($entity), Response::HTTP_OK);
    }

    /**
     * @param ApiEntity $entity
     * @return Response
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteEntity(ApiEntity $entity)
    {
        $this->apiEntityService->deleteEntity($entity);
        return new Response(null, Response::HTTP_OK);
    }
}