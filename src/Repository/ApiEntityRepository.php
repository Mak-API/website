<?php

namespace App\Repository;

use App\Entity\ApiEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method ApiEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiEntityRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    /**
     * ApiEntityRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(ApiEntity::class);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->objectRepository->findAll();
    }

    /**
     * @param ApiEntity $apiEntity
     */
    public function save(ApiEntity $apiEntity): void
    {
        $this->entityManager->persist($apiEntity);
        $this->entityManager->flush();
    }

    /**
     * @param ApiEntity $apiEntity
     */
    public function delete(ApiEntity $apiEntity): void
    {
        $this->entityManager->remove($apiEntity);
        $this->entityManager->flush();
    }
}
