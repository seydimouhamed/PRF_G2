<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateursController extends AbstractController
{
    private $em;

    public function __construct(
        EntityManagerInterface $em)
    {
        $this->em=$em;
    }
        /**
     * @Route(
     *     name="archive_user",
     *     path="/api/admin/users/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\UtilisateursController::archiveUser",
     *          "__api_resource_class"=Utilsateurs::class,
     *          "__api_collection_operation_name"="archive_user"
     *     }
     * )
     */
    public function archiveUser(UtilisateursRepository $repo,$id)
    {
        $user=$repo->find($id)
                  ->setArchivage(1);
        $this->em->persist($user);
        $this->em->flush();
        // $user=$this->serializer->serialize($user,"json");
        return $this->json(true,200);
    }
}
