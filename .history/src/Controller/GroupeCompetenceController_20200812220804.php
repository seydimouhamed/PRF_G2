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
    private $repoGC;
    public function __construct(
        SerializerInterface $serializer,
        GroupeCompetenceRepository $repoGC,
        EntityManagerInterface $em)
    {
        $this->repoGC=$repoGC;
        $this->serializer=$serializer;
        $this->em=$em;
    }
    /**
     * @Route(
     *     name="get_all",
     *     path="/api/admin/grpecompetences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::getAllGpre",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="get_all_grpe"
     *     }
     * )
     */
    public function getAllGpre(Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=2;
        $offset=($page-1)*$limit;

        $allgroup = $this->repoGC->findByArchivage(0,$limit,$offset); 
         $tab_all_gc=[];

         foreach($allgroup as $all)
         {
             $tab_all_gc[]=['id'=>$all->getID(),"libelle"=>$all->getLibelle(),"description"=>$all->getDescription()];

             $tab_comp=[];
             foreach($all->getCompetences() as $comp)
             {
                 $tab_comp[]=['id'=>$comp->getID(),"libelle"=>getLibelle()];
             }
             $tab_all_gc['competences'][]=$tab_comp;
         }


        return $this->json($tab_all_gc,201);
     }


    /**
     * @Route(
     *     name="get_gc",
     *     path="/api/admin/grpecompetence/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::getGpreComp",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="get_grpe_c"
     *     }
     * )
     */
    public function getGpreComp(Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=3;
        $offset=($page-1)*$limit;

        $this->repo->findByArchivage(0,$limit,$offset); 
        return $this->json($promo,201);
     }


     /**
      * @Route(
      *     name="get_gc_id",
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
        //  $profil=$repo->find($id);
        //  if($profil && !$profil->getArchivage())
        //  {
        //      $pSorties =json_decode( $request->getContent(),true);
        //      foreach($pSorties as $k => $ps)
        //      {
        //          $profil->{"set".ucfirst($k)}($ps);
        //      }
        //      $this->em->persist($profil);
        //      $this->em->flush();
        //      return $this->json($profil,200);     
        //  }
        //  return $this->json($promo,201);
      }


}
