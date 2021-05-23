<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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


    public function findAllItems(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.artist', 'ASC');
    }

    public function findSoldItems(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->orderBy('i.artist', 'ASC');
    }

    public function findItemsForSale(): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC');
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
            ->getResult();
    }


    public function findBestArtists()
    {
        return $this->createQueryBuilder('i')
            ->addSelect('i.artist')
            ->addSelect('COUNT(i.listingId) AS nbItems')
            ->addSelect('SUM(i.price) AS total')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->groupBy('i.artist')
            ->orderBy('total', 'DESC')
            ->addOrderBy('nbItems', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBestLabels()
    {
        return $this->createQueryBuilder('i')
            ->addSelect('i.label')
            ->addSelect('COUNT(i.listingId) AS nbItems')
            ->addSelect('SUM(i.price) AS total')
            ->andWhere('i.status = :sold')
            ->setParameter('sold', 'Sold')
            ->groupBy('i.label')
            ->orderBy('total', 'DESC')
            ->addOrderBy('nbItems', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findArtistDetail($artist)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.artist LIKE :artist')
            ->setParameter('artist', $artist)
            ->orderBy('i.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLabelDetail($label)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.label LIKE :label')
            ->setParameter('label', $label)
            ->orderBy('i.title', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findItemsForNewOrder()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.status = :forsale')
            ->setParameter('forsale', 'For Sale')
            ->orderBy('i.artist', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findOrderItem ($listingId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.listingId = :listingId')
            ->setParameter('listingId', $listingId)
            ->getQuery()
            ->getResult();
    }


}
