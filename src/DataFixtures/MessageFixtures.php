<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Rob;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 1000; $i++) {
            $message = new Message();
            $message->setConversation($this->getReference(ConversationFixtures::CONVERSATION_REFERENCE. rand(1, 10), Conversation::class));
            $message->setUserMessage($this->getReference(UserFixtures::USER_REFERENCE . rand(11, 11), User::class,));
            $message->setRob($this->getReference(RobFixtures::ROB_REFERENCE. rand(1, 10), Rob::class,));
            $message->setContent('Message ' . $i);
            $message->setTimeStamp(new \DateTimeImmutable());
            $message->setReaded(false);
            $message->setConversation($this->getReference('conversation_' . rand(1, 100), Conversation::class));
            // utiliser un boolean aléatoire pour définir si le message a été envoyé par un humain
            $message->setSentByHuman((bool)rand(0, 1));
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RobFixtures::class,
            ConversationFixtures::class,
        ];
    }
}