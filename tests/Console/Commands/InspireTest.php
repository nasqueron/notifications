<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

class InspireTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\Inspire';

    public function testExecute () {
        // A quote contain a - character and is embedded by PHP_EOL
        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexpInDisplay('/\n.*—.*\n/');
    }

}
