<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function filteredSearch(Array $filter)
    {
        $qb = $this->createQueryBuilder('l');

        if (isset($filter['user'])) {
            $qb
                ->andWhere('l.master = :user')
                ->setParameter('user', $filter['user'])
            ;
        }

        if (isset($filter['platform'])) {
            $qb
                ->andWhere('l.platform = :platform')
                ->setParameter('platform', $filter['platform'])
            ;
        }

        if (isset($filter['date'])) {
            $qb
                ->andWhere('l.periodicity = :weekly 
                    OR MOD(WEEK(l.startDate),2) = MOD(:week, 2) AND l.periodicity = :every2week 
                    OR DAY(l.startDate) >= DAY(:startDate) AND DAY(l.startDate) <= DAY(:endDate) AND l.periodicity = :monthly')
                ->setParameter('weekly', Lesson::PERIODICITY_WEEKLY)
                ->setParameter('monthly', Lesson::PERIODICITY_MONTHLY)
                ->setParameter('every2week', Lesson::PERIODICITY_EVERY_2_WEEK)
                ->setParameter('week', $filter['date']['number'])
                ->andWhere('l.endDate >= :endDate')
                ->setParameter('endDate', $filter['date']['endDate'])
                ->setParameter('startDate', $filter['date']['startDate'])
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
