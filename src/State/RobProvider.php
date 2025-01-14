<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Rob;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;

class RobProvider implements ProviderInterface
{

    private Security $security;
    private EntityManagerInterface $em;
    private const string OPERATION_COLLECTION = "_api_/robs{._format}_get_collection";

    public function __construct(Security $security, EntityManagerInterface $em){
        $this->security = $security;
        $this->em = $em;
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Rob|array|null
    {
        $robRepository = $this->em->getRepository(Rob::class);
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \RuntimeException('Authentified user is not an instance of App\Entity\User.');
        }

        try {
            if ($operation->getName() === self::OPERATION_COLLECTION) {
                return $robRepository->findBy(['user' => $user]);
            }
        } catch (\Exception $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        return null;
        // Retrieve the state from somewhere
    }
}
