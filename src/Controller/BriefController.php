<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Brief;
use App\Entity\BriefApprenant;
use App\Entity\BriefMaPromo;
use App\Entity\Competence;
use App\Entity\EtatbriefGroupe;
use App\Entity\Formateur;
use App\Entity\Groupes;
use App\Entity\Livrable;
use App\Entity\LivrableAttenduApprenant;
use App\Entity\LivrableAttendus;
use App\Entity\LivrablePartielApprenant;
use App\Entity\Niveau;
use App\Entity\Promotion;
use App\Entity\Ressource;
use App\Entity\Tag;
use App\Repository\BriefRepository;
use App\Repository\GroupesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BriefController extends AbstractController
{
    private $em;
    private $validator;
    private $repo;
    private $repoGroupe;
    private $serializer;

    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,GroupesRepository $repoGroupe,BriefRepository $repo,EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->repo=$repo;
        $this->repoGroupe=$repoGroupe;
        $this->em=$em;
    }

    /**
     * @Route(
     *     name="get",
     *     path="/api/formateurs/{id}/promo/{id1}/briefs/{id2}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::get_all_brief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get"
     *     }
     *  )
     */
    public function get_One_brief($id,$id1,$id2)
    {
        $for = $this->em->getRepository(Formateur::class)->find($id);
        $promo = $this->em->getRepository(Promotion::class)->find($id1);
        $b = $this->em->getRepository(Brief::class)->find($id2);
        if (isset($for)){
            $brief=[];
            $pro=$for->getPromotions();
            foreach ($pro as $val){
                if ($promo==$val) {
                    $briefm=$promo->getBriefMaPromos();
                    foreach ($briefm as $value) {
                        $brief[]=$value->getBrief();
                        for ($i=0;$i<count($brief);$i++){
                            if ($b==$brief[$i]){
                                return $this->json($b, 200);
                                //dd($b);
                            }
                        }
                    }
                    return $this->json("Ce brief n'est pas assigné à cette promo",401);
                }
            }
            return $this->json("Cette promo n'est pas créé par ce formateur",401);
        }
        return $this->json("Cet formateur n'existe pas",401);
    }

    /**
     * @Route(
     *     name="add_livrable",
     *     path="/api/apprenants/{id}/groupe/{id1}/livrables",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::addLivrable",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="addLivrable"
     *     }
     * )
     */

    public function addLivrable(EntityManagerInterface $em, Request $request, $id1,$id)
    {
        $a = $this->em->getRepository(Apprenant::class)->find($id);
        $gr = $this->em->getRepository(Groupes::class)->find($id1);
        $liva = $this->em->getRepository(LivrableAttendus::class)->findAll();
        $fake = Factory::create('fr-FR');
        //recupéré tout les données de la requete
        $livrables = $request->request->all();
        $livrable = $this->serializer->denormalize($livrables,"App\Entity\LivrableAttenduApprenant",true);
        //$liv=json_decode($request->getContent(),true);
        if (isset($a)){
            $ap=[];
            $groupe=$a->getGroupes();
            foreach ($groupe as $g) {
                if ($g==$gr) {
                    $ab=$gr->getApprenants();
                    for ($i=0;$i<count($ab); $i++){
                        $livrable->setApprenant($ab[$i]);
                        $livrable->setLivrableAttendu($fake->randomElement($liva));
                    }
                    $em->persist($livrable);
                    $em->flush();
                    return $this->json("success",201);
                }
            }
        }
        return $this->json("Cet apprenant n'existe pas",201);
    }

    /**
     * @Route(
     *     name="get_livrables",
     *     path="/api/apprenants/{id}/promo/{id1}/briefs/{id2}",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::get_livrables_partiels",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="get"
     *     }
     * )
     */
    public function get_livrables_partiels($id,$id1,$id2)
    {
        $promo = $this->em->getRepository(Promotion::class)->find($id1);
        $b = $this->em->getRepository(Brief::class)->find($id2);
        $a = $this->em->getRepository(Apprenant::class)->find($id);
        if (isset($promo)) {
            $brief=[];
            $groupe=$promo->getGroupes();
            $briefm=$promo->getBriefMaPromos();
            foreach ($briefm as $br){
                $brief[]=$br->getBrief();
                foreach ($brief as $item) {
                    if ($item==$b){

                        foreach ($groupe as $g){
                            $apprenants=$g->getApprenants();
                            foreach ($apprenants as $ap){

                               if ($a==$ap){

                                    $livAp=$a->getAprenantLivrablePartiels();
                                    foreach ($livAp as $liv){
                                        return $this->json($liv->getLivrablePartiel(),401);
                                    }

                               }

                            }
                            return $this->json("cet apprenant n'est pas dans les groupe de cette promo ",401);
                        }
                    }
                }
                return $this->json("Cet brief n'est pas assigné à cette promo",401);
            }

        }
        return $this->json("Cette promo n'existe pas",401);
    }

    /**
     * @Route(
     *     name="put_brief",
     *     path="/api/formateurs/promo/{id}/brief/{id1}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::archiver_Cloturer_Brief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="archiverCloturerBrief"
     *     }
     * )
     */
    public function archiver_Cloturer_Brief($id,$id1, Request $request, EntityManagerInterface $em)
    {
        $promo = $em->getRepository(Promotion::class)->find($id);
        $b = $this->em->getRepository(Brief::class)->find($id1);
        $livrables = $this->em->getRepository(LivrableAttendus::class)->findAll();
        $n = $this->em->getRepository(Niveau::class)->findAll();
        $bmp=$b->getBriefMaPromos();
        $niveau = $b->getNiveau();
        $ressource = $b->getRessources();
        $tag=$b->getTag();
        $etat=$b->getEtatbriefGroupes();
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];

        if(isset($promo)){
            $briefM = $promo->getBriefMaPromos();
            foreach ($briefM as $o)
            {
                if ($o->getBrief()==$b){
                    if($action=="archiver") {
                        $b->setArchivage(true);
                        foreach ($etat as $e){
                                $groupes=$e->getGroupe();
                                $b->removeEtatbriefGroupe($e);
                                $groupes->removeEtatbriefGroupe($e);
                                $em->persist($groupes);
                                $em->persist($b);
                                $em->remove($e);
                            }
                        $em->persist($b);
                        $em->flush();
                        return $this->json("success",200);
                    }
                    if($action=="cloturer"){
                        $b->setEtat('cloturé');
                        foreach ($etat as $e){
                            $groupes=$e->getGroupe();
                            $b->removeEtatbriefGroupe($e);
                            $groupes->removeEtatbriefGroupe($e);
                            $em->persist($groupes);
                            $em->persist($b);
                            $em->remove($e);
                        }
                        $em->persist($b);
                        $em->flush();
                        return $this->json("success",200);
                    }
                    if ($action=="ajouter_niveau"){
                        for ($ni=0;$ni<2;$ni++)
                        {
                            $n[$ni]->addBriefs($b);
                            $b->addNiveau($n[$ni]);
                            $em->persist($b);
                        }
                    }
                    if ($action=="supprimer_niveau"){
                        for($k=0;$k<count($niveau);$k++){
                            $b->removeNiveau($niveau[$k]);
                        }
                    }
                    if ($action=="ajouter_ressource"){
                        for($y=0;$y<2;$y++){
                            $ressource=new Ressource();
                            $ressource->setTitre('titre'.$y)
                                ->setUrl('url'.$y)
                                ->setBrief($b);
                            $em->persist($ressource);
                        }
                    }
                    if ($action=="supprimer_ressource"){
                        foreach($ressource as $res){
                            $b->removeRessource($res);
                            $em->remove($res);
                        }
                    }
                    if ($action=="ajouter_livrable_attendus"){
                        for ($l=1;$l<=3;$l++) {
                            $livrables[$l]->addBrief($b);
                            $b->addLivrableAttendu($livrables[$l]);
                            $em->persist($livrables[$l]);
                        }
                    }
                    if ($action=="supprimer_livrable_attendus"){
                        for($s=0;$s<count($livrables);$s++){
                            $livrables[$s]->removeBrief($b);
                            $b->removeLivrableAttendu($livrables[$s]);
                            $em->persist($livrables[$s]);
                        }
                    }
                    if ($action=="ajouter_tag"){
                        for ($t=0;$t<2;$t++) {
                            $tag[$t]->addBrief($b);
                            $b->addTag($tag[$t]);
                            $em->persist($livrables[$t]);
                            $em->persist($b);
                        }
                    }
                    if ($action=="supprimer_tag"){
                        for($t=0;$t<count($tag);$t++){
                            $b->removeTag($tag[$t]);
                        }
                    }
                    if ($action=="deassigner"){
                        foreach ($bmp as $value){
                            $bA=$value->getBriefApprenants();
                            $liv=$value->getLivrablePartiels();
                            $groupe=$promo->getGroupes();
                            foreach ($groupe as $v){
                                $app=$v->getApprenants();
                                foreach ($app as $ap){
                                    foreach ($bA as $val){
                                        $ap->removeBriefApprenant($val);
                                        $value->removeBriefApprenant($val);
                                        $em->remove($val);
                                        $em->persist($ap,$value);
                                    }
                                }
                            }
                            foreach ($bmp as $l){
                                foreach ($liv as $al){
                                    $l->removeLivrablePartiel($al);
                                    $em->remove($al);
                                    $em->persist($l);
                                }
                            }
                            $b->removeBriefMaPromo($value);
                            $promo->removeBriefMaPromo($value);
                            $em->remove($value);
                            $em->persist($b,$promo);
                        }
                    }

                    $em->persist($b);
                    $em->flush();
                    return $this->json("success",200);
                }
            }
            return $this->json("Ce brief n'est pas assigné à cette promo",401);
        }
        return $this->json("Cette promo n'existe pas",401);
    }

    /**
     * @Route(
     *     name="put_id_brief",
     *     path="/api/formateurs/promo/{id}/brief/{id1}/assignation",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::assigner_deassigner_Brief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="assigner_deassigner_Brief"
     *     }
     * )
     */
    public function assigner_deassigner_Brief($id,$id1, Request $request, EntityManagerInterface $em)
    {
        $promo = $em->getRepository(Promotion::class)->find($id);
        $b = $em->getRepository(Brief::class)->find($id1);
        $fake = Factory::create('fr-FR');
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];
        if(isset($promo)) {
            $groupe=$promo->getGroupes();
            if (isset($b)) {
                if($action=="desassigner_apprenant"){
                    $bmp=$b->getBriefMaPromos();
                    foreach ($groupe as $as){
                        $app=$as->getApprenants();
                        foreach ($app as $ve) {
                            foreach ($bmp as $v){
                                $bA=$v->getBriefApprenants();
                                foreach ($bA as $val){
                                    $ve->removeBriefApprenant($val);
                                    $v->removeBriefApprenant($val);
                                    $em->remove($val);
                                    $em->persist($v);
                                    $em->persist($ve);
                                    $em->flush();
                                    return $this->json("success", 200);
                                }
                            }
                        }
                    }

                }
                if ($action == "assigner_apprenant") {
                    foreach ($groupe as $as){
                        $ap=$as->getApprenants();
                    }
                    $briefPromo=new BriefMaPromo();
                    $briefPromo->setBrief($b);
                    $briefPromo->setPromo($promo);
                    $briefAp=new BriefApprenant();
                    $briefAp->setStatut($fake->randomElement(['valide','invalidé']))
                        ->setApprenant($fake->randomElement($ap))
                        ->setBriefPromo($briefPromo);
                    $em->persist($briefAp);
                    $em->persist($briefPromo);
                    $em->flush();
                    return $this->json("success",200);
                }
                if ($action == "assigner_un_groupe") {
                    $etat=new EtatbriefGroupe();
                    $etat->setStatut($fake->randomElement(['valide','invalidé']))
                        ->setBrief($b)
                        ->setGroupe($fake->unique()->randomElement($groupe));
                    $em->persist($etat);
                    foreach ($groupe as $g){
                        $promos=$g->getPromotion();
                    }
                    if ($promo==$promos){
                        $briefPromo=new BriefMaPromo();
                        $briefPromo->setBrief($b);
                        $briefPromo->setPromo($promos);
                        $em->persist($briefPromo);
                    }
                    $em->flush();
                    return $this->json("success",200);
                }
                if($action=="desassigner_un_groupe"){
                    $etat=$b->getEtatbriefGroupes();
                    foreach ($etat as $v){
                        $groupes=$v->getGroupe();
                        $b->removeEtatbriefGroupe($v);
                        $groupes->removeEtatbriefGroupe($v);
                        $em->persist($groupes);
                        $em->persist($b);
                        $em->remove($v);
                        $em->flush();
                        return $this->json("success",200);
                    }
                }
            }
            return $this->json("Cet brief n'existe pas", 401);
        }
        return $this->json("Cette promo n'existe pas",401);
    }

    /**
     * @Route(
     *     name="dupliquer_brief",
     *     path="/api/formateurs/briefs/{id}",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::dupliquer_brief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="dupliquer_brief"
     *     }
     * )
     */

    public function dupliquer_brief($id, EntityManagerInterface $em)
    {
        $brief = $this->em->getRepository(Brief::class)->find($id);
        $new_brief = clone $brief;
        $em->persist($new_brief);
        $em->flush();

        return $this->json("success" ,200);
    }

    /**
     * @Route(
     *     name="add_brief_post",
     *     path="/api/formateurs/briefs",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\BriefController::addbrief",
     *          "__api_resource_class"=Brief::class,
     *          "__api_collection_operation_name"="addbrief"
     *     }
     * )
     */

    public function addbrief(EntityManagerInterface $em, Request $request)
    {
        $brief = $request->request->all();
        $brief = $this->serializer->denormalize($brief,"App\Entity\Brief",true);
        $group = $this->em->getRepository(Groupes::class)->findAll();
        $promo = $this->em->getRepository(Promotion::class)->findAll();
        $tag = $this->em->getRepository(Tag::class)->findAll();
        $livrables = $this->em->getRepository(LivrableAttendus::class)->findAll();
        $niveau = $this->em->getRepository(Niveau::class)->findAll();
        $fake = Factory::create('fr-FR');
        //recupéré tout les données de la requete


        //recupération de l'image
        $photo = $request->files->get("ImageExemplaire");

        if(!$photo)
        {

            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
        }
        //$base64 = base64_decode($imagedata);
        $photoBlob = fopen($photo->getRealPath(),"rb");
        $brief->setImageExemplaire($photoBlob);
        $brief->setStatut("brouillon");
        $brief->setEtat("value");
        $brief->setArchivage(false)
            ->setDatePoste(new\ DateTime());
        for ($t=0;$t<=3;$t++)
        {
            $tag[$t]->addBrief($brief);
            $brief->addTag($tag[$t]);
            $em->persist($tag[$t]);
        }
        $briefPromo=new BriefMaPromo();
        $briefPromo->setBrief($brief);
        $briefPromo->setPromo($fake->unique()->randomElement($promo));
        $em->persist($briefPromo);

        $em->persist($brief);
        $em->flush();

        return $this->json('success',201);

    }
}
