<?php
namespace App\Service;

use App\Entity\ApiEntity;
use App\Repository\ApiEntityRepository;
use App\Repository\ApiRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class ArticleService
 * @package App\Application\Service
 */
final class ApiEntityService
{
    /**
     * @var ApiEntityRepository
     */
    private $apiEntityRepository;

    /**
     * @var ApiRepository
     */
    private $apiRepository;

    /**
     * ApiEntityService constructor.
     * @param ApiEntityRepository $apiEntityRepository
     * @param ApiRepository $apiRepository
     */
    public function __construct(ApiEntityRepository $apiEntityRepository, ApiRepository $apiRepository) {
        $this->apiEntityRepository = $apiEntityRepository;
        $this->apiRepository = $apiRepository;
    }

    /**
     * @param string $title
     * @param int $apiId
     * @return ApiEntity
     */
    public function addApiEntity(string $title, int $apiId): ApiEntity
    {
        $apiEntity = new ApiEntity();
        $apiEntity->setName($title);
        $apiEntity->setApi($this->apiRepository->find($apiId));
        $this->apiEntityRepository->save($apiEntity);
        return $apiEntity;
    }

    /**
     * @param int $apiEntityId
     * @return ApiEntity
     * @throws EntityNotFoundException
     */
    public function getApiEntity(int $apiEntityId): ApiEntity
    {
        $apiEntity = $this->apiEntityRepository->find($apiEntityId);
        if (! $apiEntity) {
            throw new EntityNotFoundException('Api Entity with id '.$apiEntityId.' does not exist!');
        }

        return $apiEntity;
    }

    /**
     * @param int $apiEntityId
     * @param string $name
     * @param int $apiId
     * @return ApiEntity
     * @throws EntityNotFoundException
     */
    public function updateApiEntity(int $apiEntityId, string $name, int $apiId): ApiEntity
    {
        $apiEntity = $this->apiEntityRepository->find($apiEntityId);
        if (! $apiEntity) {
            throw new EntityNotFoundException('Api Entity with id '.$apiEntityId.' does not exist!');
        }
        $apiEntity->setName($name);
        $apiEntity->setApi($this->apiRepository->find($apiId));
        $this->apiEntityRepository->save($apiEntity);
        return $apiEntity;
    }

    /**
     * @return array|null
     */
    public function getAllApiEntities(): ?array
    {
        return $this->apiEntityRepository->findAll();
    }

    /**
     * @param int $apiEntityId
     * @throws EntityNotFoundException
     */
    public function deleteApiEntity(int $apiEntityId): void
    {
        $apiEntity = $this->apiEntityRepository->find($apiEntityId);
        if (! $apiEntity) {
            throw new EntityNotFoundException('Api Entity with id '.$apiEntityId.' does not exist!');
        }
        $this->apiEntityRepository->delete($apiEntity);
    }
}