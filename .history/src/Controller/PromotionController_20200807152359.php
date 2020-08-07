<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Groupes;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
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

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
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
        @$photo = $request->files->get("photo");
        
        $promo = $this->serializer->denormalize($promo,"App\Entity\Promotion",true);
        if($photo)
        {
             $photoBlob = fopen($photo->getRealPath(),"rb");
             $promo->setPhoto($photoBlob);
        }
        if(!$promo->getFabrique())
        {
            $promo->setFabrique("Sonatel académie");
        }

        $errors = $this->validator->validate($promo);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
      //$user->setArchivage(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($promo);
       $em->flush();
       //creation dun groupe pour la promo
       
       $group= new Groupes();
       $date = date('Y-m-d');
       $group->setNom('Groupe Générale')
             ->setDateCreation($date)
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
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="get_promo_princ"
     *     }
     * )
     */
    public function getPromoPrincipale(PromotionRepository $repo)
    {
        $promo= $repo->findByArchivage(0);
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($promo,200);
    }
    /**
     * @Route(
     *     name="get_promotion_apprenant_attente",
     *     path="/api/admin/promo/apprenant/attente",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoApprenantAttente",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="get_apprenant_attente"
     *     }
     * )
     */
    public function getPromoApprenantAttente(PromotionRepository $repo)
    {
        $promo= $repo->findByArchivage(0);
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($promo,200);
    }
}
