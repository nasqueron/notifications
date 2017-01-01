<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Symfony\Component\Console\Tester\CommandTester;

use Nasqueron\Notifications\Config\Services\Service;
use Nasqueron\Notifications\Tests\TestCase as BaseTestCase;

use Artisan;
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

        $this->command = $this->findCommand($this->class);
        $this->tester = new CommandTester($this->command);
    }

    ///
    /// Helper methods to manipulate command arrays
    ///

    /**
     * Finds the first instance of the expected type in the specified array.
     *
     * @param mixed $expectedType The type to find among the array elements
     * @param array $haystack The array where to find
     * @return mixed|null If not found, null. Otherwise, the found item.
     */
    protected static function findInstanceOf ($expectedType, $haystack) {
        foreach ($haystack as $item) {
            if ($item instanceof $expectedType) {
                return $item;
            }
        }

        return null;
    }

    protected function findCommand ($expectedType) {
        return self::findInstanceOf($expectedType, Artisan::all());
    }

    ///
    /// Helper methods to mock services
    ///

    protected function mockServices () {
        // Inject into our container a mock of Services
        $mock = Mockery::mock('Nasqueron\Notifications\Config\Services\Services');
        $this->app->instance('services', $mock);

        return $mock;
    }

    protected function mockService ($gate = 'Storm') {
        $service = new Service;
        $service->gate = $gate;
        $service->door = 'Acme';
        $service->instance = "http://www.perdu.com";

        return $service;
    }

}
