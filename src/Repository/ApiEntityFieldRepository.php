<?php

namespace App\Repository;

use App\Entity\ApiEntityField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApiEntityField|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiEntityField|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiEntityField[]    findAll()
 * @method ApiEntityField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiEntityFieldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApiEntityField::class);
    }

    // /**
    //  * @return ApiEntityField[] Returns an array of ApiEntityField objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiEntityField
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
