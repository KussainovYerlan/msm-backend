<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function filteredSearch(Array $filter)
    {
        $qb = $this->createQueryBuilder('e');

        if (isset($filter['user'])) {
            $qb
                ->andWhere('e.master = :user')
                ->setParameter('user', $filter['user'])
            ;
        }

        if (isset($filter['platform'])) {
            $qb
                ->andWhere('e.platform = :platform')
                ->setParameter('platform', $filter['platform'])
            ;
        }

        if (isset($filter['date'])) {
            $qb
                ->andWhere('e.date >= :startDate AND e.date <= :endDate')
                ->setParameter('startDate', $filter['date']['startDate'])
                ->setParameter('endDate', $filter['date']['endDate'])
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
