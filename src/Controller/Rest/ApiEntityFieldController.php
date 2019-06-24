<?php

namespace App\Controller\Rest;

use App\Entity\ApiEntityField;
use App\Repository\UserRepository;
use App\Service\ApiEntityFieldService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiEntityFieldController
 * @package App\Controller\Rest
 *
 * @Route("fields")
 */
class ApiEntityFieldController extends RestController
{
    /**
     * @var ApiEntityFieldService
     */
    private $entityFieldService;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(ApiEntityFieldService $entityFieldService, UserService $userService)
    {
        $this->entityFieldService = $entityFieldService;
        $this->userService = $userService;
    }

    /**
     * @return Response
     *
     * @Route("/", methods={"GET"})
     */
    public function getFields(): Response
    {
        return $this->json($this->entityFieldService->getFields());
    }

    /**
     * @param ApiEntityField $field
     * @return Response
     *
     * @Route("/{id}", methods={"GET"})
     */
    public function getField(ApiEntityField $field): Response
    {
        return $this->json($field);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", methods={"POST"})
     */
    public function createField(Request $request): Response
    {
        $field = $this->entityFieldService->createField(
            $request->get('entityId'),
            $request->get('name'),
            $request->get('type'),
            $request->get('nullable'),
            '',
            $this->getUserFromRequest($request)
        );
        return new Response($this->serialize($field), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param ApiEntityField $field
     * @return Response
     *
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateField(Request $request, ApiEntityField $field): Response
    {
        $field = $this->entityFieldService->updateField(
            $field,
            $request->get('name'),
            $request->get('type'),
            $request->get('nullable'),
            $request->get('attributes'),
            $this->getUser()
        );

        return new Response($this->serialize($field), Response::HTTP_OK);
    }

    /**
     * @param ApiEntityField $field
     * @return Response
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteField(ApiEntityField $field): Response
    {
        $this->entityFieldService->deleteField($field);

        return new Response(null, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @return \App\Entity\User|mixed|null
     */
    private function getUserFromRequest(Request $request)
    {
        return $this->userService->getUser($request->get('userId'));
    }
}