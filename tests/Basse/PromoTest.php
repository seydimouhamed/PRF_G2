<?php
declare(strict_types=1);

namespace App\Tests\Basse;

use App\Tests\GetClient;
use App\Tests\TraitToken;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class PromoTest extends WebTestCase
{
   // use TraitToken;
    

    public function atestAddPromo()
     {
    //     $client = self::createClient();
    //    $token=$this->getToken();
    //     $client->setServerParameter('HTTP_Authorization',$token);


    $getClient=new GetClient();
    $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $data= ["username"=> "testUserName",
        "fisrtName"=> "testFN1",
        "lastName"=> "testLN1",
        "email"=>"test1@yahoo.com",
         "password"=>"passer",
        "plainPassword"=>"passer",
        "profil"=>"/api/admin/profils/1"];
        $client->request('POST','/api/admin/promos'
        [],
        [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode($data));

        $this->assertResponseIsSuccessful();
        //$this->assertSame(201,$client->getResponse()->getStatusCode());
    }        


    public function testGetPromoRefCompForm()
    {


        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");

        $client->request('GET','/api/formateurs/promo/1/referentiel/1/competences');

        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoRefCompApp()
    {


        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");

        $client->request('GET','/api/apprenants/14/promo/1/referentiel/1/competences');

        $this->assertResponseIsSuccessful();

    }

    public function testGetPromos()
    {


        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");

        $client->request('GET','/api/admin/promos');

        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoPrincipals()
    {


        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");

        $client->request('GET','/api/admin/promos/principal');

        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoApprenantAttente()
    {
        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/promos/apprenants/attente');
        $this->assertResponseIsSuccessful();

    }
    public function testGetPromoIDApprenantAttente()
    {
        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/promos/1/apprenants/attente');
        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoIdPrincipal()
    {
        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/promos/1/principal');
        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoIdReferentiel()
    {
        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/promos/1/referentiel');
        $this->assertResponseIsSuccessful();

    }

    public function testGetPromoIdFormateur()
    {
        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/promos/1/formateur');
        $this->assertResponseIsSuccessful();

    }
    //------------------------------------
    //       TESTS DES PUTS
    //------------------------------------
   //    /api/admin/promos/{id}/groupes/{id2}


    public function testPutPromoIdGroupe()
    {
        $getClient=new GetClient();
        $data['statut']="cloture";
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('PUT','/api/admin/promos/1/groupes/3',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
                );
        $this->assertResponseIsSuccessful();

    }


    // /api/admin/promo/{id}/apprenants

    public function atestPutPromoIdApprenants()
    {
        $getClient=new GetClient();
        $data=[
            "add"=>[],
            "remove"=>[13]
            ];

        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('PUT','/api/admin/promos/1/apprenants',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
                );
        $this->assertResponseIsSuccessful();

    }
    // /api/admin/promo/{id}/formateurs

    public function atestPutPromoIdFormateurs()
    {
        $getClient=new GetClient();
        $data=[
            "add"=>[4],
            "remove"=>[6]
            ];

        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('PUT','/api/admin/promos/1/formateurs',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
                );
        $this->assertResponseIsSuccessful();

    }
}