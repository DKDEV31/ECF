<?php

namespace App\Repository;

use App\Entity\RequestBenefit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestBenefit|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestBenefit|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestBenefit[]    findAll()
 * @method RequestBenefit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestBenefitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestBenefit::class);
    }

    // /**
    //  * @return RequestBenefit[] Returns an array of RequestBenefit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestBenefit
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
