<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Post;
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
        // Vérifiez si l'opération est de type POST
        if ($operation instanceof Post) {
            $user = $this->security->getUser();
            if (!$user instanceof User) {
                throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
            }

            // Ajout de l'utilisateur à l'objet Rob
            if ($data instanceof Rob) {
                $data->setUser($user);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
