<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LastConversationController extends AbstractController
{
    #[Route('/api/conversation/last-modified/{robId}', name: 'last_modified_conversation', methods: ['GET'])]
    public function lastConversation(
        $robId,
        ConversationRepository $conversationRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $lastConversation = $conversationRepository->findOneBy(
            ['user' => $user, 'rob' => $robId],
            ['lastUpdate' => 'DESC']
        );

        if (!$lastConversation) {
            return new JsonResponse(['error' => 'No conversation found'], 404);
        }
        $data = $serializer->serialize($lastConversation, 'json', ['groups' => ['conversation:get']]);

        return new JsonResponse(json_decode($data), 200);
    }
}
