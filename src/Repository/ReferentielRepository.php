<?php

namespace App\Repository;

use App\Entity\Promotion;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Referentiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentiel[]    findAll()
 * @method Referentiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentielRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Referentiel::class);
    }

        
    public function getCompentencePromo($idPromo)
    {
        $idRef=$this->em->getRepository(Promotion::class)->getIdReferentiel($idPromo);
        
        $result= $this->createQueryBuilder('r')
             ->select('r, gc,c')
            ->andWhere('r.id = :idRef')
            ->setParameter('idRef', $idRef['id'])
            ->leftjoin('r.grpCompetences','gc')
            ->leftjoin('gc.competences','c')
            ->getQuery()
            ->getResult()[0]
            ->getGrpCompetences()
        ;
        $tabCompe=[];
        foreach($result as $gc)
        {
            foreach($gc->getCompetences() as $comp)
            {
                $tabCompe[]=$comp;
            }
        }
        return $tabCompe;
    }
    // /**
    //  * @return Referentiel[] Returns an array of Referentiel objects
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
    public function findOneBySomeField($value): ?Referentiel
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
