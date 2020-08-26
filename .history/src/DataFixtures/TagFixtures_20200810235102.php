<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\GroupeTag;
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
        $tab=["HTML5", "php", "javascript", "angular", "wordpress", "bootstrap","json","python","java","joomla","c++","fortran","algo"];
        
        $tab_objt_tag=[];
        for($i=1;$i<=count($tab);$i++)
        {
            $tag=new Tag();
            $tag->setLibelle($tab[$i]);
            $tag->setDescription("description ".$i);

            $tab_objt_tag[]=$tag;
            $manager->persist($tag);
        }
        
      $manager->flush();

      for($i=1;$i<=3;$i++)
      {
          $grpTag=new GroupeTag();
          $grpTag->setLibelle('libelle '.$i);
          for($j=1;$j<=4;$j++)
          {
              $grpTag->addTag($fake->unique()->randomElement($tab_objt_tag));
          }
            $manager->persist($grpTag);
          
      }
      $manager->flush();

    }
    
}
