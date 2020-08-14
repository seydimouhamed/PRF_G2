<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilSortie;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GroupeController extends AbstractController
{
    private $serializer;
    private $validator;
    private $em;
    private $groupeRepo;

    public function __construct(
        GroupesRepository $groupeRepo,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
        $this->groupeRepo=$groupeRepo;
    }


    /**
     * @Route(
     *     name="getGroupeApprenant",
     *     path="api/admin/groupes/apprenants",
     *     methods={"GET"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::getGroupeApprenant",
     *          "__api_resource_class"=ProfilSortie::class,
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
                        $apprenant_details['apprenants'][]=$apprenant->getFirstName()." ".$apprenant->getLastName();
                    }
            }

        return $this->json($groupes,201);
    }

}
