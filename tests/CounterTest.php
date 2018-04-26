<?php

namespace App\Tests;

use App\Entity\Counter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class CounterTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testCreateCounter()
    {
        $counter = new Counter();
        $counter->setCounter(1);
        $this->em->persist($counter);
        $this->em->flush();

        $this->assertCount(1, $this->em->getRepository(Counter::class)->findAll());
    }

    public function testInsertFromPreviousTestIsRolledBack()
    {
        $this->assertEmpty($this->em->getRepository(Counter::class)->findAll(), 'changes from previous test must be rolled back!');
    }

    public function testInsertDoctrineFixturesDuringTest()
    {
        $this->assertEmpty($this->em->getRepository(Counter::class)->findAll());

        $app = new Application(static::$kernel);
        $app->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
        ]);
        $input->setInteractive(false);
        $app->run($input, new ConsoleOutput());

        $this->assertCount(1, $this->em->getRepository(Counter::class)->findAll());
    }

    public function testInsertFromPreviousTestWithFixturesIsRolledBack()
    {
        $this->assertEmpty($this->em->getRepository(Counter::class)->findAll(), 'changes from previous test must be rolled back!');
    }
}
