<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PromotionFixtures extends Fixture
{


    


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        
        
      $manager->flush();

    }
    
}
