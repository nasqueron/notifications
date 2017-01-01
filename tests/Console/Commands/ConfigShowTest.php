<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Nasqueron\Notifications\Config\Features;
use Nasqueron\Notifications\Services\Service;

use Mockery;

class ConfigShowTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\ConfigShow';

    /**
     * Nasqueron\Notifications\Services\Services
     */
    private $servicesMock;

    public function setUp () {
        parent::setUp();

        $this->servicesMock = $this->mockServices();
    }

    public function testRegularExecute () {
        //Our command calls Services::get()
        $this->servicesMock->shouldReceive('get')->once()->andReturn([]);

        $this->tester->execute(['command' => $this->command->getName()]);

        $this->assertRegexp('/Gates/', $this->tester->getDisplay());
        $this->assertRegexp('/Features/', $this->tester->getDisplay());
        $this->assertRegexp('/Services declared/', $this->tester->getDisplay());
    }

    public function testRegularExecuteWithService () {
        $service = $this->mockService();
        $this->servicesMock
            ->shouldReceive('get')
            ->once()
            ->andReturn([$service]);

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexp('/Storm/', $this->tester->getDisplay());
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
        $this->assertRegexp('/Phabricator.*Projects map not cached./', $this->tester->getDisplay());
    }

    protected function mockProjectsMap () {
        $mock = Mockery::mock('Nasqueron\Notifications\Phabricator\ProjectsMap');
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
        $this->assertRegexp('/Phabricator.*✓/', $this->tester->getDisplay());
    }

    public function testExecuteWhenSomeFeatureIsDisabled () {
        Features::disable('ActionsReport');

        $this->servicesMock->shouldReceive('get')->once()->andReturn([]);

        $this->tester->execute(['command' => $this->command->getName()]);
        $this->assertRegexp('/Gate *\| *✓ *\|/', $this->tester->getDisplay());
        $this->assertRegexp('/ActionsReport *\| *\|/', $this->tester->getDisplay());
    }

}
