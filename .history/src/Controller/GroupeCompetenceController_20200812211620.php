<?php

namespace App\Controller;

use App\Entity\GroupeCompetences;
use App\Entity\Competences;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCompetencesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer){
        $this->serializer=$serializer;
    }

    /**
     * @Route(
     *     name="get_grpcompetence_competence",
     *     path="/api/admin/grpecompetences/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::add",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="get_admin_grpecompetences_comptence"
     *     }
     * )
     */
        public function GroupecompetenceCompetence(EntityManagerInterface $entityManager)
        {

            $tab=[];
             $tableau = $entityManager->getRepository(GroupeCompetences::class)->findAll();

             for($i=0;$i<count($tableau);$i++){


                 for($a=0;$a<count($tableau[$i]->getCompetences());$a++){

                     $tab[]= "GroupeCompetence_".$i." => ".$tableau[$i]->getLibelle()."; 'compences' => ".$tableau[$i]->getCompetences()[$a]->getLibelle();
                 }

             }
            return $this->json($tab,200);


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
