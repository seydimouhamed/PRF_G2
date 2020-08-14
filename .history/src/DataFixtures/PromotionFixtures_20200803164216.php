<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Profil;
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


      $manager->flush();

    }
    
}
