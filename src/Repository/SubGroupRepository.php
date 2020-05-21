<?php

namespace App\Repository;

use App\Entity\SubGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubGroup[]    findAll()
 * @method SubGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubGroup::class);
    }

    // /**
    //  * @return SubGroup[] Returns an array of SubGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubGroup
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
