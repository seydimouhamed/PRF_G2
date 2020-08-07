<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use Faker\Factory;
use App\Entity\GroupeCompetence;
use App\Entity\Niveau;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Description;


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


        foreach($competences as $value){
            $groupeC=new GroupeCompetence();
            $comp=new Competence();
            $tag=new Tag();

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
            $manager->persist($groupeC);

            $comp->setLibelle($fake->unique()->randomElement($competences))
                ->setDescriptif('Descriptif'.$key)
                ->addGroupeCompetence($groupeC);
            $manager->persist($comp);

            $tag->setLibelle($fake->unique()->randomElement($tags))
                ->setDescription('Description'.$key)
                ->addGroupeCompetence($groupeC);
            $manager->persist($tag);

        }


        $manager ->flush();
    }
}