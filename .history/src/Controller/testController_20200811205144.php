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
use App\Repository\GroupesRepository;
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

class TestController extends AbstractController
{


    private $serializer;
    private $validator;
    private $em;
    private $repo;
    private $repoGroupe;

    public function __construct(
        PromotionRepository $repo,
        GroupesRepository $repoGroupe,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
)
    {
        $this->repo=$repo;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->repoGroupe=$repoGroupe;
        $this->em=$em;
        $this->encoder=$encoder;
    }
    /**
     * @Route(
     *     name="get_test",
     *     path="/api/test",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::addtest",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="addtes"
     *     }
     * )
     */
    public function addtest(Request $request)
    {
        //recupéré tout les données de la requete
       // $promo=json_decode($request->getContent(),true);
         
        //recupération  recupération imga promo!
        @$doc = $request->files->get("document");

        $file = fopen($doc, "r");

        //Output lines until EOF is reached
        while(! feof($file)) {
        $line = fgets($file);
        echo $line. "<br>";
        }

        fclose($file);
       // $content=iconv('latin5', 'utf-8',file_get_contents($doc));
        //$content=fread($content,file)
        // $promo = $this->serializer->denormalize($promo,"App\Entity\Promotion",true);
        // if($avatar)
        // {
        //      //$avatarBlob = fopen($avatar->getRealPath(),"rb");
        //     // $promo->setAvatar($avatarBlob);
        // }
    //     if(!$promo->getFabrique())
    //     {
    //         $promo->setFabrique("Sonatel académie");
    //     }

    //     $errors = $this->validator->validate($promo);
    //     if (count($errors)){
    //         $errors = $this->serializer->serialize($errors,"json");
    //         return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
    //     }
    //   //$promo->setArchivage(false);

    //     $em = $this->getDoctrine()->getManager();
    //     $em->persist($promo);
    //    $em->flush();
    //    //creation dun groupe pour la promo
       
    //    $group= new Groupes();
    //   // $date = date('Y-m-d');
    //    $group->setNom('Groupe Générale')
    //          ->setDateCreation(new \DateTime())
    //          ->setStatut('ouvert')
    //          ->setType('groupe principale')
    //          ->setPromotion($promo);
    //          $em->persist($group);
    //         $em->flush();
        
        return $this->json($content,201);
     }




}

