<?php

namespace App\Repository;

use App\Entity\Apprenant;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Apprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apprenant[]    findAll()
 * @method Apprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprenantRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Apprenant::class);
    }


      
    public function getCollectionCompetenceApprenant($idPromo)
    {
        $idApprenants=$this->em->getRepository(Promotion::class)->findAllIdAppPromo($idPromo);
        return $this->createQueryBuilder('a')
            ->select('a')
            ->andWhere('a.id IN (:idApprenants)')
            ->setParameter('idApprenants', $idApprenants)
            ->getQuery()
            ->getResult()
        ;
    }

       
    public function getCompetencesPromo($idPromo)
    {
        $grp_comp= $this->getCollectionCompetenceApprenant($idPromo);
        $tab=[];
        foreach($grp_comp as $comp)
        {
            foreach($comp->getCompetencesValides() as $cv)
            {
                $tab[]=$cv;
            };
        }

        return $tab;

    }
    // /**
    //  * @return Apprenant[] Returns an array of Apprenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findAllId()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findStatApprenant($id)
    {
        
        return $this->createQueryBuilder('a')
        ->andWhere('a.id = :id')
        ->setParameter('id', $id)
        ->join('a.briefApprenants', 'ba')
        ->select('ba.statut')
        ->getQuery()
        ->getResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?Apprenant
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
