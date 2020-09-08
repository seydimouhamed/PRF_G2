<?php
declare(strict_types=1);

namespace App\Tests\Basse;

use App\Tests\GetClient;
use App\Tests\TraitToken;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class UserTest extends WebTestCase 
{
    use TraitToken;
    public function testGetUsers()
    {
    //     $client = self::createClient();
    //    $token=$this->getToken();
    //     $client->setServerParameter('HTTP_Authorization',$token);

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('GET','/api/admin/users');

        $this->assertSame(200,$client->getResponse()->getStatusCode());
    }

    public function testAdd()
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
        $client->request('POST','/api/admin/users',$data);

        $this->assertResponseIsSuccessful();
        //$this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    public function testArchivage()
    {
    //     $client = self::createClient();
    //    $token=$this->getToken();
    //     $client->setServerParameter('HTTP_Authorization',$token);

    $getClient=new GetClient();
    $client = $getClient->createAuthenticatedClient("admin1","passe123");
        $client->request('DELETE','/api/admin/users/2');

        $this->assertResponseIsSuccessful();

    }
}