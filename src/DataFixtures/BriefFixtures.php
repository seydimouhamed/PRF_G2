<?php

namespace App\DataFixtures;

use App\Entity\Brief;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\ProfilSortie;
use App\Entity\CommunityManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BriefFixtures extends Fixture
{
    private $encoder;
    private $profilRepository;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;

    }

    public const APPRENANTS = 'apprenants';
    public const FORMATEURS = 'formateurs';


    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr-FR');

    }
}
