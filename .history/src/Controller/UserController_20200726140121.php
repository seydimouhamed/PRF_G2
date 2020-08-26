<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $encoder;
    private $serializer;
    private $validator;

    public function __construct(UserPasswordEncoderInterface $encoder,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $this->encoder=$encoder;
        $this->serializer=$serializer;
        $this->validator=$validator;
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
            //$base64 = base64_decode($imagedata);
           // $photo = fopen($photo->getRealPath(),"rb");
            // $user->setPhoto($photo);

            $file = file_get_contents($photo);
            $avatar = base64_decode($file);
             $type = $uploadedFile->getMimeType();
            echo "<img src='$type;base64,$avatar'>";
            fclose($file);
        }
        // $errors = $this->validator->validate($user);
        // if (count($errors)){
        //     $errors = $this->serializer->serialize($errors,"json");
        //     return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        // }
        //$password = $user->getPassword();
       // $user->setPassword($this->encoder->encodePassword($user,$password));

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($user);
        // $em->flush();
        
        //return $this->json("success",201);
    }
}
