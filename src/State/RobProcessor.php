<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Message;
use App\Entity\Rob;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class RobProcessor implements ProcessorInterface
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
        if ($data instanceof Rob) {
            $data->setUser($user);
        }

        // Déléguer le reste du traitement au processeur décoré
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
