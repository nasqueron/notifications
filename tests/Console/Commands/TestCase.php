<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Config\Services\Service;
use Nasqueron\Notifications\Tests\TestCase as BaseTestCase;

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Tester\CommandTester;

use Mockery;

class TestCase extends BaseTestCase {

    ///
    /// Commands test environment
    ///

    /**
     * @var Symfony\Component\Console\Command
     */
    protected $command;

    /**
     * @var Symfony\Component\Console\Tester\CommandTester;
     */
    protected $tester;

    public function setUp () {
        parent::setUp();

        $kernel = $this->app->make(Kernel::class);
        $this->command = $kernel->getByClass($this->class);
        $this->tester = new CommandTester($this->command);
    }

}
