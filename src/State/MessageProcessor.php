<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\SecurityBundle\Security;

class MessageProcessor implements ProcessorInterface
{
    private Security $security;
    private EntityManagerInterface $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authentified user is not an instance of App\Entity\User.');
        }

        $message = new Message();
        $message->setUserMessage($user);
        $message->setRob($data->getRob());
        $message->setContent($data->getContent());
        $message->setTimeStamp(new \DateTimeImmutable());
        $message->setConversation($data->getConversation());
        $message->setSentByHuman($data->isSentByHuman());

        $this->em->persist($message);
        $this->em->flush();

    }
}
