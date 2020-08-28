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


class BriefTwoController extends AbstractController
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

        if(count($briefRepository->myFindAll($limit,$offset))<$limit){

            $page = 1;
            $limit=2;
            $offset=($page-1)*$limit;
            $bief=$briefRepository->myFindAll($limit,$offset);
        }else{
            $page = (int) $request->query->get('page', 1);
            $limit=2;
            $offset=($page-1)*$limit;
            $bief=$briefRepository->myFindAll($limit,$offset);
        }



        foreach ($bief as $i => $iValue) {

            if(isset($bief[$i])){


                $tab[]= $serializer->normalize($iValue, 'json',['groups' => 'getAllBrief']);

            }
        }
        return new JsonResponse($tab, Response::HTTP_OK);
//dd(count($tab));
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

if(isset($promo) && isset($groupe)){
    foreach ($promo->getGroupes() as $pValue) {


        if($pValue->getId()===$groupe->getId()) {

            $ide=$pValue;
            $biefBygroupe = $pValue->getEtatBriefGroupes();

            $tableau[]=$serializer->normalize([$pValue->getEtatBriefGroupes()],
                'json',['groups' => ['getBriefByOneGroupe', 'getAllBrief']]);


            for ($b = 0, $bMax = count($pValue->getEtatBriefGroupes()); $b < $bMax; $b++) {
                foreach ($biefBygroupe[$b]->getBrief()->getEtatBriefGroupes() as $gvalue){

                    if($gvalue->getGroupe()->getStatut()=="encours"){
                        $tabl[]=$gvalue->getGroupe();
                        array_push($tableau, $serializer->normalize($gvalue->getGroupe(), 'json',['groups' => 'getBriefByOneGroupeApp']));
                    }
                    else{

                        array_push($tableau,"Le groupe : ". $gvalue->getGroupe()->getNom()." est fermé");
                    }
                    foreach ($biefBygroupe[$b]->getBrief()->getBriefMaPromos() as $promovalue){

                        array_push($tableau, $serializer->normalize($promovalue, 'json',['groups' => 'getBriefByOneGroupePr']));
                    }
                }


            }


        }


    }
}else{
    return new JsonResponse("Verifier les infos saisient !!!!", Response::HTTP_BAD_GATEWAY);
}


        return new JsonResponse($tableau, Response::HTTP_OK);


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
     * )
     */

    public function getBriefByPromo(PromotionRepository $promotionRepository,int $id,SerializerInterface $serializer){
        $promo=$promotionRepository->find($id);
        if(isset($promo)){


            foreach ($promo->getBriefMaPromos() as $pbvalue){
                $tableau[]=$serializer->normalize([$pbvalue->getBrief()],
                    'json',['groups' => ["getBriefByOneGroupe", 'getAllBrief']]);

              //  $tt[]=$pbvalue->getBrief()->getEtatBriefGroupes()[0]->getGroupe();

                foreach ($pbvalue->getBrief()->getEtatBriefGroupes() as $etatBrief){

                    $tt[]=$etatBrief->getGroupe()->getStatut();

                    if($etatBrief->getGroupe()->getStatut()==="encours"){

                        array_push($tableau, $serializer->normalize($etatBrief->getGroupe(), 'json',['groups' => 'getBriefByOneGroupeApp']));
                    }else{
                        array_push($tableau,"Le groupe : ". $etatBrief->getGroupe()->getNom()." est fermé");
                    }
                }
            }

        }else{
            return new JsonResponse("Ce promo n'existe pas !!", Response::HTTP_OK);
        }
        return new JsonResponse($tableau, Response::HTTP_OK);
       // dd($tt);
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
$pp[]=$bValue->getStatut();
            if($bValue->getStatut()==="brouillon"){

                $tableau[]=$serializer->normalize([$formateur,$bValue],
                    'json',['groups' => ['getBriefBrouillonFormateur','getAllBrief']]);

            }
        }

        return new JsonResponse($tableau, Response::HTTP_OK);
//dd($pp);
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
            $pp[]=$bValue->getStatut();
            if($bValue->getStatut()==="valider" || $bValue->getStatut()!=="assigner"){

                $tableau[]=$serializer->normalize([$formateur,$bValue],
                    'json',['groups' => ['getBriefBrouillonFormateur','getAllBrief']]);

            }
        }

        return new JsonResponse($tableau, Response::HTTP_OK);
//dd($pp);
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

foreach ($promo->getBriefMaPromos() as $bvalue){



        if($bvalue->getBrief()->getId()===$brief->getId()){

            $tableau[]=$serializer->normalize([$promo,$bvalue->getBrief()],
                'json',['groups' => ['getOnBriefOnePromo','getAllBrief']]);
            $tab[]=$bvalue->getBrief();

            foreach ($bvalue->getBrief()->getEtatBriefGroupes() as $etatGroupe){
$tag[]=$bvalue->getBrief()->getBriefMaPromos()[1]->getPromo()->getId();
                if($etatGroupe->getGroupe()->getStatut()=="encours"){

                    array_push($tableau, $serializer->normalize($etatGroupe->getGroupe(), 'json',['groups' => 'getBriefByOneGroupeApp']));
                }else{
                    array_push($tableau,"Le groupe : ". $etatGroupe->getGroupe()->getNom()." est fermé");
                }
                foreach ($bvalue->getBrief()->getBriefMaPromos() as $promovalue){

                    array_push($tableau, $serializer->normalize($promovalue, 'json',['groups' => 'getBriefByOneGroupePr']));
                }
            }

        }


}

        return new JsonResponse($tableau, Response::HTTP_OK);
        //dd($tag);
    }

    /**
     *
     * @Route(
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
    public function getBriefApprenantByPromo(PromotionRepository $promotionRepository, int $id, SerializerInterface $serializer)
    {
        $promo=$promotionRepository->find($id);

        if(isset($promo)){


            foreach ($promo->getBriefMaPromos() as $pbvalue){


               if($pbvalue->getPromo()->getId()===$promo->getId()){

                $tabf[]=$pbvalue->getBrief();

                if($pbvalue->getBrief()->getStatut() === "assigner"){

                    $tableau[]=$serializer->normalize([$pbvalue->getBrief()],
                        'json',['groups' => ['getBriefByOneGroupe','getAllBrief']]);


                            if(count($pbvalue->getBrief()->getEtatBriefGroupes())===0){

                               // dd("Ce brief n'est pas assigner a d'autre groupe");
                                return $this->json("Ce brief n'est pas assigner a d'autre groupe");
                                $tableau[] = $serializer->normalize("Le  ".$pbvalue->getBrief()->getTitre()." n'est pas assigne a d'autres groupe");
                            }else{

                   foreach ($pbvalue->getBrief()->getEtatBriefGroupes() as $groupeValue){

                               if($groupeValue->getGroupe()->getStatut()==="encours"){

                                   $tableau[] = $serializer->normalize($groupeValue->getGroupe(), 'json', ['groups' => 'getBriefByOneGroupeApp']);
                               }else{


                                   $tableau[] = $serializer->normalize("Le  ".$groupeValue->getGroupe()->getNom()." est ferme", 'json');
                               }
                   }
                            }


                }


               }

            }


        }

        return new JsonResponse($tableau, Response::HTTP_OK);

    }
}
