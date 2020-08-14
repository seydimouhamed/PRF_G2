<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    private $serializer;
    private $em;

    public function __construct(
        SerializerInterface $serializer,
        GroupeCompetenceRepository $repo,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->em=$em;
    }
    /**
     * @Route(
     *     name="get_gc",
     *     path="/api/admin/grpecompetence/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::add",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="get_grpe_c"
     *     }
     * )
     */
    public function getGpreComp(Request $request)
    {
        $profil=$repo->find($id);
        if($profil && !$profil->getArchivage())
        {
            $pSorties =json_decode( $request->getContent(),true);
            foreach($pSorties as $k => $ps)
            {
                $profil->{"set".ucfirst($k)}($ps);
            }
            $this->em->persist($profil);
            $this->em->flush();
            return $this->json($profil,200);     
        }
        return $this->json($promo,201);
     }
     /**
      * @Route(
      *     name="get_gc",
      *     path="/api/admin/grpecompetence/{id}/competences",
      *     methods={"GET"},
      *     defaults={
      *          "__controller"="App\Controller\GroupeCompetenceController::getGpreidComp",
      *          "__api_resource_class"=GroupeCompetence::class,
      *          "__api_collection_operation_name"="get_id_grpe_c"
      *     }
      * )
      */
     public function getGpreidComp(Request $request)
     {
         $profil=$repo->find($id);
         if($profil && !$profil->getArchivage())
         {
             $pSorties =json_decode( $request->getContent(),true);
             foreach($pSorties as $k => $ps)
             {
                 $profil->{"set".ucfirst($k)}($ps);
             }
             $this->em->persist($profil);
             $this->em->flush();
             return $this->json($profil,200);     
         }
         return $this->json($promo,201);
      }


}
