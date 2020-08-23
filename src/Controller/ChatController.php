<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Apprenant;
use App\Controller\ChatController;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{

   /* private $request;


    public function __construct(
        Request $request
    ){
        $this->request= $request;
    }*/

    /**
    * @Route(
    *     name="getcomment",
    *     path="/api/users/promo/{id}/apprenant/{id2}/chats",
    *     methods={"GET"},
    *     defaults={
    *          "__controller"="App\Controller\ChatController::voirCommentaire",
    *          "__api_resource_class"=Chat::class,
    *          "__api_collection_operation_name"="getcommentaire"
    *     }
    * )
    */

    public function getChat(ChatRepository $chaRepo, PromotionRepository $promoRepo, int $id, int $id2, EntityManagerInterface $em)
    {
        
        $chat= $chaRepo->findAll();
        $promo= $promoRepo->find($id);
        $apprenant=$em->getRepository(Apprenant::class)->find($id2);

        $groupe =$promo->getGroupes();
        $tabChat=[];
                
        if(isset($promo)){
            foreach ($groupe as $valu){
                $apprenants=$valu->getApprenants();
    
                foreach ($apprenants as $value) {
                    if($value==$apprenant){
                        $tabChat[]=$value->getChats();
    
                        return $this->json($tabChat, 200);
                    }
                }
                return $this->json("cet apprenant n'appartient pas a ce promo" ,400);
            }
        }
       return $this->json("cette promotion n'existe pas !!",400);



         //$tabChat=[];
        /*for ($i=0; $i < count($promo->getGroupes()); $i++) { 
            
            for ($j=0; $j < count($promo->getGroupes()[$i]->getApprenants()) ; $j++) { 


                    $tabChat[]=$promo->getGroupes()[$i]->getApprenants($id2)[$j]->getChats($chat);

                //$tab[]=['apprenant'] ;
            }
            
        }

        return $this->json($tabChat,200);*/
       
    }

    /**
    * @Route(
    *     name="envoyerUncommentaire",
    *     path="/api/users/promo/{id}/apprenant/{id2}/chats",
    *     methods={"POST"},
    *     defaults={
    *          "__controller"="App\Controller\ChatController::envoieCommentaire",
    *          "__api_resource_class"=Chat::class,
    *          "__api_collection_operation_name"="envoieComment"
    *     }
    * )
    */
    public function addChat(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $content= $request->request->all();
        $chat= $serializer->denormalize($content,"App\Entity\Chat",true);
        
        $pj = $request->files->get("pieceJointes");
        if(!$pj)
        {
            
            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
        }
            $photoBlob = fopen($pj->getRealPath(),"rb");
            
             $chat->setPieceJointes($photoBlob);


        $em->persist($chat);
        $em->flush();

        return $this->json($chat,201);


       /* $promo= $promoRepo->find($id);
        $apprenant=$em->getRepository(Apprenant::class)->find($id2);

        $groupe =$promo->getGroupes();
        $tabChat=[];
                
        if(isset($promo)){
            foreach ($groupe as $valu){
                $apprenants=$valu->getApprenants();
    
                foreach ($apprenants as $value) {
                    if($value==$apprenant){
                        $value->addChat();



                        $em->persister();
                        $em->flush();
                

    
                        return $this->json("succes", 200);
                    }
                }
                return $this->json("cet apprenant n'appartient pas a ce promo" ,400);
            }
        }
       return $this->json("cette promotion n'existe pas !!",400);
       */

            
        //return $this->json("success",201);
    }
}
