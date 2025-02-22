<?php

namespace App\Tests\Controller;

use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SubscriptionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $subscriptionRepository;
    private string $path = '/subscription/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->subscriptionRepository = $this->manager->getRepository(Subscription::class);

        foreach ($this->subscriptionRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Subscription index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'subscription[beginDate]' => 'Testing',
            'subscription[endDate]' => 'Testing',
            'subscription[contact]' => 'Testing',
            'subscription[product]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->subscriptionRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Subscription();
        $fixture->setBeginDate('My Title');
        $fixture->setEndDate('My Title');
        $fixture->setContact('My Title');
        $fixture->setProduct('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Subscription');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Subscription();
        $fixture->setBeginDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setContact('Value');
        $fixture->setProduct('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'subscription[beginDate]' => 'Something New',
            'subscription[endDate]' => 'Something New',
            'subscription[contact]' => 'Something New',
            'subscription[product]' => 'Something New',
        ]);

        self::assertResponseRedirects('/subscription/');

        $fixture = $this->subscriptionRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getBeginDate());
        self::assertSame('Something New', $fixture[0]->getEndDate());
        self::assertSame('Something New', $fixture[0]->getContact());
        self::assertSame('Something New', $fixture[0]->getProduct());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Subscription();
        $fixture->setBeginDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setContact('Value');
        $fixture->setProduct('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/subscription/');
        self::assertSame(0, $this->subscriptionRepository->count([]));
    }
}
