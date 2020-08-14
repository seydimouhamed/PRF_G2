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
        $tab=["HTML5", "php", "javascript", "angular", "wordpress", "bootstrap","json"];
        for($i=1;$i<=count($tab);$i++)
        {
            $tag=new Tag();
            $tag->setLibelle(($fake->unique()->randomElement());
            $tag->setDescription($fake->unique()->randomElement());

            

        }
        
      $manager->flush();

    }
    
}
