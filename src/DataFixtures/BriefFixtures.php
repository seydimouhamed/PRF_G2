<?php

namespace App\DataFixtures;

use App\Entity\Commentaires;
use App\Entity\EtatbriefGroupe;
use App\Entity\FilDiscussion;
use App\Entity\Groupes;
use App\Entity\LivrableAttenduApprenant;
use App\Entity\LivrableAttendus;
use App\Entity\LivrablePartielApprenant;
use App\Entity\Ressource;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Brief;
use App\Entity\Niveau;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use App\Entity\Commentaire;
use App\Entity\Referentiel;
use App\Entity\BriefMaPromo;
use App\Entity\FilDiscution;
use App\Entity\BriefApprenant;
use App\Entity\LivrablePartiels;
use App\DataFixtures\LivrableFixtures;
use App\Entity\AprenantLivrablePartiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BriefFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct()
    {

     }
    
    
     public function getDependencies()
     {
         return array(
             LivrableFixtures::class,
         );
     }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');

        $formateurs=$manager->getRepository(Formateur::class)->findAll();
        $tab_formateurs=[];
        foreach($formateurs as $ref)
        {
            $tab_formateurs[]=$ref;
        }

        //recuperations de apprenants
        $apprenants=$manager->getRepository(Apprenant::class)->findAll();
        $tab_ap=[];
        foreach($apprenants as $rap)
        {
            $tab_ap[]=$rap;
        }


        $referentiels=$manager->getRepository(Referentiel::class)->findAll();
        $tab_referentiels=[];
        foreach($referentiels as $ref)
        {
            $tab_referentiels[]=$ref;
        }
        $tags=$manager->getRepository(Tag::class)->findAll();
        $tab_tags=[];
        foreach($tags as $tag)
        {
            $tab_tags[]=$tag;
        }
