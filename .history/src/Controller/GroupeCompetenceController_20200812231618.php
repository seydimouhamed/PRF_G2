<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
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
        $limit=10;
        $offset=($page-1)*$limit;

        $allgroup = $this->repoGC->findByArchivage(0,$limit,$offset); 
         $tab_all=[];

         foreach($allgroup as $all)
         {
             $tab_all_gc['id']=$all->getID();
             $tab_all_gc["libelle"]=$all->getLibelle();
             $tab_all_gc["description"]=$all->getDescription();


             $tab_all_gc['competences']=[];
             foreach($all->getCompetences() as $comp)
             {
                $tab_all_gc['competences'][]=$comp;
             }

             $tab_all[]=$tab_all_gc;
         }


        return $this->json($tab_all,201);
     }


    /**
     * @Route(
     *     name="get_gc",
     *     path="/api/admin/grpecompetences/competences",
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

        $tab=[];
        $page = (int) $request->query->get('page', 1);
        $limit=10;
        $offset=($page-1)*$limit;

        
         $tableau = $this->em->getRepository(GroupeCompetence::class)->findByArchivage(0,$limit,$offset);

         for($i=0;$i<count($tableau);$i++){
                $tab_pivot['groupeComptence']=$tableau[$i]->getLibelle();
                $tab_pivot['comptences']=[];
             for($a=0;$a<count($tableau[$i]->getCompetences());$a++){

                $tab_pivot['comptences'][]=  $tableau[$i]->getCompetences()[$a]->getLibelle();
             }

             $tab[]=$tab_pivot;

         }
        return $this->json($tab,200);
     }


     /**
      * @Route(
      *     name="delete_gc_id",
      *     path="/api/admin/grpecompetences/{id}",
      *     methods={"DELETE"},
      *     defaults={
      *          "__controller"="App\Controller\GroupeCompetenceController::deleteGpreidComp",
      *          "__api_resource_class"=GroupeCompetence::class,
      *          "__api_collection_operation_name"="delete_id_grpe_c"
      *     }
      * )
      */
      public function deleteGpreidComp($id)
      {
          $grc=$this->repoGC->find($id);
          if($grc || !$grc->getArchivage())
          {
                 $grc->setArchivage(true);
                 $this->em->persist($grc);
                 $this->em->flush();

                 return$this->json('success',201);
          }
          return $this->json("ce groupe de compétence n'existe pas ou a été archivé!",401);
       }
 


     /**
      * @Route(
      *     name="get_gc_id",
      *     path="/api/admin/grpecompetences/{id}/competences",
      *     methods={"GET"},
      *     defaults={
      *          "__controller"="App\Controller\GroupeCompetenceController::getGpreidComp",
      *          "__api_resource_class"=GroupeCompetence::class,
      *          "__api_collection_operation_name"="get_id_grpe_c"
      *     }
      * )
      */
      public function getGpreidComp($id)
      {
        $grc=$this->repoGC->find($id);
        $tab=[];
        if($grc || !$grc->getArchivage())
        {
                 $tab["groupecompetence"]=$grc->getLibelle();
                 $tab["competences"]=[];
                foreach( $grc->getCompetences() as $comp)
                {
                    $tab["competences"][]=$comp;
                }
               return$this->json($tab,201);
        }
        return $this->json("ce groupe de compétence n'existe pas ou a été archivé!",401);
       }
 

    /**
     * @Route(
     *     name="add_competence",
     *     path="/api/admin/grpecompetences/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::add",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_item_operation_name"="addCompetenceGrpupeCompetence"
     *     }
     * )
     */
    public  function addCompetenceGrpupeCompetence(Request $request,EntityManagerInterface $entityManager,int $id){

        $groupeCompetence= $entityManager->getRepository(GroupeCompetences::class)->find($id);
        $reponse=json_decode($request->getContent(),true);
        $competence=$reponse['competences'];
        $Competence= $entityManager->getRepository(Competences::class)->findOneBy(['libelle'=>$competence]);
        $idCompGroupe=$Competence->getGroupeCompetence()[0]->getId();

        if($idCompGroupe==$groupeCompetence->getId()){
            return $this->json("Cette  competence est deja associé a cet groupe de competence Veuillez ajouter une autre competence",200);

        }else{
            if($reponse['action']=="supprimer"){
                $groupeCompetence ->removeCompetence($Competence);
            }

            if($reponse['action']=="ajouter"){
                $groupeCompetence ->addCompetence($Competence);
            }
            $entityManager->persist($groupeCompetence);
            $entityManager->flush();
            return $this->json($groupeCompetence->getCompetences(),200);
        }

      //  return $this->json($idCompGroupe,200);

    }
  
}
