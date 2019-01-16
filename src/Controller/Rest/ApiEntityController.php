<?php
namespace App\Controller\Rest;

use App\Service\ApiEntityService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class ArticleController
 * @package App\Infrastructure\Http\Rest\Controller
 */
final class ApiEntityController extends FOSRestController
{
    /**
     * @var ApiEntityService
     */
    private $apiEntityService;


    /**
     * ApiEntityController constructor.
     * @param ApiEntityService $apiEntityService
     */
    public function __construct(ApiEntityService $apiEntityService)
    {
        $this->apiEntityService = $apiEntityService;
    }

    /**
     * Creates an ApiEntity resource
     * @Rest\Post("/entities")
     * @param Request $request
     * @return View
     */
    public function postApiEntity(Request $request): View
    {
        $apiEntity = $this->apiEntityService->addApiEntity($request->get('title'), $request->get('apiId'));
        return View::create($apiEntity, Response::HTTP_CREATED);
    }

    /**
     * Retrieves an ApiEntity resource
     * @Rest\Get("/entities/{entityId}")
     * @param int $entityId
     * @return View
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function getApiEntity(int $entityId): View
    {
        $apiEntity = $this->apiEntityService->getApiEntity($entityId);
        return View::create($apiEntity, Response::HTTP_OK);
    }

    /**
     * Retrieves a collection of ApiEntity resource
     * @Rest\Get("/entities")
     * @return View
     */
    public function getApiEntities(): View
    {
        $apiEntities = $this->apiEntityService->getAllApiEntities();
        return View::create($apiEntities, Response::HTTP_OK);
    }

    /**
     * Replaces ApiEntity resource
     * @Rest\Put("/entities/{entityId}")
     * @param int $entityId
     * @param Request $request
     * @return View
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function putApiEntity(int $entityId, Request $request): View
    {
        $apiEntity = $this->apiEntityService->updateApiEntity($entityId, $request->get('title'), $request->get('content'));
        return View::create($apiEntity, Response::HTTP_OK);
    }

    /**
     * Removes the Article resource
     * @Rest\Delete("/entities/{entityId}")
     * @param int $entityId
     * @return View
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function deleteArticle(int $entityId): View
    {
        $this->apiEntityService->deleteApiEntity($entityId);
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}