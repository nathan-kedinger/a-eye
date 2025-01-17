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
    private ProcessorInterface $persistProcessor;
    private Security $security;

    public function __construct(ProcessorInterface $persistProcessor, Security $security)
    {
        $this->persistProcessor = $persistProcessor;
        $this->security = $security;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
        }

        // Ajout de la logique métier
        if ($data instanceof Message) {
            $data->setUserMessage($user);
            $data->setTimeStamp(new \DateTimeImmutable());
        }

        // Déléguer le reste du traitement au processeur décoré
       return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
