<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhotoTest extends WebTestCase
{
//    public function testSomething(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
//    }

    public function testGetPhotos(): void
    {
        $client = static::createClient();

        // Remplacez 'VotreTokenJWT' par votre véritable token JWT
        $client->request('GET', '/api/photosget', [], [], ['HTTP_Authorization' => 'Bearer VotreTokenJWT']);

        $this->assertResponseIsSuccessful();

        // Récupérer le contenu de la réponse
        $content = $client->getResponse()->getContent();

        // Afficher le contenu de la réponse
        echo $content;
    }

    public function testCreatePhoto(): void
    {
        $client = static::createClient();

        // Remplacez 'VotreTokenJWT' par votre véritable token JWT
        // Remplacez 'photoData' par les données de votre photo au format JSON
        $client->request('POST', '/api/photoscreate', [], [], ['HTTP_Authorization' => 'Bearer VotreTokenJWT', 'CONTENT_TYPE' => 'application/json'], 'photoData');

        $this->assertResponseIsSuccessful();

        // Récupérer le contenu de la réponse
        $content = $client->getResponse()->getContent();

        // Afficher le contenu de la réponse
        echo $content;
    }
}
