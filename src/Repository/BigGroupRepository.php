<?php

namespace App\Repository;

use App\Entity\BigGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BigGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method BigGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method BigGroup[]    findAll()
 * @method BigGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BigGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BigGroup::class);
    }

    // /**
    //  * @return BigGroup[] Returns an array of BigGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BigGroup
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
