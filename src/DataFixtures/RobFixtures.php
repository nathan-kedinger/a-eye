<?php

namespace App\DataFixtures;

use App\Entity\Rob;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RobFixtures extends Fixture implements DependentFixtureInterface
{

    public const ROB_REFERENCE = 'rob_';
    public function load(ObjectManager $manager): void
    {
        // Créer plusieurs instances de Rob
        for ($i = 1; $i <= 10; $i++) {
            $rob = new Rob();
            $rob->setName('Rob ' . $i);
            $rob->setUser($this->getReference(UserFixtures::USER_REFERENCE. rand(10, 11), User::class));
            $rob->setDescription('Description for Rob ' . $i);
            $this->addReference(self::ROB_REFERENCE.$i , $rob);
            $manager->persist($rob);
        }
        $manager->flush();
    }

    public function getDependencies(): array{
        return [UserFixtures::class];
    }
}