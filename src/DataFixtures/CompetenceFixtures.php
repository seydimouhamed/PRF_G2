<?php

namespace App\DataFixtures;

use App\Entity\Brief;
use App\Entity\Commentaires;
use App\Entity\FilDiscussion;
use App\Entity\Livrable;
use App\Entity\LivrableAttenduApprenant;
use App\Entity\LivrableAttendus;
use App\Entity\LivrablePartielApprenant;
use App\Entity\LivrablePartiels;
use App\Entity\Ressource;
use App\Repository\TagRepository;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Profil;
use App\Entity\Groupes;
use App\Entity\Tag;
use App\Entity\GroupeTag;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use App\Entity\Competence;
use App\Entity\Referentiel;
use App\Entity\ProfilSortie;
use App\Entity\GroupeCompetence;
use App\DataFixtures\AppFixtures;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Provider\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function App\Entity\setLibelle;

class CompetenceFixtures extends Fixture implements DependentFixtureInterface
{

    protected $apprenantRepo;
    protected $formateurRepo;


    public function __construct(ApprenantRepository $apprenantRepo,FormateurRepository $formateurRepo)
    {
            $this->apprenantRepo= $apprenantRepo;
            $this->formateurRepo=$formateurRepo;

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
    //     $tab_apprenant=$this->getReference(AppFixtures::APPRENANTS);
    //    $tab_formateur=$this->getReference(AppFixtures::FORMATEURS);

        $tab_competence=[];
        for($i=1; $i<=10;$i++)
        {
            $competence=new Competence();
            $competence->setLibelle('libelle_'.$i)
            ->setDescriptif($fake->text);
          //  ->setNiveau($fake->randomElement($tab_niveau));
            $manager->persist($competence);
            //ajout des niveaux de compétences


            $tab_competence[]=$competence;
        }
        $manager->flush();

        $tab_grpCompetence=[];
        for($j=0 ; $j < 3;$j++)
        {
            $grpc=new GroupeCompetence();
            $grpc->setLibelle($fake->realText($maxNBChars = 50, $indexSize = 2 ));
            $grpc->setDescription($fake->text);
            $grpc->setArchivage(false);
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
    }
        //-----------------------------------//
        //récupération de tout les formateurs //
        //-----------------------------------//
      $formateurs=$this->formateurRepo->findAll(); 
      $tab_formateurs=[];
      foreach($formateurs as $for)
      {
          $tab_formateurs[]=$for;
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
                $group->addApprenant($fake->unique()->randomElement($tab_apprenant));
            }

            for($j=1;$j<=2;$j++)
            {
               $group->addFormateur($fake->unique()->randomElement($tab_formateurs));
            }

            $tab_group[] = $group;

            $manager->persist($group);
  
        }

