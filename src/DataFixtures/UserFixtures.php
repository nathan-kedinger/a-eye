<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user_';
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setRoles(['ROLE_USER']);
            $user->setFirebaseUid('firebaseUid' . $i);
            $this->addReference(self::USER_REFERENCE . $i, $user);
            $manager->persist($user);
        }

        $mainUser = new User();
        $mainUser->setEmail('nathan.kedinger@gmail.com');
        $mainUser->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $mainUser->setFirebaseUid('sIxffRFScVdzOHF4WGYewCf09B13');
        $this->addReference('user_11', $mainUser);
        $manager->persist($mainUser);

        $manager->flush();
    }
}