<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\ItemGroupMapping;
use Nasqueron\Notifications\Tests\TestCase;

class ItemGroupMappingTest extends TestCase {

    public function testDoesItemMatch () {
        $this->assertTrue(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                'quuxians'
            )
        );

        $this->assertTrue(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                'quux'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                'foobar',
                'quux'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                '',
                'quuxians'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                ''
            )
        );
    }
}
