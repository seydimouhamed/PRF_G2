<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilSortie;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilSortieController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;
    private $em;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->encoder=$encoder;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
    }


    /**
     * @Route(
     *     name="getProfilSortie",
     *     path="/api/admin/profilSorties",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="get_profil_sortie"
     *     }
     * )
     */
    public function getProfilSortie(ProfilSortieRepository $repo,Request $request)
    {
        $page = (int) $request->query->get('page', 1);
        $limit=3;
        $offset=($page-1)*$limit;

        $profils= $repo->findByArchivage(0,$limit,$offset);
        $tab_profil=[];
        foreach($profils as $prf)
        {
            $tab_profil[]=["id"=>$prf->getID(),"libelle"=>$prf->getLibele()];
        }

        return $this->json($tab_profil,200);
    }


    /**
     * @Route(
     *     name="archive_profil",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::archiveProfil",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="archive_profilSortie"
     *     }
     * )
     */
    public function archiveProfil(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id);

        if($profil!==null)
        {
            if( $profil->getArchivage() !==true)
            {          
                  $profil->setArchivage(1);
                $this->em->persist($profil);
                $this->em->flush();
                return $this->json(true,200);
            }
            return $this->json("ce profil a été déjà archiver!",400);
       }
       return $this->json("ce profil de sortie n'existe pas!",400);
    }


    /**
     * @Route(
     *     name="get_profilsortie_id",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getOneProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="get_one_profilSortie"
     *     }
     * )
     */
    public function getOneProfilSortie(ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id);
        if($profil && !$profil->getArchivage())
        {
            return $this->json($profil,200);     
        }
        return $this->json("ce profil de sortie n'existe pas! \n ou a été archivé!",Response::HTTP_BAD_REQUEST);  
    }


    /**
     * @Route(
     *     name="put_profil",
     *     path="/api/admin/profilSorties/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::putOneProfilSortie",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="put_one_profilSortie"
     *     }
     * )
     */
    public function putOneProfilSortie(Request $request, ProfilSortieRepository $repo,$id)
    {
        $profil=$repo->find($id);
        if($profil && !$profil->getArchivage())
        {
            $pSorties =json_decode( $request->getContent(),true);
            foreach($pSorties as $k => $ps)
            {
                $profil->{"set".ucfirst($k)}($ps);
            }
            $this->em->persist($profil);
            $this->em->flush();
            return $this->json($profil,200);     
        }
        return $this->json("ce profil de sortie n'existe pas! \n ou a été archivé!",Response::HTTP_BAD_REQUEST);  
    }



        /**
         * @Route(
         *     name="ajout_profilSortie",
         *     path="/api/admin/profilSorties",
         *     methods={"POST"},
         *     defaults={
         *          "__controller"="App\Controller\ProfilSortieController::AjoutProfilSortie",
         *          "__api_resource_class"=ProfilSortie::class,
         *          "__api_collection_operation_name"="AjouterProfilSortie"
         *     }
         * )
         */


        public function AddProfilSortie(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,ValidatorInterface $validator)
        {
            $post=json_decode($request->getContent(),true);
            $profil= $serializer->denormalize($post, "App\Entity\ProfilSortie", true);
            
            $errors = $this->validator->validate($profil);
            if (count($errors)){
                $errors = $this->serializer->serialize($errors,"json");
                return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
            }
            $em->persist($profil);
            $em->flush();

            return $this->json($profil, 201, [], ['groups'=>'profilSortie:read']);  
 
        }

        /**
         * @Route(
         *     name="list_apprenant_promo_profilSortie",
         *     path="/api/admin/promo/{id}/profilSorties",
         *     methods={"GET"},
         *     defaults={
         *          "__controller"="App\Controller\ProfilSortieController::listerProfilSortie_promo",
         *          "__api_resource_class"=ProfilSortie::class,
         *          "__api_collection_operation_name"="Lister_promo_ProfilSortie"
         *     }
         * )
         */

       public function list_Promo_ProfilSortie(PromotionRepository $repoPromo, $id, GroupesRepository $repoGroupe, ProfilSortieRepository $repo){

            $promo= $repoPromo->findAllIdAppPromo($id);
            if(!empty($promo))
            {
                $profilSortie=$repo->findByApprenant($promo);
   
               return $this->json($profilSortie, 200); 
            }

            return $this->json(["message"=>["type"=>"alert","contenu"=>"ce promo n\' existe pas!"]], 200);

        }


        /**
         * @Route(
         *     name="list_apprenant_profilSortie_promo",
         *     path="/api/admin/promo/{id}/profilSorties/{id2}",
         *     methods={"GET"},
         *     defaults={
         *          "__controller"="App\Controller\ProfilSortieController::listeLesProfilSortie_promo",
         *          "__api_resource_class"=ProfilSortie::class,
         *          "__api_item_operation_name"="Lister_ProfilSortie_promo"
         *     }
         * )
         */

       public function liste_apprenant_promo_profilSortie(PromotionRepository $repoPromo, int $id, int $id2, ProfilSortieRepository $repo){

            $idApprenants= $repoPromo->findAllIdAppPromo($id);
            if(empty($idApprenants))
            { 
                return $this->json(["message"=>["type"=>"alert","contenu"=>"ce promo n\' existe pas!"]], 200);
            }
            $prof= $repo->find($id);
            if(empty($prof))
            {
                return $this->json(["message"=>["type"=>"alert","contenu"=>"ce profil de sortie n\' existe pas!"]], 200);
            }
            $profilSortie= $repo->findByOneProfilApprenant($idApprenants,$id2);

            return $this->json($profilSortie, 200);  
    }
}
