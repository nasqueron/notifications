<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Console\Commands\PhabricatorProjectsMap;

class PhabricatorProjectsMapTest extends TestCase {

    /**
     * @var string
     */
    protected $class = PhabricatorProjectsMap::class;

    public function setUp (): void {
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
        $this->assertRegexpInDisplay('/PHID.*Project name/');
        $this->assertRegexpInDisplay(
            '/PHID-PROJ-cztcgpvqr6smnnekotq7.*Agora/'
        );
    }
}
