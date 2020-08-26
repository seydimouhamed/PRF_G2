<?php

namespace App\Controller;

use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
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



    public function __construct(
)
    {
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

