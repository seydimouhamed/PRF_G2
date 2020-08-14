<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Profil;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use App\Entity\Competence;
use App\Entity\Referentiel;
use App\Entity\ProfilSortie;
use App\Entity\GroupeCompetence;
use App\DataFixtures\AppFixtures;
use App\Repository\ApprenantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompetenceFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(ApprenantRepository $apprenantRepo)
    {

     }
    
public function getDependencies()
{
    return array(
        AppFixtures::class,
    );
}

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        $tab_niveau=[];
        $tab_apprenant=$this->getReference(AppFixtures::APPRENANTS);
       $tab_formateur=$this->getReference(AppFixtures::FORMATEURS);
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
            $competence->setLibelle('libelle_'.$i)
            ->setDescriptif($fake->text)
            ->setNiveau($fake->randomElement($tab_niveau));

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
      

        //insertion de grpupes!
        $tab_group=[];
        for($i=1; $i<=2 ; $i++)
        {
            $group=new Groupes();
            $group->setNom("group principale ".$i);
            $group->setStatut($fake->randomElement(['encours','ferme']));
            $group->setType($fake->randomElement(['binome','filerouge','general']));
            // $group->setPromotion($fake->randomElement($tab_promo));
            
            for($j=1;$j<=2;$j++)
            {
                $group->addApprenant($tab_apprenant);
               $group->addFormateur($tab_formateur);
            }

            $tab_group[] = $group;

            $manager->persist($group);
  
        }

      $tab_promo=[];
      for($i=1 ; $i<=2 ; $i++)
      {
          $promo=new Promotion();
          $promo->setDescription($fake->text)
          ->setFabrique("fabrique 1")
          ->setLangue('français')
          ->setLieu('lieu1')
          ->setStatus("encours")
          ->setReferentiel($fake->randomElement($tab_referentiel))
          ->setTitre('promo samba ndiaye'.$i);

          $tab_promo[]=$promo;
          $manager->persist($promo);
      }
//recuperations des apprenants!

      $manager->flush();
        
    }
    
}
