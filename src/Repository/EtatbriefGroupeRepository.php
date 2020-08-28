<?php

namespace App\Repository;

use App\Entity\EtatbriefGroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatbriefGroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatbriefGroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatbriefGroupe[]    findAll()
 * @method EtatbriefGroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatbriefGroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatbriefGroupe::class);
    }

    // /**
    //  * @return EtatbriefGroupe[] Returns an array of EtatbriefGroupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtatbriefGroupe
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
