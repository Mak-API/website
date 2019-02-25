<?php

namespace App\Service;

use App\Entity\ApiEntityRelation;
use App\Exception\MismatchException;
use App\Repository\ApiEntityRelationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ApiEntityRelationService
{
    /**
     * @var ApiEntityRelationRepository
     */
    private $entityRelationRepository;

    /**
     * @var ApiEntityFieldService
     */
    private $apiEntityFieldService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ApiEntityRelationService constructor.
     * @param ApiEntityRelationRepository $entityRelationRepository
     * @param ApiEntityFieldService $apiEntityFieldService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ApiEntityRelationRepository $entityRelationRepository, ApiEntityFieldService $apiEntityFieldService, EntityManagerInterface $entityManager)
    {
        $this->entityRelationRepository = $entityRelationRepository;
        $this->apiEntityFieldService = $apiEntityFieldService;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a relation.
     *
     * @param int $sourceFieldId
     * @param int $targetFieldId
     * @param string $type
     * @param array $attributes
     * @return ApiEntityRelation
     * @throws MismatchException
     */
    public function createRelation(int $sourceFieldId, int $targetFieldId, string $type, array $attributes): ApiEntityRelation
    {
        $sourceField = $this->apiEntityFieldService->getField($sourceFieldId);
        $targetField = $this->apiEntityFieldService->getField($targetFieldId);

        if ($sourceField && $targetField) {
            throw new \RuntimeException("No field found for id {$sourceFieldId} and/or {$targetFieldId}.");
        }

        if ($sourceField->getType() !== $targetField->getType()) {
            throw new MismatchException($sourceField->getType(), $targetField->getType(), MismatchException::AIM_RELATION);
        }

        $relation = new ApiEntityRelation();
        $relation->setSourceEntityField($sourceField)
            ->setTargetEntityField($targetField)
            ->setType($type)
            ->setAttributes(json_encode($attributes));

        $this->entityManager->persist($relation);
        $this->entityManager->flush();

        return $relation;
    }
}