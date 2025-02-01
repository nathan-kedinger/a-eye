<?php

namespace App\Service;

use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatGPTService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private EntityManagerInterface $entityManager;
    private string $currentSummary='';

    public function __construct(HttpClientInterface $httpClient, string $apiKey, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getResponse(string $userMessage, Conversation $conversation): string
    {

        $robCustomization = $conversation->getRob()->getDescription();
        // Récupération de l'ancien résumé depuis l'entité
        $previousSummary = $conversation->getContext() ?? $this->currentSummary;

        // Mise à jour du résumé avec le nouveau message
        $newSummary = $this->getContext($previousSummary, $userMessage);

        // On met à jour l'entité uniquement si le résumé a bien été généré
        if ($newSummary !== '') {
            $conversation->setContext($newSummary);
            $this->entityManager->persist($conversation);
            $this->entityManager->flush();
            // Synchroniser la propriété locale
            $this->currentSummary = $newSummary;
        }

        // Appel principal pour générer la réponse
        $apiUrl = 'https://api.openai.com/v1/chat/completions';
        $response = $this->httpClient->request('POST', $apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'      => 'gpt-3.5-turbo',
                'messages'   => [
                    ['role' => 'system', 'content' => $robCustomization],
                    ['role' => 'system', 'content' => 'Résumé du contexte précédent : ' . $this->currentSummary ],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 150,
            ],
        ]);

        $data = $response->toArray();
        return $data['choices'][0]['message']['content'] ?? 'Désolé, une erreur est survenue.';
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getContext(string $currentSummary, string $newMessage): string
    {
        if($currentSummary === '') {
            return $newMessage;
        }
        $prompt = "Voici le résumé actuel de la conversation :\n$currentSummary\n\n".
            "Nouveau message :\n$newMessage\n\n".
            "Mets à jour ce résumé en intégrant uniquement les points clés, de façon concise.";

        $apiUrl = 'https://api.openai.com/v1/chat/completions';

        $response = $this->httpClient->request('POST', $apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You make a brief resume of this message'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 250,
            ],
        ]);

        $data = $response->toArray();

        return $data['choices'][0]['message']['content'] ?? 'Désolé, une erreur est survenue.';
    }

}
