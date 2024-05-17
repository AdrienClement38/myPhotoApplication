<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagTest extends WebTestCase
{
//    public function testSomething(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
//    }

    public function testGetTags(): void
    {
        $client = static::createClient();

        // Remplacez 'VotreTokenJWT' par votre véritable token JWT
        $client->request('GET', '/api/tagsget', [], [], ['HTTP_Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTU5NDE5NzYsImV4cCI6MTcxNTk0NTU3Niwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfQVBJIiwiUk9MRV9VU0VSLCBST0xFX0NVU1RPTUVSIl0sInVzZXJuYW1lIjoiYWRkZEBhZC5jb20ifQ.dDFGyPy2_VWaXhRV7GBHt3_b3ogmuB9D5_YDgDnOVw1Jk6iwzkE6UL4QGsGL-e8tQesFZLK4HEwpYTUudT7jDThz_SIdlLQWhPoUNFzatLfCb0J-0LeZ0XzALCsrZjfSI2KUm_BN8SCV0eVY92XbG2l4VRGRYfVOtyBSiE51USV77ckZghuAK4Q_5jP42fHgkCQAGzPjkyEF1tT15WiWxxiQbI4RSZNhdJk5-jqEMJf2GmAslWjaM-U7wFwd-VOhoFTyogP6Xixf_mu43xfCDB3eRy38ZMd682UiO5j1a2VI69pgZkIxQDWOkc2-JVTisRkN4IJuua_2yx1dDnSgZw']);

        $this->assertResponseIsSuccessful();
        // Vous pouvez ajouter d'autres assertions ici pour vérifier le contenu de la réponse

        // Récupérer le contenu de la réponse
        $content = $client->getResponse()->getContent();

        // Afficher le contenu de la réponse
        echo $content;
    }
}
