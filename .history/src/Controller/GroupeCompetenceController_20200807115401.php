<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GroupeCompetenceController extends AbstractController
{
    private $serializer;
    private $em;

    public function __construct(
        SerializerInterface $serializer,
        GroupeCompetenceRepository $repo,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->em=$em;
    }
    /**
     * @Route(
     *     name="get_gc",
     *     path="/api/admin/grpecompetence/competence",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeCompetenceController::add",
     *          "__api_resource_class"=GroupeCompetence::class,
     *          "__api_collection_operation_name"="get_grpe_c"
     *     }
     * )
     */
    public function getGpreComp(Request $request)
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
        
        $errors = $this->validator->validate($promo);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
      //$user->setArchivage(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($promo);
       $em->flush();
        
        return $this->json($promo,201);
     }


}
