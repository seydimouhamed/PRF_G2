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

class UserController extends AbstractController
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
     *     name="addUser",
     *     path="/api/addUser",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::add",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="add_user"
     *     }
     * )
     */
    public function add(Request $request)
    {
        //recupéré tout les données de la requete
        $user = $request->request->all();
         
        //recupération de l'image
        $photo = $request->files->get("photo");
        
        $user = $this->serializer->denormalize($user,"App\Entity\User",true);
        if(!$photo)
        {
            
            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
        }
            //$base64 = base64_decode($imagedata);
            $photoBlob = fopen($photo->getRealPath(),"rb");
            
             $user->setPhoto($photoBlob);

                // // PREVISUALISATION DE L'IMAGE
                //     $file = file_get_contents($photo);
                //     $avatar = base64_encode($file);
                //     $type = $photo->getMimeType();
                //     // fclose($file);
                //     echo "<img src='data:$type;base64,$avatar' />";
                // // PREVISUALISATION DE L'IMAGE
        
        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $user->getPassword();
       $user->setPassword($this->encoder->encodePassword($user,$password));
       $user->setArchivage(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return $this->json("success",201);
    }
    /**
     * @Route(
     *     name="getUser",
     *     path="/api/admin/users",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::getUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="get_user"
     *     }
     * )
     */
    public function getUser(UserRepository $repo)
    {
        $user= $repo->findByArchivage(0);
        // $user=$this->serializer->serialize($user,"json");
        return $this->json($user,200);
    }
    /**
     * @Route(
     *     name="archive_user",
     *     path="/api/admin/users/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\UserController::archiveUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="archive_user"
     *     }
     * )
     */
    public function archiveUser(UserRepository $repo,$id)
    {
        $user=$repo->find($id)
                  ->setArchivage(1);
        $this->em->persist($user)->flush();
        // $user=$this->serializer->serialize($user,"json");
        return $this->json(true,200);
    }
}
