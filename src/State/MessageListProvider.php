<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MessageListProvider implements ProviderInterface
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $messageRepository = $this->em->getRepository(Message::class);
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
        }
        try {
            $conversation = $context['filters']['conversation'] ?? null;
            if ($conversation) {
                return $messageRepository->findBy(
                    ['conversation' => $conversation, 'userMessage' => $user],
                    ['timeStamp' => 'ASC']
                );
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        return null;
    }
}
