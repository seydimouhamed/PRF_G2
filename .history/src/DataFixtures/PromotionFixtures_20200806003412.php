<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Promotion;
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
        for($i=1 ; $i<=2 ; $i++)
        {
            $promo=new Promotion();
        }
        $manager->flush();

        $manager->flush();
        
      $manager->flush();

    }
    
}
