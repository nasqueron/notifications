<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Config\Features;
use Mockery;

class ConfigShowTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\ConfigShow';

    /**
     * Nasqueron\Notifications\Config\Services\Services
     */
    private $servicesMock;

    public function setUp (): void {
        parent::setUp();

        $this->servicesMock = $this->mockServices();
    }

    public function testRegularExecute () {
        //Our command calls Services::get()
        $this->servicesMock->shouldReceive('get')->once()->andReturn([]);

        $this->tester->execute(['command' => $this->command->getName()]);

        $this->assertRegexpInDisplay('/Gates/');
        $this->assertRegexpInDisplay('/Features/');
        $this->assertRegexpInDisplay('/Services declared/');
    }

    public function testRegularExecuteWithService () {
        $service = $this->mockService();
        $this->servicesMock
            ->shouldReceive('get')
            ->once()
            ->andReturn([$service]);

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexpInDisplay('/Storm/');
    }

    public function testRegularExecuteWithPhabricatorService () {
        $this->mockPhabricatorAPIForProjectsMap();

        $service = $this->mockService('Phabricator');
        $this->servicesMock
            ->shouldReceive('get')
            ->once()
            ->andReturn([$service]);

        $this->servicesMock
            ->shouldReceive('findServiceByProperty');

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexpInDisplay(
            '/Phabricator.*Projects map not cached./'
        );
    }

    protected function mockProjectsMap () {
        $mock = Mockery::mock(
            'Nasqueron\Notifications\Phabricator\ProjectsMap'
        );
        $this->app->instance('phabricator-projectsmap', $mock);

        return $mock;
    }

    public function testRegularExecuteWithPhabricatorServiceWhenTheProjectsMapIsCached () {
        // The services list will return only one, for the Phabricator gate.
        $service = $this->mockService('Phabricator');
        $this->servicesMock
            ->shouldReceive('get')->once()->andReturn([$service]);

        // The project map (built by the factory) will say it's cached.
        $this->mockProjectsMap()
            ->shouldReceive('fetch->isCached')->once()->andReturn(true);

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexpInDisplay('/Phabricator.*✓/');
    }

    public function testExecuteWhenSomeFeatureIsDisabled () {
        Features::disable('ActionsReport');

        $this->servicesMock->shouldReceive('get')->once()->andReturn([]);

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexpInDisplay(
            '/Gate *\| *✓ *\|/'
        );
        $this->assertRegexpInDisplay(
            '/ActionsReport *\| *\|/'
        );
    }

}
