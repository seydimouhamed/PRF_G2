<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Repository\ProfilRepository;
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
    public function add(Request $request,EntityManagerInterface $em)
    {
        //recupéré tout les données de la requete
        $user = $request->request->all();

        //recupération de l'image
        $avatar = $request->files->get("photo");
        // $imagedata = file_get_contents($avatar);
          //$base64 = base64_decode($imagedata);
        $avatar = fopen($avatar->getRealPath(),"rb");
        $user['photo'] = $avatar;
        $user = $this->serializer->denormalize($user,"App\Entity\User");
        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        $em->persist($user);
        $em->flush();
        //fclose($avatar);
        return $this->json($user,Response::HTTP_CREATED);
    }
}
