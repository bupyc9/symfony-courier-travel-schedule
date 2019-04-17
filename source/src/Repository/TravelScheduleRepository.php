<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TravelSchedule;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function createQueryBuilderByPeriod(DateTimeInterface $dateDeparture, DateTimeInterface $dateArrival): Query
    {
        return $this->createQueryBuilder('self')
        ->andWhere('self.dateDeparture >= :dateDeparture')
        ->andWhere('self.dateArrival <= :dateArrival')
        ->setParameters(['dateDeparture' => $dateDeparture, 'dateArrival' => $dateArrival])
        ->join('self.courier', 'courier')
        ->join('self.region', 'region')
        ->addSelect(['courier', 'region'])
        ->orderBy('self.dateDeparture', 'ASC')
        ->getQuery();
    }
}
