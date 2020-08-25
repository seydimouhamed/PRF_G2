<?php

namespace App\Controller;

use App\Entity\GroupeTag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupeTagController extends AbstractController
{
    /**
     * @Route(
     *     name="get_taggrp_groupetags",
     *     path="/api/admin/grptags/{id}/tags",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupeTagController::Tagegrpptag",
     *          "__api_resource_class"=GroupeTag::class,
     *          "_api_item_operation_name"="get_taggrp_groupetags"
     *     }
     * )
     */
    public function Tagegrpptag(EntityManagerInterface $entityManager,int $id)
    {
        $groupeTag = $entityManager->getRepository(GroupeTag::class)->find($id);

        return $this->json($groupeTag ,200);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 0b1883a6ad45fb46ede9530cbc7901f1f89668e4
