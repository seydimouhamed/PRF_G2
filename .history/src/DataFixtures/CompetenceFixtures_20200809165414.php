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

    protected $apprenantRepo;

    public function __construct(ApprenantRepository $apprenantRepo)
    {
            $this->apprenantRepo= $apprenantRepo;
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

        $tab_competence=[];
        for($i=1; $i<=10;$i++)
        {
            $competence=new Competence();
            $competence->setLibelle('libelle_'.$i)
            ->setDescriptif($fake->text);
          //  ->setNiveau($fake->randomElement($tab_niveau));
            $manager->persist($competence);
            //ajout des niveaux de compétences
            for($j=1;$j<=3;$j++)
            {
                $niveau=new Niveau();
                $niveau->setLibelle('niveau '.$j);
                $niveau->setCritereEvaluation('competentence '.$i.'critere_evaluation '.$j);
                $niveau->setGroupeAction('competentence '.$i.'groupe action '.$j);
                $niveau->setCompetence($competence);
                $manager->persist($niveau);
            }

            $tab_competence[]=$competence;
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
             $tab_referentiel[]=$referenciel;
            $manager->persist($referenciel);
        }

      $manager->flush();
      //-----------------------------------//
      //récupération de tout les apprrenant //
      //-----------------------------------//
    $apprenants=$this->apprenantRepo->findAll(); 
    $tab_apprenant=[];
    foreach($apprenants as $app)
    {
        $tab_apprenant[]=$app;
        
        //-----------------------------------//
        //récupération de tout les formateurs //
        //-----------------------------------//
      $formateurs=$this->formateurRepo->findAll(); 
      $tab_formateurs=[];
      foreach($formateurs as $for)
      {
          $tab_formateur[]=$for;
    }

        //insertion de grpupes!
        $tab_group=[];
        for($i=1; $i<=5 ; $i++)
        {
            $group=new Groupes();
            $group->setNom("group ".$i);
            $group->setStatut($fake->randomElement(['encours','ferme']));
            $group->setType($fake->randomElement(['binome','filerouge']));
            // $group->setPromotion($fake->randomElement($tab_promo));
            
            for($j=1;$j<=10;$j++)
            {
                $group->addApprenant($tab_apprenant);
            }

            for($j=1;$j<=2;$j++)
            {
               $group->addFormateur($tab_formateur);
            }

            $tab_group[] = $group;

            $manager->persist($group);
  
        }

      $tab_promo=[];
      for($i=1 ; $i<=3 ; $i++)
      {
          $promo=new Promotion();
          $promo->setDescription($fake->text)
          ->setFabrique($fake->randomElement(['Sonatel Académie','Simplon']))
          ->setLangue($fake->randomElement(['anglais','france']))
          ->setLieu('lieu1')
          ->setStatus($fake->randomElement(['encours','ferme','attente']))
          ->setReferentiel($fake->randomElement($tab_referentiel))
          ->setTitre('promo '.$i);
          
          //ajouter un groupe principal au promo!
                $group_princ=new Groupes();
                $group_princ->setNom("groupe principale promo ".$i)
                            ->setStatut($fake->randomElement(['encours','ferme','attente']))
                            ->setType('groupe principale');
                            $manager->persist($group_princ);
            $promo->addGroupe($group_princ);

          //$tab_promo[]=$promo;
          $manager->persist($promo);
      }
//recuperations des apprenants!

      $manager->flush();
        
    }
    
}
