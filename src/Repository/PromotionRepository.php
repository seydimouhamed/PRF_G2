<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * @return Promotion[] Returns an array of Promotion objects
     */
    
    
    public function findByGroupePrincipal($idPromo)
    {
        return $this->createQueryBuilder('p')
             ->select('p, g, a')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->leftjoin('p.groupes', 'g')
            ->andWhere('g.type = :type')
            ->setParameter('type', 'groupe principale')
            ->leftjoin('g.apprenants','a')
            ->getQuery()
            ->getResult()[0]
            ->getGroupes()[0]
            ->getApprenants()
        ;
    }  
    public function isApprenantInPromo($idPromo,$idApp)
    {
        $result= $this->createQueryBuilder('p')
             ->select('p, g, a')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->leftjoin('p.groupes', 'g')
            ->andWhere('g.type = :type')
            ->setParameter('type', 'groupe principale')
            ->leftjoin('g.apprenants','a')
            ->andWhere('a.id = :idApp')
            ->setParameter('idApp', $idApp)
            ->getQuery()
            ->getResult()
        ;
        
        return !empty($result);
    }    
    public function getIdReferentiel($idPromo)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->leftjoin('p.referentiel', 'r')
            ->select('r.id')
            ->getQuery()
            ->getResult()[0]
        ;
    }
    

        
    public function findBriefByPromoGroupe($idPromo,$idGroupe)
    {
        return $this->createQueryBuilder('p')
             ->select('p,g,e,b')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->leftjoin('p.groupes', 'g')
            ->andWhere('g.id = :idGoupe')
            ->setParameter('idGoupe', $idGroupe)
            ->leftjoin('g.etatBriefGroupes','e')
            ->leftjoin('e.brief', 'b')
            ->getQuery()
           // ->getArrayResult()
            ->getResult()
        ;
    }   
    public function findAllBriefsPromo($idPromo)
    {
        return $this->createQueryBuilder('p')
             ->select('p,bm,b,ebg,grp')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->leftjoin('p.briefMaPromos','bm')
            ->leftjoin('bm.brief', 'b')
            ->join('b.etatBriefGroupes', 'ebg')
            ->andWhere('ebg.statut = :statut')
            ->setParameter('statut', 'rendu')
            ->join('ebg.groupe', 'grp')
            ->getQuery()
           // ->getArrayResult()
            ->getResult()
        ;
    }
    
    public function findAllIdAppPromo($idPromo)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :idPromo')
            ->setParameter('idPromo', $idPromo)
            ->join('p.groupes', 'g')
            ->andWhere('g.type = :type')
            ->setParameter('type', 'groupe principale')
            ->join('g.apprenants','a')
            ->select('a.id')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Promotion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
