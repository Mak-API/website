<?php

namespace App\Repository;

use App\Entity\PaymentPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentPlan[]    findAll()
 * @method PaymentPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentPlanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentPlan::class);
    }

    // /**
    //  * @return PaymentPlan[] Returns an array of PaymentPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentPlan
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
