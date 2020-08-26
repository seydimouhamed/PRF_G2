<?php

namespace App\Controller;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    private $serializer;
    private $validator;
    private $em;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
    }


    /**
     * @Route(
     *     name="getG_profils",
     *     path="api/admin/profils",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\GroupesController::getGroupeApprenant",
     *          "__api_resource_class"=Groupes::class,
     *          "__api_collection_operation_name"="get_appreanant_groupe"
     *     }
     * )
     */
    public function getGroupeApprenant()
    {
        $groupes=$this->groupeRepo->findAll();

        $appreanant=[];
            foreach($groupes as $groupe)
            {
                    $apprenant_details['groupe']="groupe ".$groupe->getID();
                    $apprenant_details['apprenants']=[];
                    foreach($groupe->getApprenants() as $apprenant)
                    {
                        $apprenant_details['apprenants'][]=$apprenant->getFisrtName()." ".$apprenant->getLastName();
                    }
                    $appreanant[]=$apprenant_details;
            }

        return $this->json($appreanant,201);
    }

}
