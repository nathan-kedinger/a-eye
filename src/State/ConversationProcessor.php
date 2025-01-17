<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\RobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ConversationProcessor implements ProcessorInterface
{
    private ProcessorInterface $persistProcessor;
    private Security $security;

    public function __construct(
        ProcessorInterface $persistProcessor,
        Security $security,
        RobRepository $robRepository
    ) {
        $this->persistProcessor = $persistProcessor;
        $this->security = $security;
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authenticated user is not an instance of App\Entity\User.');
        }


        if ($data instanceof Conversation) {
            // Met à jour la date
            $data->setLastUpdate(new \DateTimeImmutable());
            $data->setRob($data->getRob());
            $data->setUser($user);
        }
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
