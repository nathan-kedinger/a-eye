<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer plusieurs instances de Place
        for ($i = 1; $i <= 10; $i++) {
            $place = new Place();
            $place->setName('Place ' . $i);

            $manager->persist($place);
        }

        $manager->flush();
    }
}