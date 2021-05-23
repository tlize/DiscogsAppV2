<?php

namespace App\Repository;

use App\Entity\TestItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestItem[]    findAll()
 * @method TestItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestItem::class);
    }

    // /**
    //  * @return TestItem[] Returns an array of TestItem objects
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
    public function findOneBySomeField($value): ?TestItem
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
