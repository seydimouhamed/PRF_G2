<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ProfilSortie;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use App\Repository\ApprenantRepository;
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
    private $apprenantRepo;

    public function __construct(
        GroupesRepository $groupeRepo,
        ApprenantRepository $apprenantRepo,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
        $this->groupeRepo=$groupeRepo;
        $this->apprenantRepo=$apprenantRepo;
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
                        $apprenant_details['apprenants'][]=$apprenant->getFisrtName()." ".$apprenant->getLastName();
                    }
                    $appreanant[]=$apprenant_details;
            }

        return $this->json($appreanant,201);
    }

    /**
     * @Route(
     *     name="putGroupeApprenants",
     *     path="api/admin/groupes/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::putGroupeApprenant",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="put_appreanant_groupe"
     *     }
     * )
     */
    public function putGroupeApprenant(Request $request,$id)
    { 
        $idApprenant=json_decode($request->getContent(),true);

        $objetApprenant=$this->apprenantRepo->find($idApprenant);

        if($objetApprenant)
        {
            $objtGroupe=$this->groupeRepo->find($id);
            if($objtGroupe)
            {
                if($objtGroupe->getType()!=='groupe principale')
                {
                    $objtGroupe->addApprenant($objetApprenant);

                    $this->em->persist($objtGroupe);
                    $this->em->flush();

                    return $this->json("success",201);
                }
                return $this->json("changement de promo/ groupe principale impossibles",201);
            }
            return $this->json("ce groupe n'existe pas",401);
        }

        return $this->json("cet apprenant n'existe pas",401);
    }


    /**
     * @Route(
     *     name="deleteGroupeApprenants",
     *     path="api/admin/groupes/{id}/{apprenant}",
     *     methods={"DELETE"},
     *     defaults={
     *          "__controller"="App\Controller\ProfilSortieController::deleteGroupeApprenant",
     *          "__api_resource_class"=ProfilSortie::class,
     *          "__api_collection_operation_name"="delete_appreanant_groupe"
     *     }
     * )
     */
    public function deleteGroupeApprenant(Request $request,$id)
    { 
        $idApprenant=json_decode($request->getContent(),true);

        $objetApprenant=$this->apprenantRepo->find($idApprenant);

        if($objetApprenant)
        {
            $objtGroupe=$this->groupeRepo->find($id);
            if($objtGroupe)
            {
                if($objtGroupe->getType()!=='groupe principale')
                {
                    $objtGroupe->addApprenant($objetApprenant);

                    $this->em->persist($objtGroupe);
                    $this->em->flush();

                    return $this->json("success",201);
                }
                return $this->json("changement de promo/ groupe principale impossibles",201);
            }
            return $this->json("ce groupe n'existe pas",401);
        }

        return $this->json("cet apprenant n'existe pas",401);
    }

}
