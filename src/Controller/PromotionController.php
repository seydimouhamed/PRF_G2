<?php

namespace App\Controller;

use ContainerTqjcrpd\getUserRepositoryService;
use DateTime;
use App\Entity\User;
use App\Entity\Groupes;
use App\Entity\Promotion;
use App\Entity\Apprenant;
use App\Entity\Formateur;
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
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
        $this->encoder=$encoder;
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
    /**
     * @Route(
     *     name="modifie_Statut_Groupe",
     *     path="/api/admin/promo/{id}/groupes/{id2}",
     *     methods={"PUT"},
     *     defaults={
     *          "__api_resource_class"="Groupes::class",
     *          "__api_item_operation_name"="Statut_Groupe",
     *     }
     * )
     */
    public function modifiStatutGroupe(Request $request,EntityManagerInterface $entityManager,int $id2,int $id)
    {
        $groupe = $entityManager->getRepository(groupes::class)->find($id2);
        $promo = $entityManager->getRepository(promotion::class)->find($id);

        $modif = json_decode($request->getContent(), true);
        $idPromoGroupe = $groupe->getPromotion()->getId();
        $idPromo = $promo->getId();


        if ($idPromo == $idPromoGroupe) {
            foreach ($modif as $value) {

                foreach ($value[0] as $recu) {

                    $persi = $groupe->setStatut($recu);
                    $entityManager->persist($persi);
                    $entityManager->flush();
                    return $this->json($recu, 200);
                }
            }
        } else {
            return $this->json("Ce groupe n'existe pas", 200);
        }

    }


    /*public function addApprenant(Request $request,EntityManagerInterface $entityManager,int $id){
//recupéré tout les données de la requete
        $apprenants=json_decode($request->getContent(),true);
        $promo = $entityManager->getRepository(promotion::class)->find($id);
        $apprenants = $this->serializer->denormalize($apprenants,"App\Entity\User","JSON");



            $genre=$apprenants['genre'];
            $adresse=$apprenants['adresse'];
            $telephone=$apprenants['telephone'];
            $username=$apprenants['username'];
            $firstname=$apprenants['fisrtName'];
            $lastname=$apprenants['lastName'];
            $email=$apprenants['email'];
            $password=['password'];
            $profil=$apprenants['profil'];
            $archivage=$apprenants['archivage'];
            $photo = $request->files->get("photo");
            $photoBlob = fopen($photo->getRealPath(),"rb");
                if($profil==6){

                    $ajApprenants=new apprenant();
                    $ajApprenants->setGenre($genre)
                        ->setAdresse($adresse)
                        ->setTelephone($telephone);
                }elseif ($profil==4){
                    $ajApprenants=new formateur();
                }else{
                    return $this->json("Ce profil ne peut pas etre ajouter",400);
                }

        $ajApprenants->setUsername($username)
                ->setFisrtName($firstname)
                ->setLastName($lastname)
                ->setEmail($email)
                ->setPassword($this->encoder->encodePassword ($ajApprenants, $password ))
                ->setProfil($profil)
                ->setPhoto($photoBlob)
                ->setArchivage($archivage);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ajApprenants);
            $em->flush();
            return $this->json($apprenants,201);

    }
*/

    /**
     * @Route(
     *     name="delete_promo_apprenant",
     *     path="/api/admin/promo/{id}/apprenants",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::DeleteApprenant",
     *          "__api_resource_class"=User::class,
     *          "__api_item_operation_name"="delete_Apprenant"
     *     }
     *     ),
     *       @Route(
     *     name="delete_promo_formateur",
     *     path="/api/admin/promo/{id}/formateur",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::DeleteApprenant",
     *          "__api_resource_class"=User::class,
     *          "__api_item_operation_name"="delete_Formateur"
     *     }
     *
     * )
     */
    public function DeleteApprenant(Request $request,EntityManagerInterface $entityManager,UserRepository $userRepository){
        $reponse=json_decode($request->getContent(),true);

        $username=$reponse['username'];
        $userId=$userRepository->findOneBy(["username"=>$username])
           ->setArchivage(false);
       $this->em->persist($userId);
        $this->em->flush();
        return $this->json(true,200);


    }
    /**
     * @Route(
     *     name="modifier_promo_id",
     *     path="/api/admin/promo/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::ModifierPromo",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_item_operation_name"="modifier_Promo"
     *     }
     *     ),
    */
    public function ModifierPromo(Request $request,EntityManagerInterface $entityManager,int $id){
        $reponse=json_decode($request->getContent(),true);
        $libele=['langue','titre','description','lieu','dateDebut','dateFinPrvisoire','fabrique','dateFinReelle','status'];

        $promo = $entityManager->getRepository(promotion::class)->find($id);
        $tabfonct=[
            $promo->setLangue($reponse['langue']),
            $promo->setTitre($reponse['titre']),
            $promo->setdescription($reponse['description']),
            $promo->setLieu($reponse['lieu']),
            $promo->setDateDebut(\DateTime::createFromFormat('Y-m-d',$reponse['dateDebut'])),
            $promo->setDateFinPrvisoire(\DateTime::createFromFormat('Y-m-d',$reponse['dateFinPrvisoire'])),
            $promo->setFabrique($reponse['fabrique']),
            $promo->setDateFinReelle(\DateTime::createFromFormat('Y-m-d',$reponse['dateFinReelle'])),
            $promo->setStatus($reponse['status'])];
        $tab=[];
        for ($i=0;$i<count($reponse);$i++){

            if(isset($reponse[$libele[$i]])){

                $tab1[]=$reponse[$libele[$i]];
                $entityManager->persist($tabfonct[$i]);
                $entityManager->flush();
            }

        }


        return $this->json(true,200);


    }
    /**
     * @Route(
     *     name="add_promo_apprenant",
     *     path="/api/admin/promo/{id}/apprenants",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::addApprenant",
     *          "__api_resource_class"=User::class,
     *          "__api_item_operation_name"="addANDremoveUser"
     *     }
     * ),
     *  @Route(
     *     name="add_promo_formateur",
     *     path="/api/admin/promo/{id}/formateur",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::addFormateur",
     *          "__api_resource_class"=User::class,
     *          "__api_item_operation_name"="addANDremoveUser"
     *     }
     * )
     */
    public function addANDremoveUser(UserRepository $userRepository,Request $request,EntityManagerInterface $entityManager,int $id){

        $reponse=json_decode($request->getContent(),true);
        $action=$reponse['action'];
        $tableau=['username','email'];

        if($action=="ajouter"){
            for ($i=0;$i<count($tableau);$i++){

                if(isset($reponse[$tableau[$i]])){
                    $user=$reponse[$tableau[$i]];
                    $userId=$userRepository->findOneBy([$tableau[$i]=>$user]);
                    $idProfil=$userId->getProfil()->getId();

                    if($idProfil==4){
                                    $promo=new promotion();
                                    $promo->addFormateur($userId);

                                                         }
                    if($idProfil==6){
                        $groupe=new Groupes();
                        $groupe->addApprenant($userId);
                                    }



                }
            }
        }
        if($reponse=="supprimer"){

            for ($i=0;$i<count($tableau);$i++){

                if(isset($reponse[$tableau[$i]])){
                    $user=$reponse[$tableau[$i]];
                    $userId=$userRepository->findOneBy([$tableau[$i]=>$user]);
                    $idProfil=$userId->getProfil()->getId();
                    if($idProfil==4){
                        $promo=new promotion();
                        $promo->removeFormateur($userId);
                    }
                    if($idProfil==6){
                        $groupe=new Groupes();
                        $groupe->removeApprenant($userId);
                    }
                }
            }
        }


       return $this->json(true,200);

    }
}

