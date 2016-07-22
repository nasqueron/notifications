<?php

namespace Nasqueron\Notifications\Tests\Jobs;

use Nasqueron\Notifications\Jobs\NotifyNewCommitsToDiffusion;
use Nasqueron\Notifications\Phabricator\PhabricatorAPI;
use Nasqueron\Notifications\Tests\TestCase;

use Mockery;

class NotifyNewCommitsToDiffusionTest extends TestCase {

    /**
     * Mock for the Phabricator API factory
     * @var \Mockery\MockInterface
     */
    private $apiFactoryMock;

    /**
     * The job to test
     * @var NotifyNewCommitsToDiffusion
     */
    private $job;

    public function setUp () {
        parent::setUp();

        $this->apiFactoryMock = $this->mockPhabricatorAPI();
        $this->job = $this->mockJob();
    }

    /**
     * @return NotifyNewCommitsToDiffusion
     */
    protected function mockJob() {
        return new NotifyNewCommitsToDiffusion("acme", "ssh://acme/k2.git");
    }

    public function testJobWantsPhabricatorAPI () {
        $this->apiFactoryMock->shouldReceive('getForProject')->once();
        $this->job->handle();
    }

}
