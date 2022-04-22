<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Tests\TestCase as BaseTestCase;

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Tester\CommandTester;

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

    public function setUp (): void {
        parent::setUp();

        $kernel = $this->app->make(Kernel::class);
        $this->command = $kernel->getByClass($this->class);
        $this->tester = new CommandTester($this->command);
    }

    ///
    /// Display assertions
    ///

    public function assertDisplayContains(string $expectedNeedle) {
        $this->assertStringContainsString(
            $expectedNeedle,
            $this->tester->getDisplay()
        );
    }

    public function assertRegexpInDisplay (string $pattern) {
        $this->assertMatchesRegularExpression($pattern, $this->tester->getDisplay());
    }

}