      $tab_promo=[];
      for($i=0 ; $i<=3 ; $i++)
      {
          for($oo=0;$oo<5;$oo++){

              $promo=new Promotion();
              $promo->setDescription($fake->text)
                  ->setFabrique($fake->randomElement(['Sonatel Académie','Simplon']))
                  ->setLangue($fake->randomElement(['anglais','français']))
                  ->setLieu('lieu'.$oo)
                  ->setStatus($fake->randomElement(['encours','ferme','attente']))
                  ->setReferentiel($fake->randomElement($tab_referentiel))
                  ->setTitre('promo '.$oo);
              $manager->persist($promo);
          }

          
          //ajouter un groupe principal au promo!
                $group_princ=new Groupes();
                $group_princ->setNom("groupe principale promo ".$i)
                            ->setStatut($fake->randomElement(['encours','ferme','attente']))
                            ->setType('groupe principale');
                            $manager->persist($group_princ);
            
                for($j=1;$j<=10;$j++)
                {
                    $group_princ->addApprenant($fake->unique()->randomElement($tab_apprenant));
                }
    
                for($j=1;$j<=2;$j++)
                {
                    $group_princ->addFormateur($fake->unique()->randomElement($tab_formateurs));
                }
            $promo->addGroupe($group_princ);
            for($k=1;$k<=2;$k++)
            {
                $promo->addGroupe($fake->randomElement($tab_group));
                $promo->addFormateur($fake->unique()->randomElement($tab_formateurs));
            }

          //$tab_promo[]=$promo;


            //////////////////////////

          $tab=["HTML5", "php", "javascript", "angular", "wordpress", "bootstrap","json","python","java","joomla","c++","fortran","algo"];


          $tab_objt_tag=[];
          for($i=0;$i<count($tab);$i++)
          {
              $tag=new Tag();
              $tag->setLibelle($tab[$i]);
              $tag->setDescription("description ".$i);



              $tab_objt_tag[]=$tag;
              $manager->persist($tag);
          }

          $manager->flush();

          for($i=1;$i<=3;$i++)
          {
              $grpTag=new GroupeTag();
              $grpTag->setLibelle('libelle '.$i);
              for($j=1;$j<=4;$j++)
              {
                  $grpTag->addTag($fake->unique()->randomElement($tab_objt_tag));
              }
              $manager->persist($grpTag);

          }
          $manager->flush();
            /////////////////////////
          $photo = $fake->imageUrl($width = 640, $height = 480);
          for($h=0;$h<3;$h++){

              $brief=new Brief();

              $brief->setTitre('titre'.$h)
                  ->setContexte('context'.$h)
                  ->setDatePoste(new \DateTime())
                  ->setDateLimite(new \DateTime())
                  ->setLangue($fake->randomElement(['français','anglais','espagnol']))
                  ->setDescriptionRapide('description'.$h)
                  ->setCricterePerformance('cricterePerformance'.$h)
                  ->setModalitePedagogique('MoodalitePedagogique'.$h)
                  ->setModaliteDevaluation('ModaliteEvaluation'.$h)
                  ->setListeLivrable($fake->randomElement(['trello','github','figma']))
                  ->setArchivage(false)
                  ->setEtat($fake->randomElement(['brouillon','validé','assigné']))
                  ->setFormateur($fake->randomElement($tab_formateurs))
                  ->setReferentiel($fake->randomElement($tab_referentiel));
              $group_princ->addBrief($brief);
              $brief->addGroupe($group_princ);
              $brief->addPromo($promo);






              for($m=0;$m<4;$m++){
                  $brief->addTag($fake->randomElement($tab_objt_tag));
              }
            $tag->addBrief($brief);
              for($x=0;$x<2;$x++){

                  $livrable_attendu=new LivrableAttendus();
                  $livrable_attendu->setLibelle('libelle'.$i)
                                    ->addBrief($brief);
                  $manager->persist($livrable_attendu);
                  for($l=0;$l<2;$l++){
                      $livrable = new LivrableAttenduApprenant();
                      $livrable->setUrl("https://github.com/seydimouhamed/PRF_G2")
                              ->setLivrableAttendu($livrable_attendu)
                              ->setApprenant($fake->unique()->randomElement($tab_apprenant));
                      $manager->persist($livrable);
                  }

              }
              $promo->addBrief($brief);
              for($y=0;$y<2;$y++){
                  $ressource=new Ressource();
                  $ressource->setTitre('titre'.$y)
                            ->setUrl('url'.$y)
                            ->setBrief($brief);
                  $manager->persist($ressource);
              }
              for($j=1;$j<=3;$j++)
              {
                  $niveau=new Niveau();
                  $niveau->setLibelle('niveau '.$j);
                  $niveau->setCritereEvaluation('competentence '.$i.'critere_evaluation '.$j);
                  $niveau->setGroupeAction('competentence '.$i.'groupe action '.$j);
                  $niveau->setCompetence($competence);
                  $niveau->addBrief($brief);
                  $brief->addNiveau($niveau);
                  $manager->persist($niveau);
              }

              for($s=1;$s<=3;$s++)
              {
                  $liv=new LivrablePartiels();
                  $liv->setLibelle('LivrablePartiel'.$s)
                      ->setDescription('Description'.$s)
                      ->setDateCreation(new \DateTime())
                      ->setStatut($fake->randomElement(['rendu','validé','invalidé']))
                      ->setDelai(new \DateTime())
                      ->addNiveau($niveau);
                  $niveau->addLivrablesPartiel($liv);
                  $manager->persist($liv,$niveau);
              }
              $t=[];
              for ($d=0;$d<2;$d++)
              {
                  $discussion=new FilDiscussion();
                  $discussion->setLibelle("discussion".$d);
                  $t[]=$discussion;
                  $manager->persist($discussion);
                  $commentaire=new Commentaires();
                  $commentaire->setFilDiscussion($discussion);
                  $commentaire->addFormateur($fake->randomElement($tab_formateurs))
                      ->setCommentaire($fake->text);
                  $discussion->addCommentaire($commentaire);
                  $manager->persist($commentaire,$discussion);

                      $livrables = new LivrablePartielApprenant();
                      $livrables->setDelais(new \DateTime())
                          ->setEtat($fake->randomElement(['validé','invalidé']))
                          ->setLivrablePartiel($liv)
                          ->setFil($fake->unique()->randomElement($t))
                          ->setApprenant($fake->unique()->randomElement($tab_apprenant));
                      $manager->persist($livrables);

              }

              $brief->addLivrableAttendu($livrable_attendu);

              $manager->persist($brief,$group_princ,$tag);
          }
      }

//recuperations des apprenants!

      $manager->flush();
        
    }
    
}