<?php

namespace App\Repository;

use App\Entity\TravelSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TravelSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelSchedule[]    findAll()
 * @method TravelSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelScheduleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TravelSchedule::class);
    }

    // /**
    //  * @return TravelSchedule[] Returns an array of TravelSchedule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TravelSchedule
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
