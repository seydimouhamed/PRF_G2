<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TagFixtures extends Fixture
{

    public function __construct()
    {

    }
    


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');
        for($i=1;$i<=10;$i++)
        {
            $tag=new Tag();
        }
        
      $manager->flush();

    }
    
}
