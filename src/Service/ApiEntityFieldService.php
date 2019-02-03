<?php

namespace App\Service;


use App\Entity\ApiEntityField;
use App\Repository\ApiEntityFieldRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiEntityFieldService
{
    /**
     * @var ApiEntityFieldRepository
     */
    private $apiEntityFieldRepository;

    /**
     * @var ApiEntityService
     */
    private $apiEntityService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiEntityFieldService constructor.
     * @param ApiEntityFieldRepository $apiEntityFieldRepository
     * @param ApiEntityService $apiEntityService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ApiEntityFieldRepository $apiEntityFieldRepository, ApiEntityService $apiEntityService, EntityManagerInterface $entityManager)
    {
        $this->apiEntityFieldRepository = $apiEntityFieldRepository;
        $this->apiEntityService = $apiEntityService;
        $this->entityManager = $entityManager;
    }

    /**
     * Gets all fields.
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->apiEntityFieldRepository->findAll();
    }

    /**
     * Gets a field.
     *
     * @param int $fieldId
     * @return ApiEntityField|null
     */
    public function getField(int $fieldId): ApiEntityField
    {
        return $this->apiEntityFieldRepository->find($fieldId);
    }

    /**
     * Creates a field.
     *
     * @param int $apiEntityId
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param string $attributes
     * @param UserInterface $creator
     * @return ApiEntityField
     */
    public function createField(int $apiEntityId, string $name, string $type, bool $nullable, string $attributes, UserInterface $creator): ApiEntityField
    {
        $field = new ApiEntityField();
        $field->setEntity($this->apiEntityService->getEntity($apiEntityId))
            ->setName($name)
            ->setType($type)
            ->setNullable($nullable)
            ->setAttributes($attributes)
            ->setCreatedBy($creator);

        $this->entityManager->persist($field);
        $this->entityManager->flush();

        return $field;
    }
}