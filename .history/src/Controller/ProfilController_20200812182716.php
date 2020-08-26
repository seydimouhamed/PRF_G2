<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $serializer;
    private $validator;
    private $em;
    private $repoProfil;

    public function __construct(
        ProfilRepository $repoProfil,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
        $this->repoProfil=$repoProfil;
    }


    /**
     * @Route(
     *     name="getG_profils",
     *     path="api/admin/profils",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilController::getProfil",
     *          "__api_resource_class"=Profil::class,
     *          "__api_collection_operation_name"="get_appreanant_groupe"
     *     }
     * )
     */
    public function getProfil()
    {
        $profils=$this->repoProfil->findByArchivage(0);
        
        $returnProfils=[];
        foreach($profils as $pfl)
        {
           $arr=["id"=>$pfl->getID(),"libelle"=>$pfl->getLibelle(), "abbr"=>$pfl->getAbbr()];
            $returnProfils[]=$arr;
        }

        
        return $this->json($returnProfils,201);
    }



    /**
     * @Route(
     *     name="archive_profils",
     *     path="api/admin/profils/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilController::archiveProfilWithUsers",
     *          "__api_resource_class"=Profil::class,
     *          "__api_collection_operation_name"="delete_profil"
     *     }
     * )
     */
    public function archiveProfilWithUsers(UserRepository $userRepository,int $id)
    {
        
        $profil = $this->em->getRepository(profil::class)->find($id);
        if($profil!==null )
        {
            if($profil->getArchivage()!==true)
            {
                $profil->setArchivage(true);
            
                $users=$userRepository->findByProfil($id);
                foreach($users as $user){

                        $user->setArchivage(true);
                        $this->em->persist($user);
                        $this->em->flush();
                }
                return $this->json("success",201);
            }
            return $this->json("profil déja archivé!",401);
        }
        return $this->json("profiln'existe pas!",401);
        
    }



     /**
     * @Route(
     *     name="get_UsersByProfil",
     *     path="api/admin/profils/{id}/users",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilController::getUsersByProfil",
     *          "__api_resource_class"=Profil::class,
     *          "__api_collection_operation_name"="get_getUsersByProfil"
     *     }
     * )
     */
    public function getUsersByProfil(UserRepository $userRepository,int $id)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=3;
        $offset=($page-1)*$limit;
        
        $profil = $this->em->getRepository(profil::class)->findBy(["id"=>$id], ['id'=>"DESC"], $limit,$offset);
        if($profil!==null )
        {
            if($profil[0]->getArchivage()!==true)
            {
            
                
                return $this->json($profil,201);
            }
            return $this->json("profil déja archivé!",401);
        }
        return $this->json("profiln'existe pas!",401);
        
    }
}
