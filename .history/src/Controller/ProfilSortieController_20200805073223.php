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

class ProfilSortieController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;
    private $em;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->encoder=$encoder;
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


    /**
     * @Route(
     *     name="archive_profil",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::archiveProfil",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="archive_profilSortie"
     *     }
     * )
     */
    public function archiveProfil(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id)
                  ->setArchivage(1);
        $this->em->persist($profil);
        $this->em->flush();
        return $this->json(true,200);
    }


    /**
     * @Route(
     *     name="get_profilsortie_id",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getOneProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="get_one_profilSortie"
     *     }
     * )
     */
    public function getOneProfilSortie(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id);
        if($profil && !$profil->getArchivage())
        {
            return $this->json($profil,200);     
        }
        return $this->json("ce profil de sortie n'existe pas! \n ou a été archivé!",Response::HTTP_BAD_REQUEST);  
    }


    /**
     * @Route(
     *     name="put_profil",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::putOneProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="put_one_profilSortie"
     *     }
     * )
     */
    public function putOneProfilSortie(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id);
        if($profil && !$profil->getArchivage())
        {
            return $this->json($profil,200);     
        }
        return $this->json("ce profil de sortie n'existe pas! \n ou a été archivé!",Response::HTTP_BAD_REQUEST);  
    }
}
