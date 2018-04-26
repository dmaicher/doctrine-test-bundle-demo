<?php

namespace App\DataFixtures;

use App\Entity\Counter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CounterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $counter = new Counter();
        $counter->setCounter(666);
        $manager->persist($counter);
        $manager->flush();
    }
}