//Promotion
        $promos=$manager->getRepository(Promotion::class)->findAll();


        $groupe=$manager->getRepository(Groupes::class)->findAll();

        $niveaux=$manager->getRepository(Niveau::class)->findAll();
        $tab_niveaux=[];
        foreach($niveaux as $niv)
        {
            $tab_niveaux[]=$niv;
        }

        $tab_briefs=[];
        for($i=1;$i<=10;$i++)
        {
            $brief=new Brief();
            $brief->setContexte("context brief ".$i);
            $brief->setArchivage(false)
                  ->setTitre('Titre '.$i)
                  ->setCricterePerformance('Criteres de performance '.$i)
                  ->setDateLimite($fake->datetime)
                  ->setDatePoste($fake->datetime)
                  ->setDescriptionRapide($fake->text)
                  ->setFormateur($fake->randomElement($tab_formateurs))
                  ->setEtat( $fake->randomElement(['brouillon','valide','assigne']))
                  ->setLangue('francais')
                  ->setListeLivrable('liste livrables '.$i)
                  ->setModaliteDevaluation('Modalité d evaluation '.$i)
                  ->setModalitePedagogique('Modalité pédagoique '.$i)
                  ->setReferentiel($fake->randomElement($tab_referentiels));
                $photo = fopen($fake->imageUrl($width = 640, $height = 480),"rb");
                $brief->setImageExemplaire($photo);
                   for($j=1;$j<=5;$j++)
                   {
                        $brief->addNiveau($fake->unique()->randomElement($tab_niveaux));
                        $brief->addTag($fake->unique()->randomElement($tab_tags));
                              
                   }
                   $tab_briefs[]=$brief;
                  $fake->unique($reset = true );
                  $manager->persist($brief);
        }
      $manager->flush();

      //-------------------//
      //    BRIEF PROMO    //
      //-------------------//
        $tab_briefPromo=[];
        for($i=1;$i<=5;$i++)
        {
            $briefPromo=new BriefMaPromo();
            $briefPromo->setBrief($fake->unique()->randomElement($tab_briefs));
            $briefPromo->setPromo($fake->randomElement($promos));

            $tab_briefPromo[]=$briefPromo;
            $manager->persist($briefPromo);
        }
        for($i=1;$i<=5;$i++)
        {
            $etat=new EtatbriefGroupe();
            $etat->setGroupe($fake->unique()->randomElement($groupe));
            $etat->setStatut($fake->randomElement(['validé',"invalidé"]));
            $etat->setBriefs($fake->unique()->randomElement($tab_briefs));

            $manager->persist($etat);
        }

        $manager->flush();

        //--------------------------//
        //   BRIEF PROMO APPRENANT  //
        //--------------------------//
      for($a=0;$a<4;$a++)
      {
          $apprt=$fake->unique()->randomElement($apprenants);
            for($i=0;$i<5;$i++)
            {
                $briefappr=new BriefApprenant();
                $briefappr->setApprenant($apprt);
                $briefappr->setStatut($fake->randomElement(['valide','rendu','assigne']));
                $briefappr->setBriefPromo($tab_briefPromo[$a]);

                $manager->persist($briefappr);
            }
            $manager->flush();
          
      }

      $tab_livrablePartiel=[];
      foreach($tab_briefPromo as $k => $tbp)
      {
            for($i=0;$i<4;$i++)
            {
                $lp=new LivrablePartiels();
                $lp->setDelai($fake->datetime)
                    ->setType($fake->randomElement(['ISAS','Code','modelisation']))
                    ->setDateCreation($fake->datetime)
                    ->setDescription($fake->text)
                    ->setEtat($fake->randomElement(['rendu','valide','invalide','assigne']))
                    ->setLibelle('libelle promo'.$k. '_'.$i)
                    ->setNbreCorriger($fake->numberBetween($min = 10, $max = 20))
                    ->setNbreRendu($fake->numberBetween($min = 20, $max = 30))
                    ->setBriefMaPromo($tbp)
                    ->addNiveau($fake->unique()->randomElement($tab_niveaux));

                    $tab_livrablePartiel[]=$lp;

                $manager->persist($lp);
                  
            }

            $manager->flush();
      }
      $ta=[];
        for ($d=0;$d<2;$d++)
        {
            $discussion = new FilDiscussion();
            $discussion->setLibelle("Discussion".$d);
            $manager->persist($discussion);

            $livrables = new LivrablePartielApprenant();
            $livrables->setDelais(new \DateTime())
                ->setEtat($fake->randomElement(['validé','invalidé']))
                ->setLivrablePartiel($fake->randomElement($tab_livrablePartiel))
                ->setApprenant($fake->unique()->randomElement($tab_ap));
            $livrables->setFil($discussion);
            $ta[]=$livrables;
            $manager->persist($livrables);


            $commentaire=new Commentaires();
            $commentaire->setCommentaire($fake->text)
                ->setFilDiscussion($discussion);
            $commentaire->addFormateur($fake->randomElement($tab_formateurs));
            $discussion->addCommentaire($commentaire);
            $manager->persist($commentaire,$discussion);
        }


        for($x=0;$x<2;$x++){

            $livrable_attendu=new LivrableAttendus();
            $livrable_attendu->setLibelle("libelle".$x)
                ->addBrief($brief);
            $manager->persist($livrable_attendu);
            for($l=0;$l<2;$l++){
                $livrable = new LivrableAttenduApprenant();
                $livrable->setUrl("https://github.com/seydimouhamed/PRF_G2")
                    ->setLivrableAttendu($livrable_attendu)
                    ->setApprenant($fake->unique()->randomElement($tab_ap));
                $manager->persist($livrable);
            }

        }
        for($y=0;$y<2;$y++){
            $ressource=new Ressource();
            $ressource->setTitre('titre'.$y)
                ->setUrl('url'.$y)
                ->setPieceJointe("PieceJointe.$y")
                ->setType("type".$y)
                ->setBrief($brief);
            $manager->persist($ressource);
        }

        $manager->flush();
    }
    
}
