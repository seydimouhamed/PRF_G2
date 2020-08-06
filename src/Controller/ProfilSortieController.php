<?php

namespace App\Controller;

use App\Entity\ProfilSorti;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortiRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilSortieController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }

       /**
     * @Route(
     *     name="delete_profilSorti",
     *     path="/api/admin/profilSorti/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::putOneProfilSorti",
     *          "__api_resource_class"=ProfilSorti::class,
     *          "__api_collection_operation_name"="put_one_profilSort"
     *     }
     * )
     */

    public function archiveProfil(ProfilSortiRepository $repo,$id)
    {
        $profil=$repo->find($id)
                  ->setArchivage(1);
        $this->em->persist($profil);
        $this->em->flush();
        return $this->json(true,200);
    }
}