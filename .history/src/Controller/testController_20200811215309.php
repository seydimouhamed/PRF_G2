<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Groupes;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\GroupesRepository;
use App\Repository\PromotionRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\Request;
use ContainerTqjcrpd\getUserRepositoryService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestController extends AbstractController
{


    private $serializer;
    private $validator;
    private $em;
    private $repo;
    private $repoGroupe;

    public function __construct(
        PromotionRepository $repo,
        GroupesRepository $repoGroupe,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
)
    {
        $this->repo=$repo;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->repoGroupe=$repoGroupe;
        $this->em=$em;
        $this->encoder=$encoder;
    }
    /**
     * @Route(
     *     name="get_test",
     *     path="/api/test",
     *     methods={"POST"},
     *     defaults={
     *          "__controller"="App\Controller\PromotionController::addtest",
     *          "__api_resource_class"=Promotion::class,
     *          "__api_collection_operation_name"="addtes"
     *     }
     * )
     */
    public function addtest(Request $request)
    {

        $doc = $request->files->get("document");

        $file= IOFactory::identify($doc);
        
        $reader= IOFactory::createReader($file);

        $spreadsheet=$reader->load($doc);
        
        $content= $spreadsheet->getActivesheet()->toArray();
        
        return $this->json($content,201);
    }




}

