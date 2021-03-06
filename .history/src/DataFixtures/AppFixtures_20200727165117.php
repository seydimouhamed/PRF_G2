<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $profilRepository;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder=$encoder; 
      // $this->profilRepository=$profilRepository;

     }
    // public function load(ObjectManager $manager)
    // {

    //     $role=['Administrateur',"Formateur","Community Manager","Aprenant"];
    //    $abbre=["admin","formateur","CM","aprenants"];
    //    $taProfil=[];
    //    for($i=0;$i<4;$i++)
    //    {
    //       $profil=new Profil();
    //       $profil->setLibelle($role[$i]);
    //       $profil->setAbbr($abbre[$i]);
    //       $tabProfil[$i]=$profil;
    //       $manager->persist($profil);
    //       $manager->flush();
    //    }
       
    //      // $roleInt=[1,2,3,4];
    //     for($i=0;$i<20;$i++)
    //     {
    //      $user = new User();
    //      $intAlea=rand(0,3);
    //      $profil=$tabProfil[$intAlea];
    //       //les valeurs!
    //       $user->setLastName('Prenom'.$i)
    //       ->setFisrtName('Nom'.$i)
    //       ->setPassword($this->encoder->encodePassword($user, 'passer123'))
    //       ->setEmail('email'.$i.'@gmail.com')
    //       ->setProfil($profil)
    //       ->setUsername('admin'.$i);
          
    //          $manager->persist($user);
 
    //     }
    //     $manager->flush();
    // }


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');


        $abbrs=["ADMIN","FORMATEUR"  ,"CM","APPRENANT"];
        $libelle=['Administrateur',"Formateur","Community Manager","Aprenant"];
      
foreach ($abbrs as $key => $abbr) 
{
    $profil =new Profil() ;
    $profil ->setLibelle ($libelle[$key]);
    $profil ->setAbbr($abbr);
    $manager ->persist($profil);

    $manager ->flush();
        for ($i=1; $i <=3 ; $i++) {
           $user = new User();
           if($abbr=="APPRENANT")
           {
               $user=new Apprenant();
               $user->setGenre($fake->randomElement(['homme','femme']));
               $user->setTelephone($fake->phoneNumber)
           }
           $user ->setProfil ($profil);
           $user ->setUsername(strtolower ($abbr ).$i);
           $user ->setFisrtName($fake->firstName);
            // gestion de la photo
                 $photo = fopen($fake->imageUrl($width = 100, $height = 100),"rb");
                 $user->setPhoto($photo);
            // fin 
           $user ->setLastName($fake->lastName);
           $user ->setEmail($fake->email);

 //Génération des Users
           $password = $this->encoder->encodePassword ($user, 'passe123' );
          $user ->setPassword ($password );
            
            $manager ->persist($user);

         }
          $manager ->flush();
 }
    }
}
