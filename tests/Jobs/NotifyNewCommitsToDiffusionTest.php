<?php

namespace Nasqueron\Notifications\Tests\Jobs;

use Nasqueron\Notifications\Jobs\Job;
use Nasqueron\Notifications\Jobs\NotifyNewCommitsToDiffusion;
use Nasqueron\Notifications\Tests\TestCase;

class NotifyNewCommitsToDiffusionTest extends TestCase {

    ///
    /// Tests
    ///

    /**
     * @dataProvider apiRepositoryReplyProvider
     */
    public function testHandle(?array $apiRepositoryReply, int $apiCallCounts) {
        $this->mockPhabricatorAPI()
            ->shouldReceive('getForProject->call')
            ->andReturn(
                // First API call: repository.query
                $apiRepositoryReply,

                // Second API call: diffusion.looksoon
                null
            )
            ->times($apiCallCounts); // 2 when repository.query is valid
                                     // 1 otherwise

        $job = $this->mockJob();
        $job->handle();
    }

    public function testJobWhenThereIsNoPhabricatorInstanceForTheProject () : void {
        $job = $this->mockJob("not-existing-project");
        $job->handle();

        $this->markTestIncomplete();
    }

    ///
    /// Helper methods
    ///

    /**
     * Mocks a job
     */
    protected function mockJob(string $project = "acme") : Job {
        return new NotifyNewCommitsToDiffusion(
            $project,
            "ssh://acme/k2.git"
        );
    }

    /**
     * Provides API repository reply and associated API calls count
     */
    public function apiRepositoryReplyProvider () : array {
        return [
            // Regular behavior
            [[new class { public $callsign = "K2"; }], 2],

            // Phabricator doesn't know this repo
            [[], 1],

            // Some error occurs and the API reply is null
            [null, 1],
        ];
    }

}
