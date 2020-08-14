<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Profil;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Competence;
use App\Entity\Referentiel;
use App\Entity\ProfilSortie;
use App\Entity\GroupeCompetence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompetenceFixtures extends Fixture
{

    public function __construct()
    {

     }
    


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        $tab_niveau=[];
        for($i=1;$i<=3;$i++)
        {
            $niveau=new Niveau();
            $niveau->setLibelle('libelle '.$i);
            $niveau->setCritereEvaluation('critere_evaluation '.$i);
            $niveau->setGroupeAction('groupe action '.$i);
            
            $tab_niveau[]=$niveau;
            $manager->persist($niveau);
        }
            $manager->flush();

        $tab_competence=[];
        for($i=1; $i<=10;$i++)
        {
            $competence=new Competence();
            $competence->setLibelle('libelle_'.$i);
            $competence->setNiveau($fake->randomElement($tab_niveau));

            $tab_competence[]=$competence;
            $manager->persist($competence);
        }
        $manager->flush();

        $tab_grpCompetence=[];
        for($j=0 ; $j < 3;$j++)
        {
            $grpc=new GroupeCompetence();
            $grpc->setLidelle($fake->realText($maxNBChars = 50, $indexSize = 2 ));
            $grpc->setDescription($fake->text);
            for($i=1;$i<=3;$i++)
            {
                 $grpc->addCompetence($fake->unique()->randomElement($tab_competence));
            }
            $tab_grpCompetence[]=$grpc;
            $manager->persist($grpc);
        }
        $manager->flush();
        
        $tab_referentiel=[];
        for($i=1;$i<=2;$i++)
        {
            $referenciel = new Referentiel();

            $referenciel->setCritereAdmission('critere d\'admission '.$i)
                         ->setCritereEvaluation('critere evaluation '.$i)
                         ->setLibelle('referentiel no'.$i)
                         ->setPresentation($fake->text)
                         ->setProgramme('programme '.$i);
                         for($j=0;$j<2;$j++)
                         {
                            $referenciel->addGrpCompetence($fake->randomElement($tab_grpCompetence));
                         }
             $tab_competence[]=$referenciel;
            $manager->persist($referenciel);
        }

      $manager->flush();
      
      $tab_promo=[];
      for($i=1 ; $i<=2 ; $i++)
      {
          $promo=new Promotion();
          $promo->setDateDebut($fake->date_create())
          ->setDateFinPrvisoire($fake->date_create())
          ->setDescription($fake->text)
          ->setFabrique("fabrique 1")
          ->setLangue('français')
          ->setLieu('lieu1')
          ->setStatus("encours")
          ->setReferentiel($fake->unique()->randomElement($tab_referentiel))
          ->setTitre('promo samba ndiaye');
          $tab_promo[]=$promo;
          $manager->persist($promo);
      }
        //insertion de grpupes!
      for($i=1; $i<=2 ; $i++)
      {
          $group=new Groupes();
          $group->setNom("group principale ".$i);
          $group->setDateCreation($fake->date($format = 'Y-m-d', $max = 'now'));
          $group->setStatus($fake->randomElement(['encours','ferme']));
          $group->setType($fake->randomElement(['binome','filerouge','general']));
          $group->setPromotion($fake->randomElement($tab_promo));
          

      }
      $manager->flush();
        
    }
    
}
