<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    public function findSoldItems()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findItemsForSale()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findExpensiveSoldItems()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :status')
            ->setParameter('status', 'Sold')
            ->andWhere('i.price > 50')
            ->orderBy('i.price', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOutOfShop()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status != :sold')
            ->setParameter('sold', 'Sold')
            ->andWhere('i.status != :forSale')
            ->setParameter('forSale', 'for Sale')
           ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
