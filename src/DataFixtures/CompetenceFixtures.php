<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\Groupes;
use App\Entity\GroupeTag;
use App\Entity\Promotion;
use App\Entity\Referentiel;
use Faker\Factory;
use App\Entity\GroupeCompetence;
use App\Entity\Niveau;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Description;
use PhpParser\Builder\Class_;


class CompetenceFixtures extends Fixture
{
    private $encoder;
    private $profilRepository;
    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');

        //groupeCompetence
        $groupeCompentences=["Développer le back-end d’une application web","Envoyer des emails automatiques","Développer les composants d’accès aux données"];
        $competences=["Créer une base de données","Validation serveur et client","generer un token"];
        $tags=["php","javascript","symfony","jqery"];
        $groupedaction=[
            "A partir d'un schéma
                    physique de données et
                    dans le contexte d'un
                    besoin client décrit créer
                    une base de données sur
                    un SGBD désigné",
            "Creer un formulaire d'inscription
                     generique selon 
                    les besoins de l'utilisteur ",
            "Creer une application qui permet 
                    de sauvegarder des donnees securise 
                    avec avec email et un motdepasse",
        ];
        $Criteredevaluation=[
            "La BD est créée à l'aide
                        d'un script sans erreur et
                        les données sont
                        insérées à l'aide d'un
                        script sans erreur",
            "Les donnees serons envoyées 
                        par ajax et bien valider",

        ];

        $niveau=['Niveau_1','Niveau_2','Niveau_3'];
//groupe

        $tab_grpCompetence=[];
        $tab_referentiel=[];
        foreach($competences as $value){
            $groupeC=new GroupeCompetence();
            $comp=new Competence();
            foreach ($niveau as $key => $niv){
                $niveau=new Niveau();
                $niveau->setLibelle($niv)
                    ->setCritereEvaluation($fake->randomElement($Criteredevaluation))
                    ->setGroupeAction($fake->randomElement($groupedaction))
                    ->setCompetence($comp);
                $manager->persist($niveau);
            }
            $groupeC->setLidelle($fake->unique()->randomElement($groupeCompentences))
                    ->setDescription('Description'.$key)
                    ->addCompetence($comp);
            $tab_grpCompetence[]=$groupeC;
            $manager->persist($groupeC);

            $comp->setLibelle($fake->unique()->randomElement($competences))
                ->setDescriptif('Descriptif'.$key)
                ->addGroupeCompetence($groupeC);
            $manager->persist($comp);

        }
        $manager ->flush();
        foreach ($tags as $key => $val){
            $t=new Tag();
            $groupetag= new GroupeTag();
            $t->setLibelle($val)
                ->setDescription('Description'.$key)
                ->addGroupeCompetence($groupeC)
                ->addGroupeTag($groupetag);
            $manager->persist($t);
            $groupetag->setLibelle($fake->unique()->randomElement($tags))
                ->addTag($t);
            $manager->persist($groupetag);
        }
        $manager ->flush();
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
        $manager ->flush();

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
                ->setTitre('promo '.$i);

            $tab_promo[]=$promo;
            $manager->persist($promo);
        }

        //insertion de grpupes!
        for($i=1; $i<=2 ; $i++)
        {
            $group=new Groupes();
            $group->setNom("group principale ".$i);
            $group->setStatut($fake->randomElement(['encours','ferme']));
            $group->setType($fake->randomElement(['binome','filerouge','general']));
            $group->setPromotions($fake->randomElement($tab_promo));

            for($j=1;$j<=2;$j++)
            {
                $group->addApprenant($this->getReference(AppFixtures::APPRENANTS));
            }
            $manager->persist($group);

        }
        $manager->flush();

    }

}