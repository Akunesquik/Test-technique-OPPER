<?php

namespace App\Tests\Controller;

use App\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionControllerTest extends WebTestCase
{
    public function testCreateSubscription(): void
    {
        $client = static::createClient();

        $data = [
            'contact' => 1, // ID du contact
            'product' => 1, // ID du produit
            'beginDate' => '2025-02-23',
            'endDate' => '2025-03-23'
        ];
        
        $client->request('POST', '/subscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        
        // Vérification si la réponse JSON contient bien ces données
        $this->assertJson(json_encode([
            '"contact":[{"id":1,"name":"John","firstname":"Doe"}]'
        ]));
        
        
    }

    public function testCreateSubscriptionMissingData(): void
    {
        $client = static::createClient();

        $data = [
            'contact' => 1,
            'product' => 1
            // Données manquantes : beginDate, endDate
        ];

        $client->request('POST', '/subscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Données manquantes.']),
            $client->getResponse()->getContent()
        );
    }

    public function testShowSubscription(): void
    {
        $client = static::createClient();

        $id = 1;
        $client->request('GET', "/subscription/{$id}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson(
            json_encode([
                'contact' => [
                    ['id' => 1, 'name' => 'John', 'firstname' => 'Doe']
                ]
            ]),
            $client->getResponse()->getContent()
        );
    }

    public function testShowSubscriptionNotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', '/subscription/9999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Aucune souscription trouvée pour ce contact.']),
            $client->getResponse()->getContent()
        );
    }

    public function testEditSubscription(): void
    {
        $client = static::createClient();
        $id = 19;
    
        $data = [
            'contact' => 1, // ID du contact à mettre à jour
            'product' => 2, // ID du produit à mettre à jour
            'beginDate' => '2025-03-01',
            'endDate' => '2025-04-01'
        ];

        $client->request('PUT', "/subscription/{$id}", [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson(
            json_encode(json_encode([
                'contact' => [
                    ['id' => 1, 'name' => 'John', 'firstname' => 'Doe']
                ]
            ])),
            $client->getResponse()->getContent()
        );
        $this->assertJson(
            json_encode(json_encode([
                'product' => [
                    ['id' => 2, 'label' => 'Poires']
                ]
            ])),
            $client->getResponse()->getContent()
        );
    }

    public function testEditSubscriptionNotFound(): void
    {
        $client = static::createClient();

        $data = [
            'contact' => 2,
            'product' => 2
        ];

        $client->request('PUT', '/subscription/9999', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Souscription non trouvée.']),
            $client->getResponse()->getContent()
        );
    }

    public function testDeleteSubscription(): void
    {
        $client = static::createClient();

        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $subscriptionRepository = $entityManager->getRepository(Subscription::class);

        $subscription = $subscriptionRepository->findOneBy([], ['id' => 'DESC']); 

        if ($subscription) {
            $client->request('DELETE', '/subscription/' . $subscription->getId());
            
            $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        } else {
            $this->fail('Aucune souscription trouvée pour supprimer');
        }
    }


    public function testDeleteSubscriptionNotFound(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/subscription/9999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Souscription non trouvée.']),
            $client->getResponse()->getContent()
        );
    }
}
