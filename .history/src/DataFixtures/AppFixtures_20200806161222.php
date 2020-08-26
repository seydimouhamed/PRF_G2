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

     }
    
    public const APPRENANTS= 'apprenants';


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');

        
        $tab_users=[];//tableau contenant les objets des users!

        $abbrs=["ADMIN","FORMATEUR"  ,"CM","APPRENANT"];
        $libelle=['Administrateur',"Formateur","Community Manager","Aprenant"];
        $tab_ps_entity=[];     
      $ProfilSortis = ["Développeur front", "back", "fullstack", "CMS", "intégrateur", "designer", "CM", "Data"];
        foreach($ProfilSortis as $ps) 
        {
            $pro_sortie=new ProfilSortie();
            $pro_sortie->setLibele($ps);
            $tab_ps_entity[]=$pro_sortie;
            $manager->persist($pro_sortie);
        }
        $manager->flush();
      
        foreach ($abbrs as $key => $abbr) 
        {
        // profile de sorties

          $profil =new Profil() ;
          $profil ->setLibelle ($libelle[$key]);
          $profil ->setAbbr($abbr);
          $manager ->persist($profil);

          $manager ->flush();
              for ($i=1; $i <=3 ; $i++) {
                $user = new User();
                if($abbr=="APPRENANT")
                {

                  // $pro_sortie=new ProfilSortie();
                  // $pro_sortie->setLibele($fake->unique()->randomElement($ProfilSortis));
                  // $manager->persist($pro_sortie);
                    //apprenant!
                    $user=new Apprenant();
                    $user->setGenre($fake->randomElement(['homme','femme']));
                    $user->setTelephone($fake->phoneNumber());
                    $user->setAdresse($fake->address());
                    $user->setProfilSortie($fake->randomElement($tab_ps_entity));
                }
                if($abbr=="FORMATEUR")
                {
                    $user=new Formateur();
                }
                $user ->setProfil ($profil);
                $user ->setUsername(strtolower ($abbr ).$i);
                $user ->setFisrtName($fake->firstName);
                  // gestion de la photo
                      $photo = fopen($fake->imageUrl($width = 640, $height = 480),"rb");
                      $user->setPhoto($photo);
                  // fin 
                $user ->setLastName($fake->lastName);
                $user ->setEmail($fake->email);
                $user->setArchivage(false);

             //Génération des Users
                $password = $this->encoder->encodePassword ($user, 'passe123' );
                $user ->setPassword ($password );
                  
                $tab_users[]=$user;
                  $manager ->persist($user);

              }
              $this->addReference(self::APPRENANTS, $tab_users);
                $manager ->flush();
        }
    }
}
