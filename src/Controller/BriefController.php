<?php

namespace App\Controller;


use App\Repository\BriefRepository;
use App\Repository\FormateurRepository;
use App\Repository\GroupesRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function getAllBriefs(BriefRepository $briefRepository,Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=2;
        $offset=($page-1)*$limit;
            $bief=$briefRepository->myFindAll($limit,$offset);
        foreach ($bief as $i => $iValue) {

            if(isset($bief[$i])){


                $tab[]=["brief"=>["id"=> $iValue->getId(),"langue"=> $iValue->getLangue(),"Titre:"=> $iValue->getTitre(),"Contexte"=> $iValue->getContexte(),
                    "Formateur"=> ["Username"=>$iValue->getFormateur()->getUsername(),"Firstname"=>$iValue->getFormateur()->getFisrtName(),"Email"=>$iValue->getFormateur()->getEmail(),
                        "Photo"=>$iValue->getFormateur()->getPhoto()],
                    "DatePoste"=> $iValue->getDatePoste(),"DateLimite"=> $iValue->getDateLimite(),
                    "LivrableAttendu"=> $iValue->getLivrableAttendus(),"ModalitePedagogique"=> $iValue->getModalitePedagogique(),
                    "CricterePerformance"=> $iValue->getCricterePerformance(),"ModaliteEvaluation"=> $iValue->getModaliteDevaluation(),
                    "Image"=> $iValue->getImageExemplaire(),"Niveau"=> $iValue->getNiveau(),"Tag"=> $iValue->getTag(),"Ressources"=> $iValue->getRessources()]];

            }
        }
        return $this->json($tab, 200);

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
    public function getBriefByOneGroupe(PromotionRepository $promotionRepository,GroupesRepository $groupesRepository,int $id,int $id1){

            $promo=$promotionRepository->find($id);
            $groupe=$groupesRepository->find($id1);


        foreach ($promo->getGroupes() as $pValue) {


                    if($pValue->getId()===$groupe->getId()) {

                        $biefBygroupe = $pValue->getBriefs();
                        for ($b = 0, $bMax = count($pValue->getBriefs()); $b < $bMax; $b++) {

                            $tab[]=["Groupes"=>["Id"=> $pValue->getId(),"Nom Grroupe"=> $pValue->getNom()],"Brief"=>["id"=>$biefBygroupe[$b]->getId(), "langue"=>$biefBygroupe[$b]->getLangue(),
                                "Titre:"=>$biefBygroupe[$b]->getTitre(),"Contexte"=>$biefBygroupe[$b]->getContexte(),
                                "Formateur"=>["Id"=>$biefBygroupe[$b]->getFormateur()->getId(),"Username"=>$biefBygroupe[$b]->getFormateur()->getUsername(),"FisrtName"=>$biefBygroupe[$b]->getFormateur()->getFisrtName()],
                                "Email"=>$biefBygroupe[$b]->getFormateur()->getEmail(),
                                "DatePoste"=>$biefBygroupe[$b]->getDatePoste(),"DateLimite"=>$biefBygroupe[$b]->getDateLimite(),
                                "ModalitePedagogique"=>$biefBygroupe[$b]->getModalitePedagogique(), "CricterePerformance"=>$biefBygroupe[$b]->getCricterePerformance(),"
                                 ModaliteEvaluation"=>$biefBygroupe[$b]->getModaliteDevaluation(),"Referentiel"=>$biefBygroupe[$b]->getReferentiel(),
                                "Niveau"=>$biefBygroupe[$b]->getNiveau(),"Livrable Attendus"=>$biefBygroupe[$b]->getLivrableAttendus(),
                                "Tag"=>$biefBygroupe[$b]->getTag(),"Ressources"=>$biefBygroupe[$b]->getRessources(),
                                "Livrable partiel"=>$biefBygroupe[$b]->getLivrablePartiels(),"ImageExemplaire"=>$biefBygroupe[$b]->getImageExemplaire()]];

                            foreach ($biefBygroupe[$b]->getGroupe() as $gValue) {

                                if($gValue->getStatut()=="encours"){

                                    $tab[]=["Groupes"=>["Nom Groupe"=> $gValue->getNom(),
                                        "Apprenants"=> $gValue->getApprenants()]];

                                    foreach ($biefBygroupe[$b]->getPromo() as $prValue) {
                                        $tab[] = ["promo" => ["Id" => $prValue->getId(), "Titre" => $prValue->getTitre(),
                                            "Description" => $prValue->getDescription(),
                                            "Fabrique" => $prValue->getFabrique()]];
                                    }
                                }
                                else{
                                    $tab[]="Le groupe : ". $gValue->getNom()." est fermé";
                                }
                            }


                        }


                    }


        }

        return $this->json($tab  , 200);
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
     * )
     */
    public function getBriefByPromo(PromotionRepository $promotionRepository,int $id){
        $promo=$promotionRepository->find($id);
        if(isset($promo)){
            $briefPromo=$promo->getBriefs();
            for($b=0, $bMax = count($promo->getBriefs()); $b< $bMax; $b++){

                $tab[]=["Promo"=>["Id"=>$promo->getId(),"Titre"=>$promo->getTitre(),"Description"=>$promo->getDescription(),"Fabrique"=>$promo->getFabrique()],"Brief"=>["id"=>$briefPromo[$b]->getId(),
                    "langue"=>$briefPromo[$b]->getLangue(),"Titre:"=>$briefPromo[$b]->getTitre(),"Contexte"=>$briefPromo[$b]->getContexte(),
                    "Formateur"=>["Id"=>$briefPromo[$b]->getFormateur()->getId(),"Username"=>$briefPromo[$b]->getFormateur()->getUsername(),"FirstName"=>$briefPromo[$b]->getFormateur()->getFisrtName(),
                        "Email"=>$briefPromo[$b]->getFormateur()->getEmail()], "DatePoste"=>$briefPromo[$b]->getDatePoste(),"DateLimite"=>$briefPromo[$b]->getDateLimite(),
                    "ModalitePedagogique"=>$briefPromo[$b]->getModalitePedagogique(), "CricterePerformance"=>$briefPromo[$b]->getCricterePerformance(),"
                                     ModaliteEvaluation"=>$briefPromo[$b]->getModaliteDevaluation()],"Referentiel"=>$briefPromo[$b]->getReferentiel(),
                    "Niveau"=>$briefPromo[$b]->getNiveau(),"Livrable Attendus"=>$briefPromo[$b]->getLivrableAttendus(),
                    "Tag"=>$briefPromo[$b]->getTag(),"Ressources"=>$briefPromo[$b]->getRessources(),"Livrable partiel"=>$briefPromo[$b]->getLivrablePartiels(),"ImageExemplaire"=>$briefPromo[$b]->getImageExemplaire()];

                foreach ($briefPromo[$b]->getGroupe() as $gValue) {

                    if($gValue->getStatut()=="encours"){

                        $tab[]=["Groupes"=>["Nom Groupe"=> $gValue->getNom(),"Apprenants"=> $gValue->getApprenants()]];

                        foreach ($briefPromo[$b]->getPromo() as $prValue) {

                            $tab[] = ["promo" => ["Id" => $prValue->getId(), "Titre" => $prValue->getTitre(), "Description" => $prValue->getDescription(),
                                "Fabrique" => $prValue->getFabrique()]];
                        }
                    }
                    else{
                        $tab[]="Le groupe : ". $gValue->getNom()." est fermé";
                    }

                }
            }

        }
        return $this->json($tab  , 200);
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
    public function getBriefBrouillonFormateur(FormateurRepository $formateurRepository,int $id){
        $formateur=$formateurRepository->find($id);

        foreach ($formateur->getBriefs() as $bValue) {

            if($bValue->getStatutBrief()=="brouillon"){
                $tab[]=["Formateur"=>["Id"=>$formateur->getId(),"Username"=>$formateur->getUsername(),"Firstname"=>$formateur->getFisrtName(),
                    "Email"=>$formateur->getEmail(),"photo"=>$formateur->getPhoto()],
                    "Brief"=>["Id"=> $bValue->getId(),"Titre"=> $bValue->getTitre(),
                        "Contexte"=> $bValue->getContexte(),"Niveau"=> $bValue->getNiveau(),
                        "Livrable Attendu"=> $bValue->getLivrableAttendus(),"Tag"=> $bValue->getTag(),
                        "Ressources"=> $bValue->getRessources()]];
            }else{
                $tab[]="Vous n'avez pas de brief(s) brouillon(s)";
            }

        }

        return $this->json($tab,200);

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
    public function getOnBriefOnePromo(PromotionRepository $promotionRepository,BriefRepository $briefRepository,int $id,int $id1){
        $promo=$promotionRepository->find($id);
        $brief=$briefRepository->find($id1);



            for($f=0, $fMax = count($promo->getBriefs()); $f< $fMax; $f++){

                if($promo->getBriefs()[$f]->getId()==$brief->getId()){

                    $briefPromo=$promo->getBriefs();
                    $tab[]=["Promo"=>["Id"=>$promo->getId(),"Titre"=>$promo->getTitre(),"Description"=>$promo->getDescription()],
                        "Brief"=>["id"=>$briefPromo[$f]->getId(), "langue"=>$briefPromo[$f]->getLangue(),"Titre:"=>$briefPromo[$f]->getTitre(),"Contexte"=>$briefPromo[$f]->getContexte(),
                            "Formateur"=>["Id"=>$briefPromo[$f]->getFormateur()->getId(),"Username"=>$briefPromo[$f]->getFormateur()->getUsername(),"Firstname"=>$briefPromo[$f]->getFormateur()->getFisrtName(),
                                "Email"=>$briefPromo[$f]->getFormateur()->getEmail(),"photo"=>$briefPromo[$f]->getFormateur()->getPhoto()],
                            "DatePoste"=>$briefPromo[$f]->getDatePoste(),"DateLimite"=>$briefPromo[$f]->getDateLimite(),
                            "ModalitePedagogique"=>$briefPromo[$f]->getModalitePedagogique(), "CricterePerformance"=>$briefPromo[$f]->getCricterePerformance(),"
                                     ModaliteEvaluation"=>$briefPromo[$f]->getModaliteDevaluation()],"Referentiel"=>$briefPromo[$f]->getReferentiel(),
                        "Niveau"=>$briefPromo[$f]->getNiveau(),"Livrable Attendus"=>$briefPromo[$f]->getLivrableAttendus(),
                        "Tag"=>$briefPromo[$f]->getTag(),"Ressources"=>$briefPromo[$f]->getRessources(),"Livrable partiel"=>$briefPromo[$f]->getLivrablePartiels(),
                        "ImageExemplaire"=>$briefPromo[$f]->getImageExemplaire()];
                }
                else{

                    $tab[]="Ce brief n'est pas dans ce promo";
                }

                foreach ($briefPromo[$f]->getGroupe() as $gValue) {

                    if($gValue->getStatut()=="encours"){

                        $tab[]=["Groupes"=>["Nom Groupe"=> $gValue->getNom(),"Apprenants"=> $gValue->getApprenants()]];

                        foreach ($briefPromo[$f]->getPromo() as $prValue) {

                            $tab[] = ["promo" => ["Id" => $prValue->getId(), "Titre" => $prValue->getTitre(),
                                "Description" => $prValue->getDescription(),
                                "Fabrique" => $prValue->getFabrique()]];
                        }

                    }
                    else{
                        $tab[]="Le groupe : ". $gValue->getNom()." est fermé";
                    }

                }
            }

        return $this->json($tab, 200);
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
    public function getBriefFormateurValiderOuAssigner(FormateurRepository $formateurRepository,int $id){

        $formateur=$formateurRepository->find($id);

        foreach ($formateur->getBriefs() as $bValue) {

            if($bValue->getStatutBrief()=="valider" || $bValue->getStatutBrief()=="assigner"){

                $tab[]=["Formateur"=>["Id"=>$formateur->getId(),"Username"=>$formateur->getUsername(),"Firstname"=>$formateur->getFisrtName(),
                    "Email"=>$formateur->getEmail(),"photo"=>$formateur->getPhoto()],
                    "Brief"=>["Id"=> $bValue->getId(),"Titre"=> $bValue->getTitre(),
                        "Contexte"=> $bValue->getContexte(),"Niveau"=> $bValue->getNiveau(),
                        "Livrable Attendu"=> $bValue->getLivrableAttendus(),"Tag"=> $bValue->getTag(),
                        "Ressources"=> $bValue->getRessources()]];
            }else{
                $tab[]="Vous n'avez pas de brief(s) Valider ou Assigner";
            }

        }

        return $this->json($tab,200);

    }
    }

