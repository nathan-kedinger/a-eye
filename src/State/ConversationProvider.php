<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;

class ConversationProvider implements ProviderInterface
{

    /**
     * @inheritDoc
     */
    private Security $security;
    private EntityManagerInterface $em;
    private const string OPERATION_GET = "_api_/conversations/{id}{._format}_get";
    private const string OPERATION_COLLECTION = "_api_/conversations{._format}_get_collection";

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Conversation|array|null
    {
        $conversationRepository = $this->em->getRepository(Conversation::class);
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
        }

        try {
            if ($operation->getName() === self::OPERATION_COLLECTION) {
                $robId = $context['filters']['rob.id'] ?? null;

                return $conversationRepository->findBy(
                    ['user' => $user, 'rob' => $robId],
                    ['lastUpdate' => 'DESC']
                );
            }
            if ($operation->getName() === self::OPERATION_GET) {
                $conversationId = $uriVariables['id'] ?? null;
                $tpConversation = $conversationRepository->findOneBy(['id' => $conversationId]);
                if ($tpConversation->getUser() === $user) {
                    return $tpConversation;
                } else {
                    throw new InvalidArgumentException('You are not allowed to access this conversation.');
                }
            }
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        return null;
    }
}