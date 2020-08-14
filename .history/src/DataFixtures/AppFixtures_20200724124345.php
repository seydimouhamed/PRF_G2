<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
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
        $regions = $this->repo->findAll();
        $fake = Factory::create('fr-FR');


        $abbr=["ADMIN","FORMATEUR" ,"APPRENANT" ,"CM"];
       $libel=["admin","formateur","CM","aprenants"];
foreach ($profils as $key => $libelle) 
{
    $profil =new Profil() ;
    $profil ->setLibelle ($libelle );
    $manager ->persist($profil);
    $manager ->flush();
        for ($i=1; $i <=3 ; $i++) {
           $user = new User();
           $user ->setProfil ($profil);
           $user ->setLogin (strtolower ($libelle ).$i);
           $user ->setNomComplet($fake->name);
 //Génération des Users
           $password = $this->encoder->encodePassword ($user, 'pass_1234' );
          $user ->setPassword ($password );

            $manager ->persist($user);

         }
          $manager ->flush();
 }
    }
}
