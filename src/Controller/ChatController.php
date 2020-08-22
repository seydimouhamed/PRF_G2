<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Controller\ChatController;
use App\Repository\ChatRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
// path="/api/users/promo/id/apprenant/id/chats",


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
        $content=$request->getContent();
        $chat= $serializer->deserialize($post, Chat::class, 'json');


        $em->persister();
        $em->flush();


        return $this->json("success",201);
    }
}
