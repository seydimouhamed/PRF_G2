<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\ProfilSortie;
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
   $tab_ps_entity=[];     
  $ProfilSortis = ["Développeur front", "back", "fullstack", "CMS", "intégrateur", "designer", "CM", "Data"];
    foreach($ProfilSortis as $ps) 
    {
        $pro_sortie=new ProfilSortie();
        $pro_sortie->setLibele($ps);
        $manager->persist($pro_sortie);
    }
      
foreach ($abbrs as $key => $abbr) 
{
  // profile de sorties

    $profil =new Profil() ;
    $profil ->setLibelle ($libelle[$key]);
    $profil ->setAbbr($abbr);
    $manager ->persist($profil);

    $manager ->flush();
        for ($i=1; $i <=count($ProfilSortis) ; $i++) {
           $user = new User();
           if($abbr=="APPRENANT")
           {

            $pro_sortie=new ProfilSortie();
            $pro_sortie->setLibele($fake->unique()->randomElement($ProfilSortis));
            $manager->persist($pro_sortie);
               //apprenant!
               $user=new Apprenant();
               $user->setGenre($fake->randomElement(['homme','femme']));
               $user->setTelephone($fake->phoneNumber());
               $user->setAdresse($fake->address());
               $user->setProfilSortie($pro_sortie);
           }
           if($abbr=="FORMATEUR")
           {
               $user=new Formateur();
           }
           $user ->setProfil ($profil);
           $user ->setUsername(strtolower ($abbr ).$i);
           $user ->setFisrtName($fake->firstName);
            // gestion de la photo
                //  $photo = fopen($fake->imageUrl($width = 640, $height = 480),"rb");
                //  $user->setPhoto($photo);
            // fin 
           $user ->setLastName($fake->lastName);
           $user ->setEmail($fake->email);
           $user->setArchivage(false);

 //Génération des Users
           $password = $this->encoder->encodePassword ($user, 'passe123' );
          $user ->setPassword ($password );
            
            $manager ->persist($user);

         }
          $manager ->flush();
 }
    }
}
