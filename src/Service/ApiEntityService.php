<?php

namespace App\Service;

use App\Entity\ApiEntity;
use App\Repository\ApiEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiEntityService
{
    /**
     * @var ApiEntityRepository
     */
    private $apiEntityRepository;

    /**
     * @var ApiService
     */
    private $apiService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiEntityService constructor.
     * @param ApiEntityRepository $apiEntityRepository
     * @param ApiService $apiService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ApiEntityRepository $apiEntityRepository, ApiService $apiService, EntityManagerInterface $entityManager)
    {
        $this->apiEntityRepository = $apiEntityRepository;
        $this->apiService = $apiService;
        $this->entityManager = $entityManager;
    }

    /**
     * Gets all the entities.
     *
     * @return array
     */
    public function getEntities(): array
    {
        return $this->apiEntityRepository->findAll();
    }

    /**
     * Gets an entity.
     *
     * @param int $id
     * @return ApiEntity
     */
    public function getEntity(int $id): ApiEntity
    {
        return $this->apiEntityRepository->find($id);
    }

    /**
     * Creates an entity.
     *
     * @param int $apiId
     * @param string $name
     * @param UserInterface $creator
     * @return ApiEntity
     */
    public function createEntity(int $apiId, string $name, UserInterface $creator)
    {
        $apiEntity = new ApiEntity();
        $apiEntity->setApi($this->apiService->getApi($apiId))
            ->setName($name)
            ->setCreatedBy($creator);

        $this->entityManager->persist($apiEntity);
        $this->entityManager->flush();

        return $apiEntity;
    }
}