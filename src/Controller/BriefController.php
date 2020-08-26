<?php

namespace App\Controller;


use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupesRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BriefController extends AbstractController
{

    /**
     * @Route(
     *     name="get_brief_all",
     *     path="/api/formateurs/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getAllBriefs",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_bief"
     *     }
     * )
     */
    public function getAllBriefs(BriefRepository $briefRepository,Request $request,SerializerInterface $serializer)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=2;
        $offset=($page-1)*$limit;
            $bief=$briefRepository->myFindAll($limit,$offset);
        foreach ($bief as $i => $iValue) {

            if(isset($bief[$i])){


                $tab[]= $serializer->normalize($iValue, 'json',['groups' => 'getAllBrief']);

            }
        }
        return new JsonResponse($tab, Response::HTTP_OK);

    }

    /**
     * @Route(
     *     name="get_brief_by_one_groupe",
     *     path="/api/formateurs/promo/{id}/groupe/{id1}/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getBriefByOneGroupe",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_brief_one_groupe"
     *     }
     * )
     */
    public function getBriefByOneGroupe(PromotionRepository $promotionRepository,GroupesRepository $groupesRepository,int $id,int $id1,SerializerInterface $serializer){

            $promo=$promotionRepository->find($id);
            $groupe=$groupesRepository->find($id1);
            $tableau=[];


        foreach ($promo->getGroupes() as $pValue) {


                    if($pValue->getId()===$groupe->getId()) {

                        $biefBygroupe = $pValue->getBriefs();
                        for ($b = 0, $bMax = count($pValue->getBriefs()); $b < $bMax; $b++) {

                            $tableau[]=$serializer->normalize([$pValue],
                                'json',['groups' => ['getBriefByOneGroupe', 'getAllBrief']]);

                            foreach ($biefBygroupe[$b]->getGroupe() as $gValue) {

                                if($gValue->getStatut()==="encours"){


                                    array_push($tableau, $serializer->normalize($gValue, 'json',['groups' => 'getBriefByOneGroupeApp']));


                                }
                                else{
                                    array_push($tableau,"Le groupe : ". $gValue->getNom()." est fermé");
                                }
                            }


                        }


                    }


        }

        return new JsonResponse($tableau, Response::HTTP_OK);
        //dd($ta);
    }
    /**
     * @Route(
     *     name="get_brief_by_one_promo",
     *     path="/api/formateurs/promos/{id}/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getBriefByPromo",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_brief_one_promo"
     *     }
     * ),
     *       @Route(
     *     name="get_brief_by_one_promo_for_apprenant",
     *     path="/api/apprenants/promos/{id}/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getBriefByPromo",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_brief_one_promo_for_apprenant"
     *     }
     * )
     */

    public function getBriefByPromo(PromotionRepository $promotionRepository,int $id,SerializerInterface $serializer){
        $promo=$promotionRepository->find($id);
        if(isset($promo)){
            $briefPromo=$promo->getBriefs();


                $tableau[]=$serializer->normalize([$promo],
                    'json',['groups' => ["getBriefByPromo", 'getAllBrief']]);

            for($b=0, $bMax = count($promo->getBriefs()); $b< $bMax; $b++){

                foreach ($briefPromo[$b]->getGroupe() as $gValue) {

                    if($gValue->getStatut()=="encours"){

                        array_push($tableau, $serializer->normalize($gValue, 'json',['groups' => 'getBriefByOneGroupeApp']));

                    }
                    else{
                        array_push($tableau,"Le groupe : ". $gValue->getNom()." est fermé");
                    }

                }
            }

        }
        return new JsonResponse($tableau, Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="get_brief_by_one_formateur",
     *     path="/api/formateurs/{id}/briefs/broullons",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getBriefBrouillonFormateur",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_brief_one_formateur"
     *     }
     * )
     */
    public function getBriefBrouillonFormateur(FormateurRepository $formateurRepository,int $id,SerializerInterface $serializer){
        $formateur=$formateurRepository->find($id);

        foreach ($formateur->getBriefs() as $bValue) {

            if($bValue->getStatutBrief()=="brouillon"){
                $tableau[]=$serializer->normalize([$formateur,$bValue],
                    'json',['groups' => ['getAllBrief',"getBriefBrouillonFormateur"]]);

            }else{

                array_push($tableau,"Vous n'avez pas de brief(s) brouillon(s)");
            }

        }

        return new JsonResponse($tableau, Response::HTTP_OK);

    }

    /**
     * @Route(
     *     name="get_on_brief_on_promo",
     *     path="/api/formateurs/promo/{id}/briefs/{id1}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getOnBriefOnePromo",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_one_brief_one_promo"
     *     }
     * )
     */
    public function getOnBriefOnePromo(PromotionRepository $promotionRepository,BriefRepository $briefRepository,int $id,int $id1,SerializerInterface $serializer){
        $promo=$promotionRepository->find($id);
        $brief=$briefRepository->find($id1);



            for($f=0, $fMax = count($promo->getBriefs()); $f< $fMax; $f++){

                if($promo->getBriefs()[$f]->getId()==$brief->getId()){

                    $briefPromo=$promo->getBriefs();
                    $tableau[]=$serializer->normalize([$promo,$briefPromo[$f]],
                        'json',['groups' => ['getAllBrief',"getOnBriefOnePromo"]]);
                }
                else{


                    array_push($tableau,"Ce brief n'est pas dans ce promo");
                }

                foreach ($briefPromo[$f]->getGroupe() as $gValue) {

                    if($gValue->getStatut()=="encours"){



                        array_push($tableau, $serializer->normalize($gValue, 'json',['groups' => 'getBriefByOneGroupeApp']));

                    }
                    else{

                        array_push($tableau,"Le groupe : ". $gValue->getNom()." est fermé");
                    }

                }
            }

        return new JsonResponse($tableau, Response::HTTP_OK);
    }

    /**
     * @Route(
     *     name="get_brief_valide_assigner_on_formateur",
     *     path="/api/formateurs/{id}/briefs/valide",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::getBriefFormateurValiderOuAssigner",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get_brief_valide_assigner_one_formateur"
     *     }
     * )
     */
    public function getBriefFormateurValiderOuAssigner(FormateurRepository $formateurRepository,int $id,SerializerInterface $serializer){

        $formateur=$formateurRepository->find($id);

        foreach ($formateur->getBriefs() as $bValue) {

            if($bValue->getStatutBrief()=="valider" || $bValue->getStatutBrief()=="assigner"){

                $tableau[]=$serializer->normalize([$formateur,$bValue],
                    'json',['groups' => ['getAllBrief',"getBriefBrouillonFormateur"]]);
            }else{

                array_push($tableau,"Vous n'avez pas de brief(s) Valider ou Assigner");
            }

        }

        return new JsonResponse($tableau, Response::HTTP_OK);

    }
    }

