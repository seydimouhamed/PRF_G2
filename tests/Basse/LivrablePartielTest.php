<?php
namespace App\Tests\Basse;

use App\Tests\GetClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class LivrablePartielTest  extends WebTestCase
{
    
    // protected function createAuthenticatedClient(string $login, string $password): KernelBrowser
    // {
    //     $client = static::createClient();
    //     $infos=["username"=>$login,
    //            "password"=>$password];
    //     $client->request(
    //         'POST',
    //         '/api/login',
    //         [],
    //         [],
    //         ['CONTENT_TYPE' => 'application/json'],
    //         json_encode($infos)
    //     );
    //     $this->assertResponsestatusCodeSame(Response::HTTP_OK);
    //     $data = \json_decode($client->getResponse()->getContent(), true);
    //     $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
    //     $client->setServerParameter('CONTENT_TYPE', 'application/json');

    //     return $client;
    // }

    public function testGetAppProRefCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("apprenant1","passe123");
        $client->request('GET', '/api/apprenants/14/promo/1/referentiel/1/competences');
        $this->assertResponseIsSuccessful();
    }

    public function testGetAppProRefStaBri()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("apprenant1","passe123");
        $client->request('GET', '/api/apprenants/14/promo/1/referentiel/1/statistiques/briefs');
        $this->assertResponseIsSuccessful();
    }

    public function testGetForProRefStaCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("formateur1","passe123");
        $client->request('GET', '/api/formateurs/promo/1/referentiel/1/statistiques/competences');
        $this->assertResponseIsSuccessful();
    }

    public function testGetForLivCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("formateur1","passe123");
        $client->request('GET', '/api/formateurs/livrablepartiels/1/commentaires');
        $this->assertResponseIsSuccessful();
    }
    public function testGetAppLivCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("apprenant1","passe123");
        $client->request('GET', '/api/apprenants/livrablepartiels/1/commentaires');
        $this->assertResponseIsSuccessful();
    }
    public function atestAddAppLivCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("apprenant1","passe123");
        $data['description']="commentaire provenant des tests";
            $client->request(
                'POST',
                '/api/apprenants/livrablepartiels/1/commentaires',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                '{
                    "description": "commentaire provenant apprenants tests"
                }'
            );

        $this->assertResponseIsSuccessful();
    }
    public function atestAddFormLivCom()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("formateur1","passe123");
        $data['description']="commentaire provenant des tests";
            $client->request(
                'POST',
                '/api/formateurs/livrablepartiels/1/commentaires',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                '{
                    "description": "commentaire provenant formateur tests",
                    "idformateur": 4
                }'
            );
        $this->assertResponseIsSuccessful();
    }


    public function testPutAppLiv()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("apprenant10","passe123");
            $client->request(
                'PUT',
                '/api/apprenants/10/livrablepartiels/2',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                '{                    
                    "statut":"valide"
                }'
            );
        $this->assertResponseIsSuccessful();
    }


    public function atestPutForProBriLiv()
    {

        $getClient=new GetClient();
        $client = $getClient->createAuthenticatedClient("formateur1","passe123");
        $data=[
            "libelle"=>"maquette figma",
            "delai"=>"2014-11-23",
             "etat"=>"assigne",
             "description"=>"description du livrablee partiel",
             "type"=>"groupe",
             "nbreRendu"=>30,
             "nbreCorriger"=>20,
             "niveau"=>["api/niveaux/1","api/niveaux/7","api/niveaux/14"],
             "apprenants"=>["api/apprenants/12","api/apprenants/13","api/apprenants/14","api/apprenants/12"]
            ];
        
        
            $client->request(
                'PUT',
                '/api/formateurs/promo/1/brief/3/livrablepartiels',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode($data)
            );
            
        $this->assertResponseIsSuccessful();
    }


    
    // public function testCreateProfil()
    // {
    //     $client = $this->createAuthenticatedClient("admin1","pass_1234");
    //     $client->request(
    //         'POST',
    //         '/api/profils',
    //         [],
    //         [],
    //         ['CONTENT_TYPE' => 'application/json'],
    //         '{
    //             "libelle": "ADMIN1388"
    //         }'
    //     );
    //     $responseContent = $client->getResponse();
    //     //$responseDecode = json_decode($responseContent);
    //     $this->assertEquals(Response::HTTP_OK,$responseContent->getStatusCode());
    //     //$this->assertJson($responseContent);
    //     //$this->assertNotEmpty($responseDecode);
    
    
    // }


}