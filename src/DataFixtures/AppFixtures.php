<?php

namespace App\DataFixtures;

use App\Entity\Apprenants;
use App\Entity\Formateurs;
use App\Entity\Profil;
use Faker\Factory;
use App\Entity\Utilisateurs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

     /**
     *
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
        
    }
    public function load(ObjectManager $manager)
    {
        $faker=Factory::create('fr_FR');
        $profil=['ADMINISTRATEUR',"FORMATEURS","COMMUNITY_MANAGER","APPRENANT"];
        // $product = new Product();
        // $manager->persist($product);

        foreach($profil as $value){

            $mesprofil=new Profil();
            $mesprofil->setLibelle($value);
            $manager->persist($mesprofil);
            $user=new Utilisateurs;

            if($value=="APPRENANT"){
                //apprenant!
                   $user=new Apprenants();
                   $user->setGenre($faker->randomElement(['homme','femme']));
                   $user->setTelephone($faker->phoneNumber());
                   $user->setAdresse($faker->address());
           }
            if($value=="FORMATEURS")
            {
                $user=new Formateurs();
            }
            $user->setEmail($faker->email())
                ->setPrenom($faker->firstName())
                ->setProfil($mesprofil)
                ->setNom($faker->name())
                ->setPassword($this->encoder->encodePassword($user,"sidibe123"))
                ->setAvatar(fopen($faker->imageUrl($width=100,$height=100),'rb'));
                
             
               
                $manager->persist($user);
        }

        $manager->flush();
    }
}


 




   

