<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Services\Service;

class PhabricatorProjectsMapTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\PhabricatorProjectsMap';

    public function setUp () {
        parent::setUp();

        $service = $this->mockService('Phabricator');
        $this->mockServices()
            ->shouldReceive('getForGate')
            ->once()
            ->andReturn([$service]);

        $this->mockPhabricatorAPIForProjectsMap();
    }

    public function testRegularExecute () {
        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexp('/PHID.*Project name/', $this->tester->getDisplay());
        $this->assertRegexp('/PHID-PROJ-cztcgpvqr6smnnekotq7.*Agora/', $this->tester->getDisplay());
    }
}
