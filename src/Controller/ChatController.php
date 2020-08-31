<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Controller\ChatController;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{

   
    /**
    * @Route(
    *     name="getcomment",
    *     path="/api/users/promo/{id}/user/{id2}/chats",
    *     methods={"GET"},
    *     defaults={
    *          "__controller"="App\Controller\ChatController::voirCommentaire",
    *          "__api_resource_class"=Chat::class,
    *          "__api_collection_operation_name"="get_chats"
    *     }
    * )
    */

    public function getChat(SerializerInterface $serializer,ChatRepository $chaRepo, PromotionRepository $promoRepo, int $id, int $id2, EntityManagerInterface $em)
    {
        
        $promo= $promoRepo->find($id);
        $apprenant=$em->getRepository(Apprenant::class)->find($id2);

        $groupe =$promo->getGroupes();
        $tabChat=[];
                
        if($promo){

            $result=$promoRepo->isApprenantInPromo($id,$id2);
            $user=$em->getRepository(User::class)->find($id2);
            $profilUser=$user->getProfil()->getLibelle();
             $tab_profil=["Administrateur","CM","Formateur"];
            if($result || in_array($profilUser,$tab_profil))
            {
                //$chats=$promo->getChats();
                $tabChat=$em->getRepository(Chat::class)->findBy(['promo'=>$promo]);
                return $this->json($tabChat, 200);
               // dd($tabChat);
            }

            return $this->json(["message"=>["type"=>"alert","contenu"=>"Acces au chat non autorisé" ]],400);
    
            
        }
        return $this->json(["message"=>["type"=>"alert","contenu"=>"cette promotion n'existe pas !!" ]],400);

    }

    /**
    * @Route(
    *     name="envoyerUncommentaire",
    *     path="/api/users/promo/{id}/user/{id2}/chats",
    *     methods={"POST"},
    *     defaults={
    *          "__controller"="App\Controller\ChatController::envoieCommentaire",
    *          "__api_resource_class"=Chat::class,
    *          "__api_collection_operation_name"="envoieComment"
    *     }
    * )
    */
    public function addChat(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, $id, $id2)
    {
         
        
        $promoRepo= $em->getRepository(Promotion::class);
        $promo=$promoRepo->find($id);
        if($promo)
        {
           // $groupe =$promo->getGroupes();
            $result=$promoRepo->isApprenantInPromo($id,$id2);
            $user=$em->getRepository(User::class)->find($id2);
            $profilUser=$user->getProfil()->getLibelle();
             $tab_profil=["Administrateur","CM","Formateur"];
            if($result || in_array($profilUser,$tab_profil))
            {
                $chat= new Chat();
                $pj = $request->files->get("piecesJoint");
                if($pj)
                {
                    $photoBlob = fopen($pj->getRealPath(),"rb");
                    $chat->setPiecesJointe($photoBlob);
                }
                $chat->setMessage($request->request->get('message'));
                $chat->setUser($user);
                $chat->setPromo($promo);
                $chat->setDate(new \DateTime());

                $em->persist($chat);
                $em->flush();
                return $this->json("succes", 200);
            }
                 
                return $this->json(["message"=>["type"=>"alert","contenu"=>"cet apprenant n'appartient pas a ce promo" ]],400);
 
        }
            return $this->json(["message"=>["type"=>"alert","contenu"=>"cette promotion n'existe pas !!" ]],400);

    }
}
