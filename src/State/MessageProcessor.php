<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Message;
use App\Entity\User;
use App\Service\ChatGPTService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MessageProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;
    private Security $security;
    private ChatGPTService $chatGPTService;

    public function __construct(ProcessorInterface $persistProcessor, Security $security, ChatGPTService $chatGPTService)
    {
        $this->persistProcessor = $persistProcessor;
        $this->security = $security;
        $this->chatGPTService = $chatGPTService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
        }

        if ($data instanceof Message) {
            // Associer le message humain
            $data->setUserMessage($user);
            $data->setTimeStamp(new \DateTimeImmutable());

            // Enregistrer le message humain
            $this->persistProcessor->process($data, $operation, $uriVariables, $context);

            // Obtenir une réponse de ChatGPT
            $chatGPTResponse = $this->chatGPTService->getResponse($data->getContent(), $data->getConversation());

            // Créer le message du bot
            $botMessage = new Message();
            $botMessage->setUserMessage($user);
            $botMessage->setContent($chatGPTResponse);
            $botMessage->setSentByHuman(false);
            $botMessage->setReaded(false);
            $botMessage->setTimeStamp(new \DateTimeImmutable());
            $botMessage->setConversation($data->getConversation());
            $botMessage->setRob($data->getRob());

            // Enregistrer le message du bot
            $this->persistProcessor->process($botMessage, $operation, $uriVariables, $context);
        }

        return $data;
    }


}
