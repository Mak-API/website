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
    private $entityFieldRepository;

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
     * @param ApiEntityFieldRepository $entityFieldRepository
     * @param ApiEntityService $apiEntityService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ApiEntityFieldRepository $entityFieldRepository, ApiEntityService $apiEntityService, EntityManagerInterface $entityManager)
    {
        $this->entityFieldRepository = $entityFieldRepository;
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
        return $this->entityFieldRepository->findAll();
    }

    /**
     * Gets a field.
     *
     * @param int $fieldId
     * @return ApiEntityField|null
     */
    public function getField(int $fieldId): ApiEntityField
    {
        return $this->entityFieldRepository->find($fieldId);
    }

    /**
     * Creates a field.
     *
     * @param int $apiEntityId
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param string $attributes
     * @param UserInterface $createdBy
     * @return ApiEntityField
     */
    public function createField(int $apiEntityId, string $name, string $type, bool $nullable, string $attributes, UserInterface $createdBy): ApiEntityField
    {
        $field = new ApiEntityField();
        $field->setEntity($this->apiEntityService->getEntity($apiEntityId))
            ->setName($name)
            ->setType($type)
            ->setNullable($nullable)
            ->setAttributes($attributes)
            ->setCreatedBy($createdBy)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \Datetime());

        $this->entityManager->persist($field);
        $this->entityManager->flush();

        return $field;
    }

    /**
     * @param ApiEntityField $field
     * @param string $name
     * @param string $type
     * @param bool $nullable
     * @param string $attributes
     * @param UserInterface $updatedBy
     * @return ApiEntityField
     */
    public function updateField(ApiEntityField $field, string $name, string $type, bool $nullable, string $attributes, UserInterface $updatedBy): ApiEntityField
    {
        $field->setName($name)
            ->setType($type)
            ->setNullable($nullable)
            ->setAttributes($attributes)
            ->setUpdatedBy($updatedBy)
            ->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return $field;
    }
}