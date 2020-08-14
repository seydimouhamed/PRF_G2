<?php

namespace App\Controller;

use App\Entity\User;
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
        $promo= $request->getContent();
         
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
       // $em->flush();
        
        return $this->json($promo,201);
     }


    // /**
    //  * @Route(
    //  *     name="getUser",
    //  *     path="/api/admin/users",
    //  *     methods={"GET"},
    //  *     defaults={
    //  *          "__controller"="App\Controller\UserController::getUsers",
    //  *          "__api_resource_class"=User::class,
    //  *          "__api_collection_operation_name"="get_user"
    //  *     }
    //  * )
    //  */
    // public function getUsers(UserRepository $repo)
    // {
    //     $user= $repo->findByArchivage(0);
    //     // $user=$this->serializer->serialize($user,"json");
    //     return $this->json($user,200);
    // }
    // /**
    //  * @Route(
    //  *     name="archive_user",
    //  *     path="/api/admin/users/{id}",
    //  *     methods={"DELETE"},
    //  *     defaults={
    //  *          "__controller"="App\Controller\UserController::archiveUser",
    //  *          "__api_resource_class"=User::class,
    //  *          "__api_collection_operation_name"="archive_user"
    //  *     }
    //  * )
    //  */
    // public function archiveUser(UserRepository $repo,$id)
    // {
    //     $user=$repo->find($id)
    //               ->setArchivage(1);
    //     $this->em->persist($user);
    //     $this->em->flush();
    //     // $user=$this->serializer->serialize($user,"json");
    //     return $this->json(true,200);
    // }
}
