<?php

namespace App\Repository;

use App\Entity\CarriersInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarriersInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarriersInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarriersInformation[]    findAll()
 * @method CarriersInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarriersInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarriersInformation::class);
    }

    // /**
    //  * @return CarriersInformation[] Returns an array of CarriersInformation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarriersInformation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
