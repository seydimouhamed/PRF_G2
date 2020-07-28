<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $encoder;
    private $profilRepository;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder=$encoder; 
      // $this->profilRepository=$profilRepository;

    }
    public function load(ObjectManager $manager)
    {

        $role=['Administrateur',"Formateur","Community Manager","Aprenant"];
       $abbre=["admin","formateur","CM","aprenants"];
       $taProfil=[];
       for($i=0;$i<4;$i++)
       {
          $profil=new Profil();
          $profil->setLibelle($role[$i]);
          $profil->setAbbre($abbre[$i]);
          $tabProfil[$i]=$profil;
          $manager->persist($profil);
          $manager->flush();
       }
       
         // $roleInt=[1,2,3,4];
        for($i=0;$i<20;$i++)
        {
         $user = new User();
         $intAlea=rand(0,3);
         $profil=$tabProfil[$intAlea];
          //les valeurs!
          $user->setNom('Prenom'.$i)
          ->setPrenom('Nom'.$i)
          ->setPassword($this->encoder->encodePassword($user, 'passer123'))
          ->setEmail('email'.$i.'@gmail.com')
          ->setProfil($profil)
          ->setUsername('admin'.$i);
          
             $manager->persist($user);
 
        }
        $manager->flush();
    }
}
