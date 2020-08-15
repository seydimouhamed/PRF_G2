<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use App\Repository\PromotionRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use ContainerTqjcrpd\getUserRepositoryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PromotionController extends AbstractController
{


    private $serializer;
    private $validator;
    private $em;
    private $repo;
    private $repoGroupe;
    private $encoder;
    public function __construct(
        PromotionRepository $repo,
        GroupesRepository $repoGroupe,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->repo=$repo;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->repoGroupe=$repoGroupe;
        $this->em=$em;
        $this->encoder=$encoder;
        $this->tokenStorage = $tokenStorage;
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
        $em = $this->getDoctrine()->getManager();
        //recupéré tout les données de la requete
        //$promo=json_decode($request->getContent(),true);
        $promo=$request->request->all();
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
        //creation dun groupe pour la promo

        $group= new Groupes();
        // $date = date('Y-m-d');
        $group->setNom('Groupe Générale')
            ->setDateCreation(new \DateTime())
            ->setStatut('ouvert')
            ->setType('groupe principale')
            ->setPromotion($promo);
        //----------------------------------------------------
        //DEBUT RECUPERATION DES DONNEES DU FICHIERS EXCELS
        //-----------------------------------------------------

        $doc = $request->files->get("document");

        $file= IOFactory::identify($doc);

        $reader= IOFactory::createReader($file);

        $spreadsheet=$reader->load($doc);

        $tab_apprenants= $spreadsheet->getActivesheet()->toArray();

        $attr=$tab_apprenants[0];
        $tabrjz=[];
        for($i=1;$i<count($tab_apprenants);$i++)
        {
            $apprenant=new Apprenant();
            for($k=0;$k<count($tab_apprenants[$i]);$k++)
            {
                $data=$tab_apprenants[$i][$k];
                if($attr[$k]=="Password")
                {
                    $apprenant->setPassword($this->encoder->encodePassword($apprenant,$data));
                }else
                {
                    $apprenant->{"set".$attr[$k]}($data);
                }
            }
            $apprenant->setArchivage(0);
            $apprenant->setStatut("attente");
            $em->persist($apprenant);
            $group->addApprenant($apprenant);
        }

        //------------------------------------------------------
        //FIN RECUPERATION DES DONNEES DU FICHIERS EXCELS
        //-----------------------------------------------------
        $em->persist($group);
        $promo->addGroupe($group);
        //$promo->setArchivage(false);

        $em->persist($promo);
        $em->flush();

        return $this->json("success",201);
    }


    /**
     * @Route(
     *     name="get_promotion_all",
     *     path="/api/admin/promos",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromos",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_promos"
     *     }
     * )
     */
    public function getPromos()
    {
        $promo=$this->repo->findAll();
        return $this->json($promo,200);
    }

    /**
     * @Route(
     *     name="get_promotion_principale",
     *     path="/api/admin/promos/principal",
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
     *     path="/api/admin/promos/apprenants/attente",
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
        $promos= $this->repo->findAll();

        $gc=[];
        foreach($promos as $promo)
        {

            $group_ref_detail['referentiel']=$promo->getReferentiel();
            //get id promo
            $idPromo = $promo->getID();
            //recupération du groupe principal
            $group_ref_detail['apprenants']=[];
            $groupe=$this->repoGroupe->findBy(['promotion'=>$idPromo,'type'=>"groupe principale"], ['id' => 'DESC'])[0];
            foreach($groupe->getApprenants() as $apprenant)
            {
                if($apprenant->getStatut()=="attente")
                {
                    $group_ref_detail['apprenants'][]=$apprenant->getFisrtName()." ".$apprenant->getLastName();
                }
            }

            $gc[]= $group_ref_detail;

        }


        return $this->json($gc,200);
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
        $promo= $this->repo->find($id);

        $gc=[];

        $group_ref_detail['referentiel']=$promo->getReferentiel();
        //get id promo
        $idPromo = $promo->getID();
        //recupération du groupe principal
        $group_ref_detail['apprenants']=[];
        $groupe=$this->repoGroupe->findBy(['promotion'=>$idPromo,'type'=>"groupe principale"], ['id' => 'DESC'])[0];

        foreach($groupe->getApprenants() as $apprenant)
        {
            if($apprenant->getStatut()=="attente")
            {
                if($idPromo==$id){
                    $group_ref_detail['apprenants'][]=$apprenant->getFisrtName()." ".$apprenant->getLastName();
                }
            }
        }





        return $this->json($group_ref_detail,200);
    }



    /**
     * @Route(
     *     name="promo_get_principal",
     *     path="/api/admin/promos/{id}/principal",
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
     *     path="/api/admin/promos/{id}/referentiel",
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



    /**
     * @Route(
     *     name="promo_get_formateur",
     *     path="/api/admin/promos/{id}/formateur",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getPromoidformateur",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="get_Promoidformateurl"
     *     }
     * )
     */
    public function getPromoidformateur($id)
    {
        $promo=$this->repo->find($id);

        $data["referentiel"] = $promo->getReferentiel();
        $data['formateurs'] =[];
        foreach($promo->getFormateurs() as $form){
            $data['formateurs'][]=$form->getFisrtName()." ".$form->getLastName();
        }


        return $this->json($data ,200);
    }


    /**
     * @Route(
     *     name="get_pro_group_apprenant",
     *     path="/api/admin/promos/{id}/groupes/{id1}/apprenants",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::getpromogroupeapprenant",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_item_operation_name"="get_pro_grp_apprenant"
     *     }
     * ),
     */
    public  function getpromogroupeapprenant($id,$id1)
    {
        $promo= $this->em->getRepository(Promotion::class)->find($id);
        $groupe=$this->repoGroupe->findBy(['promotion'=>$id,"id"=>$id1], ['id' => 'DESC'])[0];


        $tab['referentiels']=$promo->getReferentiel();
        $tab['formateurs']=[];
        foreach($promo->getFormateurs() as $form){
            $tab['formateurs'][]=$form->getFisrtName()." ".$form->getLastName();
        }
        $tab['groupe']=$groupe;
        $tab['groupe']=["id"=>$groupe->getID(),
            "nom"=> $groupe->getNom(),
            "dateCreation"=> $groupe->getDateCreation(),
            "statut"=>$groupe->getStatut(),
            "type"=> $groupe->getType()];
        foreach($groupe->getApprenants() as $apprenant)
        {
            $tab['groupe']['apprenants'][]=$apprenant->getFisrtName()." ".$apprenant->getLastName();
        }

        return $this->json($tab ,200);

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

    private function getreferentielpromo($id=null)
    {
        $promos= $this->repo->find($id);
        $promo_ref=$promos->getReferentiel();

        return $promo_ref;

    }
    /**
     * @Route(
     *     name="modifie_Statut_Groupe",
     *     path="/api/admin/promos/{id}/groupes/{id2}",
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
     *     path="/api/admin/promos/{id}",
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
        $libele=['langue','titre','description','lieu','fabrique','status','referentiel'];
        $dateLib=['dateFinPrvisoire','dateFinReelle','dateDebut'];
        $refern="referentiel";
        $referentiel=['libelle','presentation','programme','critereAdmission','critereEvaluation'];
        $promo = $entityManager->getRepository(promotion::class)->find($id);
        for($i=0;$i<count($reponse);$i++){

            if(isset($reponse[$libele[$i]])){

                $promo->{"set".ucfirst($libele[$i])}($reponse[$libele[$i]]);
                for($a=0;$a<count($dateLib);$a++){

                    if(isset($reponse[$dateLib[$a]])){

                        $promo->{"set".ucfirst($dateLib[$a])}(\DateTime::createFromFormat('Y-m-d',$reponse[$dateLib[$a]]));
                    }

                }

                for($b=0;$b<count($referentiel);$b++){

                    if(isset($reponse['referentiel'])){

                        $promo->getReferentiel()->{"set".ucfirst($referentiel[$b])}($reponse[$referentiel[$b]]);


                    }

                }


            }


            $entityManager->persist($promo);
            $entityManager->flush();
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
    public function addANDremoveUser(MailerInterface $mailer,UserRepository $userRepository,Request $request,EntityManagerInterface $entityManager,int $id){
        $promo = $entityManager->getRepository(promotion::class)->find($id);

        $reponse=json_decode($request->getContent(),true);
        $action=$reponse['action'];
        $tableau=['username','email'];

        if($action=="ajouter"){
            for ($i=0;$i<count($tableau);$i++){

                if(isset($reponse[$tableau[$i]])){
                    $user=$reponse[$tableau[$i]];
                    $userId=$userRepository->findOneBy([$tableau[$i]=>$user]);
                    $idProfil=$userId->getProfil()->getId();
                    $libelle=$userId->getProfil()->getLibelle();

                    if($libelle=="Formateur"){

                        $promo->addFormateur($userId);

                    }
                    if($libelle=="Aprenant"){

                        for($z=0;$z<count($promo->getGroupes());$z++) {

                            if ($promo->getGroupes()[$z]->getType() == "groupe principale") {

                                $promo->getGroupes()[$z]->addApprenant($userId);
                                $email = (new Email())
                                    ->from("abdoukarimsidibe1@gmail.com")
                                    ->to($promo->getGroupes()[0]->getApprenants()[0]->getEmail())
                                    ->subject('Message teste!')
                                    ->text("Bonjour {$promo->getGroupes()[0]->getApprenants()[0]->getFisrtName()}! ❤️ce message est un teste")
                                    ->html("<h1>Felicitation {$promo->getGroupes()[0]->getApprenants()[0]->getFisrtName()} !! vous avez ete selectionné(e) suite
                                    a votre test d'entré a la Sonatel Academy! ❤.<br>Veuillez utiliser ces informations pour vous connecter a votre Promo,Username:
                                    {$promo->getGroupes()[0]->getApprenants()[0]->getUsername()}, Password:Pass123️</h1>");


                                $mailer->send($email);
                            }
                        }

                    }



                }
            }
        }
        if($action=="supprimer"){

            for ($i=0;$i<count($tableau);$i++){

                if(isset($reponse[$tableau[$i]])){
                    $user=$reponse[$tableau[$i]];
                    $userId=$userRepository->findOneBy([$tableau[$i]=>$user]);
                    $idProfil=$userId->getProfil()->getId();
                    $libelle=$userId->getProfil()->getLibelle();
                    if($libelle=="Formateur"){

                        $promo->removeFormateur($userId);
                    }
                    if($libelle=="Aprenant"){

                        for($z=0;$z<count($promo->getGroupes());$z++){

                            if($promo->getGroupes()[$z]->getType()=="groupe principale"){

                                $promo->getGroupes()[$z]->removeApprenant($userId);
                            }
                            if($promo->getGroupes()[$z]->getType()=="binome" || $promo->getGroupes()[$z]->getType()=="filerouge"){

                                $promo->getGroupes()[$z]->removeApprenant($userId);
                            }
                        }



                        $promo->getGroupes()[0]->removeApprenant($userId);
                    }
                }
            }
        }

        $entityManager->persist($promo);
        $entityManager->flush();
        return $this->json(true,200);
//return dd($promo->getGroupes()[0]->getApprenants()[0]->getEmail());
        // return $this->json($promo->getGroupes()[1]->getType(),200);
    }
}