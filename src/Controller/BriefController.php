<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Competence;
use App\Entity\Formateur;
use App\Entity\Groupes;
use App\Entity\LivrableAttendus;
use App\Entity\LivrablePartiels;
use App\Entity\Niveau;
use App\Entity\Promotion;
use App\Entity\Referentiel;
use App\Entity\Ressource;
use App\Entity\Tag;
use App\Repository\BriefRepository;
use App\Repository\GroupesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function App\Entity\getId;

class BriefController extends AbstractController
{
    private $em;
    private $validator;
    private $repo;
    private $repoGroupe;
    private $serializer;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        GroupesRepository $repoGroupe,
        BriefRepository $repo,
        EntityManagerInterface $em)
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
     * )
     */
    public function get_all_brief($id,$id1,$id2)
    {
        $promo = $this->em->getRepository(Promotion::class)->find($id1);
        $brief=[];
        $brief[]=['Promotion'=>$promo->getId(), " Description "=>$promo->getDescription(), 'briefs'=>$promo->getBriefs()[$id2]];
        //dd($brief);
        return $this->json($brief,200);
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
        $brief = $promo->getBriefs()[$id1];
        $niveau = $brief->getNiveau();
        $ressource = $brief->getRessources();
        $liv = $brief->getLivrablePartiels();
        $livrables = $this->em->getRepository(LivrableAttendus::class)->findAll();
        $tag = $this->em->getRepository(Tag::class)->findAll();
        $groupe = $this->em->getRepository(Groupes::class)->findAll();
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];

        if($action=="archiver"){
            $brief->setArchivage(true);
            for($k=0;$k<count($niveau);$k++){
                $brief->removeNiveau($niveau[$k]);
            }
            for($re=0;$re<=count($ressource);$re++){
                $brief->removeRessource($ressource[$re]);
            }
            for($s=0;$s<count($livrables);$s++){
                $livrables[$s]->removeBrief($brief);
                $brief->removeLivrableAttendu($livrables[$s]);
                $em->persist($livrables[$s]);
            }
            for($a=0;$a<count($tag);$a++){
                    $tag[$a]->removeBrief($brief);
                    $brief->removeTag($tag[$a]);
                    $em->persist($tag[$a]);
                }
            for($m=0;$m<count($liv);$m++){
                $brief->removeLivrablePartiel($liv[$m]);
            }
            for($o=0;$o<count($groupe);$o++){
                $groupe[$o]->removeBrief($brief);
                $brief->removeGroupe($groupe[$o]);
                $em->persist($groupe[$o]);
            }
            $promo->removeBrief($brief);
            $brief->removePromo($promo);
            $em->persist($promo);
        }
        if($action=="cloturer"){
            $brief->setEtat('cloturé');
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
                $niveau->setBrief($brief);
                $em->persist($niveau);
            }
        }
        if ($action=="supprimer_niveau"){
            for($k=0;$k<count($niveau);$k++){
                $brief->removeNiveau($niveau[$k]);
            }
        }
        if ($action=="ajouter_ressource"){
            for($y=0;$y<2;$y++){
                $ressource=new Ressource();
                $ressource->setTitre('titre'.$y)
                    ->setUrl('url'.$y)
                    ->setBrief($brief);
                $em->persist($ressource);
            }
        }
        if ($action=="supprimer_ressource"){
            for($r=0;$r<count($ressource);$r++){
                $brief->removeRessource($ressource[$r]);
            }
        }
        if ($action=="ajouter_livrable_attendus"){
            for ($l=1;$l<=3;$l++) {
                $livrables[$l]->addBrief($brief);
                $brief->addLivrableAttendu($livrables[$l]);
                $em->persist($livrables[$l]);
            }
        }
        if ($action=="supprimer_livrable_attendus"){
            for($s=0;$s<count($livrables);$s++){
                $livrables[$s]->removeBrief($brief);
                $brief->removeLivrableAttendu($livrables[$s]);
                $em->persist($livrables[$s]);
            }
        }
        if ($action=="ajouter_tag"){
            for ($t=1;$t<=3;$t++) {
                $tag[$t]->addBrief($brief);
                $brief->addTag($tag[$t]);
                $em->persist($livrables[$t]);
            }
        }
        if ($action=="supprimer_tag"){
            for($a=0;$a<count($tag);$a++){
                $tag[$a]->removeBrief($brief);
                $brief->removeTag($tag[$a]);
                $em->persist($tag[$a]);
            }
        }
        if ($action=="deassigner"){
                $promo->removeBrief($brief);
                $brief->removePromo($promo);
                $em->persist($promo);
        }

        $em->persist($brief);
        $em->flush();
        return $this->json("success",200);
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
        $briefs = $promo->getBriefs()[$id1];
        $brief = $em->getRepository(Brief::class)->find($id1);
        $groupe = $this->em->getRepository(Groupes::class)->findAll();
        $reponse=json_decode($request->getContent(),true);

        $action=$reponse['action'];

        if($action=="desassigner_des_groupe"){
            for($o=0;$o<3;$o++){
                $groupe[$o]->removeBrief($brief);
                $brief->removeGroupe($groupe[$o]);
                $em->persist($groupe[$o]);
            }
        }
        if($action=="desassigner_un_groupe"){
            for($o=0;$o<1;$o++){
                $groupe[$o]->removeBrief($brief);
                $brief->removeGroupe($groupe[$o]);
                $em->persist($groupe[$o]);
            }
        }
        if($action=="desassigner_promo"){
            $promo->removeBrief($briefs);
            $briefs->removePromo($promo);
            $em->persist($promo);
            $em->persist($briefs);
        }
        if ($action=="assigner_promo"){
            $promo->addBrief($brief);
            $brief->addPromo($promo);
            $em->persist($promo);
        }
        if ($action=="assigner_un_groupe"){
            for($g=0;$g<1;$g++){
                $groupe[$g]->addBrief($brief);
                $brief->addGroupe($groupe[$g]);
                $em->persist($groupe[$g]);
            }
        }
        if ($action=="assigner_des_groupe"){
            for($g=0;$g<3;$g++){
                $groupe[$g]->addBrief($brief);
                $brief->addGroupe($groupe[$g]);
                $em->persist($groupe[$g]);
            }
        }
        $em->persist($brief);
        $em->flush();
        return $this->json("success",200);
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
        $fake = Factory::create('fr-FR');
        $group = $this->em->getRepository(Groupes::class)->findAll();
        $promo = $this->em->getRepository(Promotion::class)->findAll();
        $tag = $this->em->getRepository(Tag::class)->findAll();
        $livrables = $this->em->getRepository(LivrableAttendus::class)->findAll();
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
            $niveau=new Niveau();
            $niveau->setLibelle('niveau '.$j);
            $niveau->setCritereEvaluation('competentence '.$j.'critere_evaluation '.$j);
            $niveau->setGroupeAction('competentence '.$j.'groupe action '.$j);
            $niveau->setCompetence($competence[$j]);
            $niveau->setBrief($brief);
            $em->persist($niveau);
        }
        for($y=0;$y<2;$y++){
            $ressource=new Ressource();
            $ressource->setTitre('titre'.$y)
                ->setUrl('url'.$y)
                ->setBrief($brief);
            $em->persist($ressource);
        }
        for($s=1;$s<=3;$s++)
        {
            $liv=new LivrablePartiels();
            $liv->setLibelle('LivrablePartiel'.$s)
                ->setDescription('Description'.$s)
                ->setDateCreation(new \DateTime())
                ->setDelai(new \DateTime())
                ->setBrief($brief);

            $em->persist($liv);
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
