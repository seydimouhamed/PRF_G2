<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Entity\GroupeCompetence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
         *     path="/api/admin/referentiels/{id1}/grpecompetences/{id2}",
         *     methods={"GET"},
         *     defaults={
         *          "__controller"="App\Controller\ReferentielController::getReferentielIdGCId",
         *          "__api_resource_class"=Referentiel::class,
         *          "__api_collection_operation_name"="get_ref_grpCompid"
         *     }
         * )
         */
        public function getReferentielIdGCId(EntityManagerInterface $entityManager,$id1,$id2)
        {
                $tab=[];
                $tableau = $entityManager->getRepository(Referentiel::class)->find($id1);
            if($tableau)
            {
                $grpComp=$tableau-> getGrpCompetences();
                foreach($grpComp as $grp)
                {
                    if($grp->getID()==$id2)
                    {
                        $competences['idReferentiels']=$tableau->getID();
                       $competences['idGroupeCompetence'] =$grp->getID();
                       $competences['competences']=$grp->getCompetences();

                       return $this->json($competences,201);
                    }
                }

                return $this->json("ce groupe de compétence n'existe pas dans ce référentiels!",201);  
            }

            return $this->json("ce référentiel n'existe pas!",401);  
            
        }


        /**
         * @Route(
         *     name="put_referentiel_gc_id",
         *     path="/api/admin/referentiels/{id}",
         *     methods={"PUT"},
         *     defaults={
         *          "__controller"="App\Controller\ReferentielController::putReferentielGCId",
         *          "__api_resource_class"=Referentiel::class,
         *          "__api_collection_operation_name"="put_ref_grpCompid"
         *     }
         * )
         */
        public function putReferentielGCId(EntityManagerInterface $entityManager,$id,Request $request)
        {
                $data=json_decode($request->getContent());

                $referentiel= $entityManager->getRepository(Referentiel::class)->find($id);
                
                $tab_id_gc=[];
                foreach($referentiel->getGrpCompetences() as $gc)
                {
                    $tab_id_gc[]=$gc->getID();
                }

               //ajouter les groupe compétence au référentiel
               foreach($data["add"] as $id_gc)
               {
                   if(!in_array($id_gc,$tab_id_gc))
                   {
                        $groupComp= $entityManager->getRepository(GroupeCompetence::class)->find($id_gc);
                        if($groupComp)
                        {
                            $referentiel->addGrpCompetence($groupComp);
                        }
                        $entityManager->persist($referentiel);
                   }
               }
               //sles groupe compétence au référentiel
               foreach()

                

             return $this->json($tab_id_gc,401);  
            
        }
}
