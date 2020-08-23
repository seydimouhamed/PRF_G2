<?php

namespace App\Repository;

use App\Entity\Brief;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brief|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brief|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brief[]    findAll()
 * @method Brief[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BriefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brief::class);
    }
    public function myFindAll($limit,$offset)

    {

        return $this

            ->createQueryBuilder('a')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()


            ;

    }
    public function findBriefAttributs(){
        return $this
            ->createQueryBuilder('b')
            ->select('b.id,b.Titre , b.contexte, b.DatePoste, b.DateLimite, 
            b.ListeLivrable, b.DescriptionRapide, b.ModalitePedagogique, b.CricterePerformance,
             b.ModaliteDevaluation, b.langue')
            ->getQuery()
            ->getResult();

    }
    // /**
    //  * @return Brief[] Returns an array of Brief objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Brief
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
