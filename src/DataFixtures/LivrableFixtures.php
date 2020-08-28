<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Promotion;
use App\Entity\CompetencesValide;
use App\Repository\ApprenantRepository;
use App\DataFixtures\CompetenceFixtures;
use App\Repository\CompetenceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LivrableFixtures extends Fixture implements DependentFixtureInterface
{


    private $apprenantRepo;
    private $competenceRepo;
    public function __construct(ApprenantRepository $apprenantRepo,CompetenceRepository $competenceRepo)
    {
        $this->apprenantRepo=$apprenantRepo;
        $this->competenceRepo=$competenceRepo;
     }
    
    
public function getDependencies()
{
    return array(
        CompetenceFixtures::class,
    );
}


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        
        
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
    //récupération de tout les competences! //
    //-----------------------------------//
        $comptences=$this->competenceRepo->findAll(); 
        $tab_comptences=[];
        foreach($comptences as $com)
        {
            $tab_comptences[]=$com;
        }


        for($i=0;$i<50;$i++)
        {
            $apprenant=$fake->unique()->randomElement($tab_apprenant);
            for($j=0;$j<($fake->randomElement([1,2,3]));$j++)
            {
                $competence=$fake->randomElement($tab_comptences);
                $compVal=new CompetencesValide();
                $compVal->setApprenant($apprenant);
                $compVal->setCompetence($competence);
                $compVal->setNiveau1($fake->randomElement(["oui","non","oui"]));
                $compVal->setNiveau2($fake->randomElement(["oui","non"]));
                $compVal->setNiveau3($fake->randomElement(["oui","non","non"]));
                $manager->persist($compVal);
                
            }
        }
        $manager->flush();

    }


    
}
