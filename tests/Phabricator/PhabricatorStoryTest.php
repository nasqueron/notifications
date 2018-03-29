<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Phabricator\PhabricatorStory;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorStoryTest extends TestCase {
    /**
     * @dataProvider provideStories
     */
    public function testGetObjectType ($expected, $data) {
        $story = new PhabricatorStory('acme');
        $story->data = $data;

		$this->assertEquals($expected, $story->getObjectType());
    }

    public function provideStories () : iterable {
        yield ["VOID", null];
        yield ["VOID", []];
        yield ["VOID", ['foo' => 'bar']];
        yield ["TASK", ['objectPHID' => 'PHID-TASK-l34fw5wievp6n6rnvpuk']];
    }
}
