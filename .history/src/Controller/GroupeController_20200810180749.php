<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilSortie;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GroupeController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;
    private $em;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
    }


    /**
     * @Route(
     *     name="getProfilSortie",
     *     path="/api/admin/profilSorties",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="get_profil_sortie"
     *     }
     * )
     */
    public function getProfilSortie(ProfilSortieRepository $repo)
    {
        $user= $repo->findByArchivage(0);
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($user,200);
    }

}
