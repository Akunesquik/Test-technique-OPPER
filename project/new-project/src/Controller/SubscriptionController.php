<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\Contact;
use App\Entity\Product;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/subscription')]
final class SubscriptionController extends AbstractController
{
    #[Route('', name: 'app_subscription_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['contact'], $data['product'], $data['beginDate'], $data['endDate'])) {
            return $this->json(['message' => 'Données manquantes.'], Response::HTTP_BAD_REQUEST);
        }

        $subscription = new Subscription();
        $subscription->setContact($entityManager->getRepository(Contact::class)->find($data['contact']));
        $subscription->setProduct($entityManager->getRepository(Product::class)->find($data['product']));
        $subscription->setBeginDate(new \DateTime($data['beginDate']));
        $subscription->setEndDate(new \DateTime($data['endDate']));

        $entityManager->persist($subscription);
        $entityManager->flush();

        return $this->json($subscription, Response::HTTP_CREATED);
    }

    #[Route('/{idContact}', name: 'app_subscription_show', methods: ['GET'])]
    public function show(int $idContact, SubscriptionRepository $subscriptionRepository): Response
    {
        $subscriptions = $subscriptionRepository->findByContactId($idContact);

        if (!$subscriptions) {
            return $this->json(['message' => 'Aucune souscription trouvée pour ce contact.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($subscriptions, Response::HTTP_OK);
    }

    #[Route('/{idSubscription}', name: 'app_subscription_edit', methods: ['PUT'])]
    public function edit(Request $request, int $idSubscription, EntityManagerInterface $entityManager): Response
    {
        $subscription = $entityManager->getRepository(Subscription::class)->find($idSubscription);

        if (!$subscription) {
            return $this->json(['message' => 'Souscription non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['contact'])) {
            $subscription->setContact($entityManager->getRepository(Contact::class)->find($data['contact']));
        }

        if (isset($data['product'])) {
            $subscription->setProduct($entityManager->getRepository(Product::class)->find($data['product']));
        }

        if (isset($data['beginDate'])) {
            $subscription->setBeginDate(new \DateTime($data['beginDate']));
        }

        if (isset($data['endDate'])) {
            $subscription->setEndDate(new \DateTime($data['endDate']));
        }

        $entityManager->flush();

        return $this->json($subscription, Response::HTTP_OK);
    }

    #[Route('/{idSubscription}', name: 'app_subscription_delete', methods: ['DELETE'])]
    public function delete(int $idSubscription, EntityManagerInterface $entityManager): Response
    {
        $subscription = $entityManager->getRepository(Subscription::class)->find($idSubscription);

        if (!$subscription) {
            return $this->json(['message' => 'Souscription non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($subscription);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}
