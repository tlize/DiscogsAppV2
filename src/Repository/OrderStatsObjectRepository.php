<?php

namespace App\Repository;

use App\Entity\OrderStatsObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderStatsObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderStatsObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderStatsObject[]    findAll()
 * @method OrderStatsObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderStatsObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatsObject::class);
    }

    // /**
    //  * @return OrderStatsObject[] Returns an array of OrderStatsObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderStatsObject
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBestArtists()
    {
        return $this->createQueryBuilder('oso')
            ->addSelect('oso.artist')
            ->addSelect('COUNT(oso.releaseId) AS nbItems')
            ->addSelect('SUM(oso.price) AS total')
            ->groupBy('oso.artist')
            ->orderBy('total', 'DESC')
            ->addOrderBy('nbItems', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBestLabels()
    {
        return $this->createQueryBuilder('oso')
            ->addSelect('oso.label')
            ->addSelect('COUNT(oso.releaseId) AS nbItems')
            ->addSelect('SUM(oso.price) AS total')
            ->groupBy('oso.label')
            ->orderBy('total', 'DESC')
            ->addOrderBy('nbItems', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
