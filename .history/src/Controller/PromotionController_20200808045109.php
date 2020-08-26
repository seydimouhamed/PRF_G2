<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Groupes;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
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

class PromotionController extends AbstractController
{
    private $serializer;
    private $validator;
    private $em;
    private $repo;

    public function __construct(
        PromotionRepository $repo,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->repo=$repo;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
    }
    /**
     * @Route(
     *     name="add_promo",
     *     path="/api/admin/promos",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::add",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="addPromo"
     *     }
     * )
     */
    public function add(Request $request)
    {
        //recupéré tout les données de la requete
        $promo=json_decode($request->getContent(),true);
         
        //recupération  recupération imga promo!
        //@$avatar = $request->files->get("avatar");
        
        $promo = $this->serializer->denormalize($promo,"App\Entity\Promotion",true);
        // if($avatar)
        // {
        //      //$avatarBlob = fopen($avatar->getRealPath(),"rb");
        //     // $promo->setAvatar($avatarBlob);
        // }
        if(!$promo->getFabrique())
        {
            $promo->setFabrique("Sonatel académie");
        }

        $errors = $this->validator->validate($promo);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
      //$promo->setArchivage(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($promo);
       $em->flush();
       //creation dun groupe pour la promo
       
       $group= new Groupes();
      // $date = date('Y-m-d');
       $group->setNom('Groupe Générale')
             ->setDateCreation(new \DateTime())
             ->setStatut('ouvert')
             ->setType('groupe principale')
             ->setPromotion($promo);
             $em->persist($group);
            $em->flush();
        
        return $this->json($promo,201);
     }


    /**
     * @Route(
     *     name="get_promotion_principale",
     *     path="/api/admin/promo/principale",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoPrincipale",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_promo_princ"
     *     }
     * )
     */
    public function getPromoPrincipale()
    {
        $promo_princ=$this->getGroupesPrincipale();
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($promo_princ,200);
    }
    /**
     * @Route(
     *     name="get_promotion_apprenant_attente",
     *     path="/api/admin/promo/apprenant/attente",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoApprenantAttente",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_apprenant_attente"
     *     }
     * )
     */
    public function getPromoApprenantAttente()
    {
        $p_princs = $this->getGroupesPrincipale();
        $apprenant_attente=[];
        foreach($p_princs as $pp)
        {
            foreach($pp as $groupe)
            {
                foreach($groupe->getApprenants() as $apprenant)
                {

                    if($apprenant->getStatut()==="attente")
                    {
                        $apprenant_attente[]=$apprenant;
                    }
                }
            }
        }

        return $this->json($apprenant_attente,200);
    }

    /**
     * @Route(
     *     name="get_promotion_id_apprenant_attente",
     *     path="/api/admin/promo/{id}/apprenant/attente",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::getPromoApprenantAttente",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_apprenant_id_attente"
     *     }
     * )
     */
    public function getPromoIdApprenantAttente($id)
    {
        $p_princs = $this->getGroupesPrincipale();
        $apprenant_attente=[];
        foreach($p_princs as $pp)
        {
            foreach($pp as $groupe)
            {
                foreach($groupe->getApprenants() as $apprenant)
                {

                    if($apprenant->getStatut()==="attente")
                    {
                        $apprenant_attente[]=$apprenant;
                    }
                }
            }
        }

        return $this->json($apprenant_attente,200);
    }



    /**
     * @Route(
     *     name="promo_get_principal",
     *     path="/api/admin/promo/{id}/principal",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoidPrincipal",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_PromoidPrincipal"
     *     }
     * )
     */
    public function getPromoidPrincipal($id)
    {
       $p_princs = $this->getGroupesPrincipale($id);

        return $this->json($p_princs ,200);
    }


    /**
     * @Route(
     *     name="promo_get_referentiel",
     *     path="/api/admin/promo/{id}/referentiel",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoidreferentiel",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_Promoidreferentiel"
     *     }
     * )
     */
    public function getPromoidreferentiel($id)
    {
        //getreferentielpromo($id);
      // $p_princs = $this->getGroupesPrincipale($id);

        return $this->json($this->getreferentielpromo($id) ,200);
    }


    private function getGroupesPrincipale($id=null)
    {
        $promos=null;
          $promos= $this->repo->findAll();
        $promo_princ=[];
        foreach($promos as $promo)
        {
            foreach($promo->getGroupes() as $promo_det)
            {
                if($promo_det->getType()==="groupe principale")
                {
                    if($promo->getID()==$id)
                    {
                            return $promo->getGroupes();
                    }
                    $promo_princ[]=$promo->getGroupes();
                }
            }
        }

        if($id)
        {
            return null;
        }else
        {
          return $promo_princ;
        }
    
    }

    private function getreferentielpromo($id=null)
    {
          $promos= $this->repo->find($id);
        $promo_ref=$promos->getReferentiel();

            return $promo_ref;

    }
}

