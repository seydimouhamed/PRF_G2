<?php

namespace App\Repository;

use App\Entity\GroupeCompetence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupeCompetence|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeCompetence|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeCompetence[]    findAll()
 * @method GroupeCompetence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeCompetenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeCompetence::class);
    }

    /**
     * @return GroupeCompetence[] Returns an array of GroupeCompetence objects
     */
    
    public function findByArchivage($value,$limit,$offset)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.archivage = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }
    
    

    /*
    public function findOneBySomeField($value): ?GroupeCompetence
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
