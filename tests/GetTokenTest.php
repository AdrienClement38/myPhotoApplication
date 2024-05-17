<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetTokenTest extends WebTestCase
{
//    public function testSomething(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
//    }


    public function testGetToken(): void
    {
        $client = static::createClient();

        // Remplacez 'username' et 'password' par vos véritables informations d'identification
        $client->request('POST', '/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'username' => 'addd@ad.com',
            'password' => '123456789',
        ]));

        $this->assertResponseIsSuccessful();

        // Récupérer le contenu de la réponse
        $content = $client->getResponse()->getContent();

        // Afficher le contenu de la réponse
        echo $content;
    }
}
