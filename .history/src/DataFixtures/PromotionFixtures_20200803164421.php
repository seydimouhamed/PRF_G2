<?php

namespace App\DataFixtures;

use Faker\Factory;
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
