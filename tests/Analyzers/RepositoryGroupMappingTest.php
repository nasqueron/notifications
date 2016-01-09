<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\RepositoryGroupMapping;
use Nasqueron\Notifications\Tests\TestCase;

class RepositoryGroupMappingTest extends TestCase {

    public function testDoesRepositoryMatch () {
        $this->assertTrue(
            RepositoryGroupMapping::doesRepositoryMatch(
                'quux*',
                'quuxians'
            )
        );

        $this->assertTrue(
            RepositoryGroupMapping::doesRepositoryMatch(
                'quux*',
                'quux'
            )
        );

        $this->assertFalse(
            RepositoryGroupMapping::doesRepositoryMatch(
                'foobar',
                'quux'
            )
        );

        $this->assertFalse(
            RepositoryGroupMapping::doesRepositoryMatch(
                '',
                'quuxians'
            )
        );

        $this->assertFalse(
            RepositoryGroupMapping::doesRepositoryMatch(
                'quux*',
                ''
            )
        );
    }
}
