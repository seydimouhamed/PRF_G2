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
        $user = $request->getContent();
         
        //recupération de l'image
        $photo = $request->files->get("photo");
        
        $user = $this->serializer->serialize($user,User::class,'json');
        if(null!==$photo)
        {
            // $imagedata = file_get_contents($avatar);
            //$base64 = base64_decode($imagedata);
            $photo = fopen($photo->getRealPath(),"rb");
            $user->setPhoto($photo);
           // $user['photo'] = $photo;
        }
        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        fclose($photo);
        return $this->json($user,Response::HTTP_CREATED);
    }
}
