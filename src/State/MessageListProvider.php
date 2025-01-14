<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\MessageRepository;

class MessageListProvider implements ProviderInterface
{
    private $messageRepository;
//    private Conversation $conversation ;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        //$this->conversation = $conversation;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $conversation = $context['filters']['conversation'] ?? null;

        if ($conversation) {

            //return $this->messageRepository->findByUserAndRob($robId, $userId);
            // return $this->messageRepository->findBy([$this->conversation]);
        }
        return null;
    }
}
