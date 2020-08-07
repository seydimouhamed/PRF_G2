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
            $promo->setDateDebut($fake->date_create())
            ->setDateFinPrvisoire($fake->date_create())
            ->setDescription($fake->text)
            ->setFabrique("fabrique 1")
            ->setLangue('français')
            ->setLieu('lieu1')
            ->setStatus("encours")
            ->setTitre('promo samba ndiaye');

            $manager->flush();
        }
        
      $manager->flush();

    }
    
}
