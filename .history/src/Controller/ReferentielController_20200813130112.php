<?php

namespace App\Controller;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     *     name="get_grpcompetence_competence",
     *     path="/api/admin/referentiels/grpecompetences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ReferentielController::getReferentielGroupeCompetence",
     *          "__api_resource_class"=Referentiel::class,
     *          "__api_collection_operation_name"="get_referentiels_grpCompetence"
     *     }
     * )
     */
    public function getReferentielGroupeCompetence(Request $request,EntityManagerInterface $entityManager)
    {
            $tab=[];
            $tableau = $entityManager->getRepository(Referentiel::class)->findAll();

            $competence= $tableau[0]->getGrpCompetences()[0]->getCompetences()[0]->getLibelle();
        $cou=$groupCompetence=$tableau[0]->getGrpCompetences()[0]->getLibelle();

        for ($i=0;$i<count($tableau);$i++){

            $tab[]=$tableau[$i]->getGrpCompetences()[0];
        }
        //return dd($tab);
        
        return $this->json($tab,201);
        }



        /**
         * @Route(
         *     name="get_grpcompetence_id_competence_id",
         *     path="/api/admin/referentiels/{id1}/grpecompetences/{id}",
         *     methods={"GET"},
         *     defaults={
         *          "__controller"="App\Controller\ReferentielController::getReferentielGroupeCompetence",
         *          "__api_resource_class"=Referentiel::class,
         *          "__api_collection_operation_name"="get_referentiels_grpCompetence"
         *     }
         * )
         */
        public function getReferentielGroupeCompetence(Request $request,EntityManagerInterface $entityManager)
        {
                $tab=[];
                $tableau = $entityManager->getRepository(Referentiel::class)->findAll();
    
                $competence= $tableau[0]->getGrpCompetences()[0]->getCompetences()[0]->getLibelle();
            $cou=$groupCompetence=$tableau[0]->getGrpCompetences()[0]->getLibelle();
    
            for ($i=0;$i<count($tableau);$i++){
    
                $tab[]=$tableau[$i]->getGrpCompetences()[0];
            }
            //return dd($tab);
            
            return $this->json($tab,201);
        }
}
