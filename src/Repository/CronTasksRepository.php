<?php

namespace App\Repository;

use App\Entity\CronTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CronTasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronTasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronTasks[]    findAll()
 * @method CronTasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CronTasksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CronTasks::class);
    }

    // /**
    //  * @return CronTasks[] Returns an array of CronTasks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CronTasks
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
