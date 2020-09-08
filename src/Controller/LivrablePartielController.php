<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use App\Entity\Commentaire;
use App\Entity\Referentiel;
use App\Entity\BriefMaPromo;
use App\Entity\FilDiscution;
use App\Entity\BriefApprenant;
use Doctrine\ORM\EntityManager;
use App\Entity\LivrablePartiels;
use App\Entity\CompetencesValide;
use App\Repository\GroupesRepository;
use App\Entity\AprenantLivrablePartiel;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LivrablePartielController extends AbstractController
{


    private $serializer;
    private $em;
    private $repo;
    private $repoGroupe;
    public function __construct(
        PromotionRepository $repo,
        GroupesRepository $repoGroupe,
        SerializerInterface $serializer,
        EntityManagerInterface $em
)
    {
        $this->repo=$repo;
        $this->serializer=$serializer;
        $this->repoGroupe=$repoGroupe;
        $this->em=$em;
    }

    /**
     * @Route(
     *     name="get_apprenants_comptence",
     *     path="/api/formateurs/promo/{idPromo}/referentiel/{idRef}/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getApprenantCompetence",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_apprenant_compe"
     *     }
     * )
     */
    public function getApprenantCompetence($idPromo,$idRef)
    {
        $competences=$this->em->getRepository(Apprenant::class)->getCollectionCompetenceApprenant($idPromo);
        
        return $this->json($competences,200, ['groups'=>'comp_vid']);
    }


    /**
     * @Route(
     *     name="get_apprenants_id_comptence",
     *     path="/api/apprenants/{idApprenant}/promo/{idPromo}/referentiel/{idReferentiel}/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getApprenantIdCompetence",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_apprenantid_compe"
     *     }
     * )
     */
    public function getApprenantIdCompetence($idApprenant,$idPromo,$idReferentiel)
    {
        $apprenant = $this->em->getRepository(Apprenant::class)->find($idApprenant);
        $competenceApprenant = $this->em->getRepository(CompetencesValide::class)->findBy(["apprenant"=>$apprenant]);
    
       $tab=['id'=>$apprenant->getId(),
            "fullName"=>$apprenant->getFisrtName().' '.$apprenant->getLastName(),
       'competences'=>$competenceApprenant];
        return $this->json( $tab,200);
    }
    /**
     * @Route(
     *     name="get_apprenant_stats",
     *     path="/api/apprenants/{idApprenant}/promo/{idPromo}/referentiel/{idRef}/statistiques/briefs",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getApprenantStatistique",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_apprenant_stat"
     *     }
     * )
     */
    public function getApprenantStatistique($idApprenant,$idPromo,$idRef)
    {
        $stats=$this->getStatApprenant($idApprenant);
       
        return $this->json($stats,200);
    }



    /**
     * @Route(
     *     name="get_comp_stats",
     *     path="/api/formateurs/promo/{idPromo}/referentiel/{idRef}/statistiques/competences",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getCompetenceStatistique",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_compe_stat"
     *     }
     * )
     */
    public function getCompetenceStatistique($idPromo,$idRef)
    {
        // //-----------------------------------------------------------------//
        // Récupération de l'ensemble des compétence validé des apprenant  //
        //-----------------------------------------------------------------//
        $competenceApprenant = $this->em->getRepository(Apprenant::class)->getCompetencesPromo($idPromo);

        

        //----------------------------------------------------------------------//
        // Récupération de l'ensemble des compétence des référentiel de la promotion  //
        //-----------------------------------------------------------------------//

        $allCompetences=$this->em->getRepository(Referentiel::class)->getCompentencePromo($idPromo);
        
     
      //------------------------------------------------------------------------------------------//
      //comparaison et comptage des compétences validés par rapport aux compétence du référentiel //
      //------------------------------------------------------------------------------------------//
      $return_tab=[]; 
      foreach($allCompetences as $comp)
        {
            $tab=[];
            $tab["competence"]=$comp;
            $nivo1=0;
            $nivo2=0;
            $nivo3=0;
            foreach($competenceApprenant as $compapp)
            {
                if($comp==$compapp->getCompetence())
                {
                        if($compapp->getNiveau1()=="oui")
                        {
                            $nivo1++;
                        }
                        if($compapp->getNiveau2()=="oui")
                        {
                            $nivo2++;
                        }
                        if($compapp->getNiveau3()=="oui")
                        {
                            $nivo3++;
                        }

                }
            }
             $tab['niveaux']=['niveau1'=>$nivo1,'niveau2'=>$nivo2,'niveau3'=>$nivo3];
             $return_tab[]=$tab;
        }

        return $this->json($return_tab,200);
    }


    /**
     * @Route(
     *     name="get_comm_fil_app",
     *     path="/api/apprenants/livrablepartiels/{idLiv}/commentaires",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getCommentLP",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_com_fil_app"
     *     }
     * ),
     * @Route(
     *     name="get_comm_fil_form",
     *     path="/api/formateurs/livrablepartiels/{idLiv}/commentaires",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getCommentLP",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_com_fil_form"
     *     }
     * )
     */
    public function getCommentLP($idLiv)
    {
        $livPartiel=$this->em->getRepository(AprenantLivrablePartiel::class)->find($idLiv);
         $comment = $livPartiel-> getFilDiscution()->getCommentaires();
       
         return $this->json($comment,200);
    }

    /**
     * @Route(
     *     name="post_form_liv",
     *     path="/api/formateurs/livrablepartiels/{idLiv}/commentaires",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addFilDiscComment",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="postform_liv"
     *     }
     * ),
     * @Route(
     *     name="post_appre_liv",
     *     path="/api/apprenants/livrablepartiels/{idLiv}/commentaires",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addFilDiscComment",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="postappr_liv"
     *     }
     * )
     */
    public function addFilDiscComment(Request $request,$idLiv)
    {
           $data=json_decode($request->getContent(),true);
           $app_liv=$this->em->getRepository(AprenantLivrablePartiel::class)->find($idLiv);
         
           if($app_liv)
           {
               $filDiscution=$app_liv->getFilDiscution();
               
               if(!$filDiscution){
                   $filDiscution=new FilDiscution();
                   $app_liv->setFilDiscution($filDiscution);
               }
               $commentaire=new Commentaire();
               $commentaire->setDescription($data['description'])
                           ->setCreatAt(new \DateTime($time='now'));
                       if(isset($data['idformateur']))
                       {
                            $formateur=$this->em->getRepository(Formateur::class)->find($data['idformateur']);
                            if($formateur)
                            {
                                $commentaire->setFormateur($formateur);
                            }else
                            {
                                return $this->json("le formateur n\'existe pas!",400);
                            }
         
                       }

                    $this->em->persist($commentaire);

                $filDiscution->addCommentaire($commentaire);
                $this->em->persist($filDiscution);
                 $this->em->flush();

                 return $this->json("succes ",200);
           }



           return $this->json(['ce livrable partiel n\'existe pas'],400);
    }


    /**
     * @Route(
     *     name="put_liv_part",
     *     path="/api/formateurs/promo/{idPromo}/brief/{idBrief}/livrablepartiels",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::addLivPartiel",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_compe_stat"
     *     }
     * )
     */
    public function addLivPartiel(Request $request,$idPromo,$idBrief)
    {
        $data=json_decode($request->getContent(),true);
       // dd($data);
        $apprenants=$data['apprenants'];
        $briefPromo=$this->em->getRepository(BriefMaPromo::class)->findBy(["brief"=>$idBrief,"promo"=>$idPromo])[0];
          if(!$briefPromo)
          {
            $promo=$this->em->getRepository(Promotion::class)->find($idPromo);
            $brief=$this->em->getRepository(Brief::class)->find($idBrief);
            if($promo && $brief)
            {
                $briefPromo=new BriefMaPromo();
                $briefPromo->setPromo($promo);
                $briefPromo->setBrief($brief);
                $this->em->persist($briefPromo);
            }
            else
            {
                return $this->json("Vérifier le promo ou le brief!" , 400);
            }
          }  

           $livPartiel=$this->serializer->denormalize($data,"App\Entity\LivrablePartiels",true);

          //  dd($livPartiel);
        
          // $livPartiel=new LivrablePartiels();
            $livPartiel->setDateCreation(new \DateTime());
            $livPartiel->setBriefMaPromo($briefPromo);
            $this->em->persist($livPartiel);
            // assignation des apprenants au livrable partiel 
            //$test=[];
            foreach($apprenants as $app)
            {
                $app_liv_partiel=new AprenantLivrablePartiel();
                $app_obj=$this->serializer->denormalize($app,"App\Entity\Apprenant",true);        
                $app_liv_partiel->setApprenant($app_obj)
                                ->setEtat('assigne')
                                ->setDelai(new \DateTime($data['delai']))
                                 ->setLivrablePartiel($livPartiel);
                //$test[]=$app_liv_partiel;
                $this->em->persist($app_liv_partiel);
            }

        $this->em->flush();

        return $this->json("success",200);
    }


    /**
     * @Route(
     *     name="put_liv_statut",
     *     path="/api/apprenants/{idAppr}/livrablepartiels/{idLiv}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::putStatutLivPartiel",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_compe_stat"
     *     }
     * )
     */
    public function putStatutLivPartiel(Request $request,$idAppr,$idLiv)
    {
          //  $promos= $this->getGroupesPrincipale($idPromo);
          $data=json_decode($request->getContent(),true);
          $app_liv=$this->em->getRepository(AprenantLivrablePartiel::class)->findBy(["livrablePartiel"=>$idLiv]);
          $data=json_decode($request->getContent(),true);
          if($app_liv)
          {
                $app_liv[0]->setEtat($data['statut']);
                $this->em->persist($app_liv[0]);
                $this->em->flush();
                return $this->json("success",200);

          }
           

        return $this->json("ce livrable partiel n\'existe pas",200);
    }

    private function getGroupesPrincipale($id=null)
    {
        $promos=null;
          $promos= $this->repo->findAll();
        $promo_princ=[];
        
        foreach($promos as $promo)
        {
            
            $group_ref_detail['referentiel']=$promo->getReferentiel();

            foreach($promo->getGroupes() as $promo_det)
            {
                if($promo_det->getType()==="groupe principale")
                {
                    if($promo->getID()==$id)
                    {
                        $group_ref_detail['groupes']=$promo_det;
                            return $group_ref_detail;
                    }
                    $group_ref_detail['groupes']=$promo_det;
                }
            }

            $promo_princ[]=$group_ref_detail;
        }

        if($id)
        {
            return null;
        }else
        {
          return $promo_princ;
        }
    
    }

    public function getStatApprenant($idApprenant)
    { 
        $apprenant = $this->em->getRepository(Apprenant::class)->find($idApprenant);
        $appBriefs=$this->em->getRepository(BriefApprenant::class)->findBy(["apprenant"=>$apprenant]);
        $stats=[];
        $livRendu=0;
        $livValide=0;
        $livAssigne=0;
        foreach($appBriefs as $stb)
        {
            $statut=$stb->getStatut();
            if($statut=="rendu")
            {
                $livRendu++;
            }
            if($statut=="assigne")
            {
                $livAssigne++;
            }
            if($statut=="valide")
            {
                $livValide++;
            }
        }
        $stats=["id"=>$idApprenant,"assigne"=>$livAssigne,"valide"=>$livValide,"rendu"=>$livRendu];


        return $stats;
    }


}

