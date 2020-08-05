<?php

namespace App\Repository;

use App\Entity\Formateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formateurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formateurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formateurs[]    findAll()
 * @method Formateurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormateursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formateurs::class);
    }

    // /**
    //  * @return Formateurs[] Returns an array of Formateurs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formateurs
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
