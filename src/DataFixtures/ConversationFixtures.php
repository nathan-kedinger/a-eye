<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Rob;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    public const CONVERSATION_REFERENCE = 'conversation_';
    public function load(ObjectManager $manager): void
    {
        // Créer plusieurs instances de Rob
        for ($i = 1; $i <= 100; $i++) {
            $conversation = new Conversation();
            $conversation->setRob($this->getReference(RobFixtures::ROB_REFERENCE . rand(1, 10), Rob::class));
            $conversation->setUser($this->getReference(UserFixtures::USER_REFERENCE. rand(11, 11), User::class));
            $conversation->setTitle('Title ' . $i);
            $startTimestamp = strtotime('2020-01-01');
            $endTimestamp = strtotime('2023-12-31');
            $randomTimestamp = random_int($startTimestamp, $endTimestamp);
            $randomDate = new \DateTimeImmutable(date('Y-m-d H:i:s', $randomTimestamp));
            $conversation->setLastUpdate($randomDate);
            $conversation->setDescription('Description for conversation ' . $i);
            $this->addReference(self::CONVERSATION_REFERENCE. $i, $conversation);


            $manager->persist($conversation);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RobFixtures::class,
        ];
    }
}