<?php

namespace App\Controller\Rest;

use App\Entity\ApiEntityField;
use App\Repository\UserRepository;
use App\Service\ApiEntityFieldService;
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

    public function __construct(ApiEntityFieldService $entityFieldService)
    {
        $this->entityFieldService = $entityFieldService;
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
     * @param UserRepository $userRepository
     * @return Response
     *
     * @Route("/", methods={"POST"})
     */
    public function createField(Request $request, UserRepository $userRepository): Response
    {
        return $this->json($this->entityFieldService->createField(
            $request->get('entityId'),
            $request->get('name'),
            $request->get('type'),
            $request->get('nullable'),
            $request->get('attributes'),
            $userRepository->find(1)
        ));
    }
}