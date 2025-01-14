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
        //$robRepository = $this->em->getRepository(Rob::class);
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authentified user is not an instance of App\Entity\User.');
        }

        try {
            if ($operation->getName() === self::OPERATION_COLLECTION) {

                $robId = $context['filters']['rob.id'] ?? null;
                return $conversationRepository->findBy(['user' => $user, 'rob' => $robId]);
            }

            //$robId = $uriVariables['rob']['id'] ?? null;
            //if ($robId) {
            /*                $rob = $robRepository->find($robId);
                            if (!$rob) {
                                throw new NotFoundHttpException('Rob not found.');
                            }*/

            // $isConversationExist = $conversationRepository->isConversationExists($user, $rob);
            if ($operation->getName() === self::OPERATION_GET) {
                $conversationId = $uriVariables['id'] ?? null;
                /*if (!$isConversationExist) {
                    $conversation = new Conversation();
                    $conversation->setUser($user);

                    $conversation->setRob($rob);
                    $conversation->setTitle('Discussion with Bot ' . $robId);
                    $conversation->setDescription('Description of the chat');

                    $this->em->persist($conversation);
                    $this->em->flush();

                } else {*/
                // TODO Trier pour afficher la dernière conversation
                $tpConversation = $conversationRepository->findOneBy(['id' => $conversationId]);
                if ($tpConversation->getUser() === $user) {
                    return $tpConversation;
                } else {
                    throw new InvalidArgumentException('You are not allowed to access this conversation.');
                }
                // }
            }
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        //}
        return null;
    }
}