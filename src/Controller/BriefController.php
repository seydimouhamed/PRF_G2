<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Brief;
use App\Entity\Competence;
use App\Entity\Formateur;
use App\Entity\Groupes;
use App\Entity\Livrable;
use App\Entity\LivrableAttendus;
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
    public function get_all_brief($id,$id1,$id2)
    {
        $for = $this->em->getRepository(Formateur::class)->find($id);
        $promo = $this->em->getRepository(Promotion::class)->find($id1);
        $b = $this->em->getRepository(Brief::class)->find($id2);
        if (isset($for)){
            $pro=$for->getPromotions();
            foreach ($pro as $val){
                if ($promo==$val) {
                    $brief=$promo->getBriefs();
                    foreach ($brief as $value) {
                        if ($b == $value) {
                            return $this->json($b, 200);
                        }
                    }
                    return $this->json("Cet brief n'existe pas dans cette promo", 401);
                }
                return $this->json("Cette promo n'est pas créé par ce formateur",401);
            }
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
        $group = $this->em->getRepository(Groupes::class)->find($id1);
        $a = $this->em->getRepository(Apprenant::class)->find($id);
        //recupéré tout les données de la requete
        $livrables = $request->request->all();
        $livrable = $this->serializer->denormalize($livrables,"App\Entity\Livrable",true);
        //$liv=json_decode($request->getContent(),true);
        if (isset($group)){
            $apprenant=$group->getApprenants();
            foreach ($apprenant as $v){
                if ($v==$a){
                    $livrable->setApprenant($a);
                    $em->persist($livrable);
                    $em->flush();
                    return $this->json("success",201);
                }
            }
            return $this->json("Cet apprenant n'est pas dans ce groupe",201);
        }
        return $this->json("Ce groupe n'existe pas",201);
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
            $tab=[];
            $groupe=$promo->getGroupes();
            $brief=$promo->getBriefs();
            foreach ($groupe as $valu){
                $apprenants=$valu->getApprenants();
                foreach ($brief as $va){
                    if ($va==$b) {
                        foreach ($apprenants as $v){
                            if ($v==$a){
                                $tab[] = ["Apprenant"=>$a];
                                return $this->json($tab, 200);
                            }
                        }
                    }
                    //return $this->json("Cet apprenant n'existe pas dans les groupes de cette promo", 401);
                }
            }
            return $this->json("Ce brief n'est pas assigné à cette promo", 401);
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
        $tag = $this->em->getRepository(Tag::class)->findAll();
        $groupe = $this->em->getRepository(Groupes::class)->findAll();
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];

        if(isset($promo)){
            $brief = $promo->getBriefs();
            foreach ($brief as $value) {
                if ($b == $value) {
                    $niveau = $value->getNiveau();
                    $ressource = $value->getRessources();
                    $liv = $value->getLivrablePartiels();
                    if($action=="archiver"){
                        $value->setArchivage(true);
                        for($k=0;$k<count($niveau);$k++){
                            $value->removeNiveau($niveau[$k]);
                        }
                        for($re=0;$re<=count($ressource);$re++){
                            $value->removeRessource($ressource[$re]);
                        }
                        for($s=0;$s<count($livrables);$s++){
                            $livrables[$s]->removeBrief($value);
                            $value->removeLivrableAttendu($livrables[$s]);
                            $em->persist($livrables[$s]);
                        }
                        for($a=0;$a<count($tag);$a++){
                            $tag[$a]->removeBrief($value);
                            $value->removeTag($tag[$a]);
                            $em->persist($tag[$a]);
                        }
                        for($m=0;$m<count($liv);$m++){
                            $value->removeLivrablePartiel($liv[$m]);
                        }
                        for($o=0;$o<count($groupe);$o++){
                            $groupe[$o]->removeBrief($value);
                            $value->removeGroupe($groupe[$o]);
                            $em->persist($groupe[$o]);
                        }
                        $promo->removeBrief($value);
                        $value->removePromo($promo);
                        $em->persist($promo);
                    }
                    if($action=="cloturer"){
                        $value->setEtat('cloturé');
                    }
                    if ($action=="ajouter_niveau"){
                        $competence = $this->em->getRepository(Competence::class)->findAll();
                        for($j=1;$j<=3;$j++)
                        {
                            $niveau=new Niveau();
                            $niveau->setLibelle('niveau '.$j);
                            $niveau->setCritereEvaluation('competentence '.$j.'critere_evaluation '.$j);
                            $niveau->setGroupeAction('competentence '.$j.'groupe action '.$j);
                            $niveau->setCompetence($competence[$j]);
                            $niveau->setBrief($value);
                            $em->persist($niveau);
                        }
                    }
                    if ($action=="supprimer_niveau"){
                        for($k=0;$k<count($niveau);$k++){
                            $value->removeNiveau($niveau[$k]);
                        }
                    }
                    if ($action=="ajouter_ressource"){
                        for($y=0;$y<2;$y++){
                            $ressource=new Ressource();
                            $ressource->setTitre('titre'.$y)
                                ->setUrl('url'.$y)
                                ->setBrief($value);
                            $em->persist($ressource);
                        }
                    }
                    if ($action=="supprimer_ressource"){
                        for($r=0;$r<count($ressource);$r++){
                            $value->removeRessource($ressource[$r]);
                        }
                    }
                    if ($action=="ajouter_livrable_attendus"){
                        for ($l=1;$l<=3;$l++) {
                            $livrables[$l]->addBrief($value);
                            $value->addLivrableAttendu($livrables[$l]);
                            $em->persist($livrables[$l]);
                        }
                    }
                    if ($action=="supprimer_livrable_attendus"){
                        for($s=0;$s<count($livrables);$s++){
                            $livrables[$s]->removeBrief($value);
                            $value->removeLivrableAttendu($livrables[$s]);
                            $em->persist($livrables[$s]);
                        }
                    }
                    if ($action=="ajouter_tag"){
                        for ($t=1;$t<=3;$t++) {
                            $tag[$t]->addBrief($value);
                            $value->addTag($tag[$t]);
                            $em->persist($livrables[$t]);
                        }
                    }
                    if ($action=="supprimer_tag"){
                        for($a=0;$a<count($tag);$a++){
                            $tag[$a]->removeBrief($value);
                            $value->removeTag($tag[$a]);
                            $em->persist($tag[$a]);
                        }
                    }
                    if ($action=="deassigner"){
                        $promo->removeBrief($value);
                        $value->removePromo($promo);
                        $em->persist($promo);
                    }

                    $em->persist($b);
                    $em->flush();
                    return $this->json("success",200);
                }
            }
            return $this->json("Cet brief n'existe pas dans cette promo", 401);
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
        $groupe = $this->em->getRepository(Groupes::class)->findAll();
        $fake = Factory::create('fr-FR');
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];
        if(isset($promo)) {
            if (isset($b)) {
                if ($action == "assigner_promo") {
                    $promo->addBrief($b);
                    $b->addPromo($promo);
                    $em->persist($promo);
                    $em->persist($b);
                }
                if ($action == "assigner_un_groupe") {
                        $b->addGroupe($fake->unique()->randomElement($groupe));
                }
                if ($action == "assigner_des_groupe") {
                    for ($i=0; $i<3;$i++){
                        $b->addGroupe($fake->unique()->randomElement($groupe));
                    }
                }
                $em->persist($b);
                $em->flush();

                $brief = $promo->getBriefs();
                foreach ($brief as $value) {
                    if ($b == $value) {
                        if($action=="desassigner_un_groupe"){
                            foreach ($groupe as $g){
                                $g->removeBrief($value);
                                $value->removeGroupe($g);
                            }
                            $em->persist($g);
                        }
                        if($action=="desassigner_promo"){
                            $promo->removeBrief($value);
                            $value->removePromo($promo);
                            $em->persist($promo);
                        }
                        $em->persist($value);
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

        $group = $this->em->getRepository(Groupes::class)->findAll();
        $promo = $this->em->getRepository(Promotion::class)->findAll();
        $tag = $this->em->getRepository(Tag::class)->findAll();
        $livrables = $this->em->getRepository(LivrableAttendus::class)->findAll();
        $niveau = $this->em->getRepository(Niveau::class)->findAll();
        //recupéré tout les données de la requete
        $brief = $request->request->all();
        $brief = $this->serializer->denormalize($brief,"App\Entity\Brief",true);
        //recupération de l'image
        $photo = $request->files->get("ImageExemplaire");

        if(!$photo)
        {

            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
        }
        //$base64 = base64_decode($imagedata);
        $photoBlob = fopen($photo->getRealPath(),"rb");
        $brief->setImageExemplaire($photoBlob);

        $competence = $this->em->getRepository(Competence::class)->findAll();
        for($j=1;$j<=3;$j++)
        {
            $niveau[$j]->addBrief($brief);
            $brief->addNiveau($niveau[$j]);
            $em->persist($niveau[$j]);
        }
        for($y=0;$y<2;$y++){
            $ressource=new Ressource();
            $ressource->setTitre('titre'.$y)
                ->setUrl('url'.$y)
                ->setBrief($brief);
            $em->persist($ressource);
        }
        for ($g=1;$g<=3;$g++)
        {
            $group[$g]->addBrief($brief);
            $brief->addGroupe($group[$g]);
            $em->persist($group[$g]);
        }
        for ($l=1;$l<=3;$l++)
        {
            $livrables[$l]->addBrief($brief);
            $brief->addLivrableAttendu($livrables[$l]);
            $em->persist($livrables[$l]);
        }
        for ($t=1;$t<=3;$t++)
        {
            $tag[$t]->addBrief($brief);
            $brief->addTag($tag[$t]);
            $em->persist($tag[$t]);
        }
        for ($p=1;$p<=2;$p++)
        {
            $promo[$p]->addBrief($brief);
            $brief->addPromo($promo[$p]);
            $em->persist($promo[$p]);
        }
        $em->persist($brief);
        $em->flush();

        return $this->json('success',201);

    }
}
