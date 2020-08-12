<?php

namespace App\Controller;
use App\Entity\user;
use App\Entity\Profil;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilArchiveController extends AbstractController
{
   public function __invoke(UserRepository $userRepository,EntityManagerInterface $entityManager,int $id)
   {
      
       $profil = $entityManager->getRepository(profil::class)->find($id);
       $profil->setArchive(false);
       
       $users=$userRepository->findByProfil($id);
       foreach($users as $user){

            $user->setArchivage(true);
            $entityManager->persist($user);
            $entityManager->flush();
       }
       
       
   }
}
