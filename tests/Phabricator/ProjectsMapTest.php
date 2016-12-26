<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Contracts\APIClient;
use Nasqueron\Notifications\Phabricator\ProjectsMap;
use Nasqueron\Notifications\Tests\TestCase;

use Mockery;

class ProjectsMapTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Phabricator\ProjectsMap
     */
    private $map;

    public function setUp () {
        parent::setUp();

        //
        // We mock the API, so an imaginary instance of Phabricator
        // will return 3 results: Accounts, Agora & architecture.
        //
        // Agora has the key "PHID-PROJ-cztcgpvqr6smnnekotq7".
        //

        $this->mockPhabricatorAPIForProjectsMap();
        $this->map = ProjectsMap::fetch("http://phabricator.acme.tld");
    }

    public function testIteratorIsTraversable () {
        $this->assertInstanceOf(
            "Traversable",
            $this->map->getIterator()
        );
    }

    ///
    /// Tests for ArrayAccess
    ///

    public function testOffsetExistsWhenItDoes () {
        $this->assertTrue(
            $this->map->offsetExists("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    public function testOffsetExistsWhenItDoesNot () {
        $this->assertFalse(
            $this->map->offsetExists("non-existing-key")
        );
    }

    public function testOffsetGetWhenItDoesExist () {
        $this->assertSame(
            "Agora",
            $this->map->offsetGet("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    /**
     * @expectedException ErrorException
     */
    public function testOffsetGetWhenItDoesNotExist () {
        $this->map->offsetGet("non-existing-key");
    }

    /**
     * @covers Nasqueron\Notifications\Phabricator\ProjectsMap::offsetSet
     */
    public function testOffsetSet () {
        $this->map->offsetSet("newkey", "quux");
        $this->assertSame("quux", $this->map->offsetGet("newkey"));
    }

    /**
     * @covers Nasqueron\Notifications\Phabricator\ProjectsMap::offsetUnset
     */
    public function testOffsetUnset () {
        unset($this->map["PHID-PROJ-cztcgpvqr6smnnekotq7"]);
        $this->assertFalse(
            $this->map->offsetExists("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    ///
    /// Tests for cache
    ///

    public function testCache () {
        $this->assertFalse($this->map->isCached());
        $this->map->saveToCache();
        $this->assertTrue($this->map->isCached());
    }

    public function testLoadFromCache () {
        $this->map->saveToCache();

        $map = new ProjectsMap("http://phabricator.acme.tld");
        $map->loadFromCache();

        $this->assertTrue(
            $map->offsetExists("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    public function testLoadWhenInCache () {
        $this->map->saveToCache();

        $map = ProjectsMap::load("http://phabricator.acme.tld");
        $this->assertTrue(
            $map->offsetExists("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    ///
    /// Tests for helper methods
    ///

    public function testGetProjectName () {
        $this->assertSame(
            "Agora",
            $this->map->getProjectName("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    public function testGetProjectNameForNewInstance () {
        $map = new ProjectsMap("http://phabricator.acme.tld");
        $this->assertSame(
            "Agora",
            $map->getProjectName("PHID-PROJ-cztcgpvqr6smnnekotq7")
        );
    }

    public function testGetProjectNameWhenItDoesNotExist () {
        $this->assertSame(
            "",
            $this->map->getProjectName("non-existing-key")
        );
    }

    public function testToArrayProducesArray () {
        $array = $this->map->toArray();
        $this->assertTrue(
            is_array($array),
            "Test if toArray return an array"
        );
    }

    public function testThatArrayCount () {
        $array = $this->map->toArray();
        $this->assertSame(3, count($array));
    }

    public function testThatArrayContainsExpectedData () {
        $this->assertSame(
            [
                 ["PHID-PROJ-6dg6ogx5pjmk24ur4tp4", "Accounts"],
                 ["PHID-PROJ-cztcgpvqr6smnnekotq7", "Agora"],
                 ["PHID-PROJ-3iew3cqf3htpazfyzb5a", "architecture"]
            ],
            $this->map->toArray()
        );
    }

    ///
    /// Tests API
    ///

    private function mockPhabricatorAPIWithReply ($reply) : APIClient {
        return (new class($reply) implements APIClient {
            private $reply;

            public function __construct ($reply) {
                $this->reply = $reply;
            }

            public function setEndPoint ($url) : void { }

            public function call ($method, $arguments = []) {
                return $this->reply;
            }
        });
    }

    /**
     * @expectedException Exception
     */
    public function testFetchFromAPIWithoutReply () {
        $mock = $this->mockPhabricatorAPIWithReply(false);
        ProjectsMap::fetch("http://phabricator.acme.tld", $mock);
    }

    /**
     * @expectedException Exception
     */
    public function testFetchFromAPIInvalidReply () {
        $mock = $this->mockPhabricatorAPIWithReply(new \stdClass);
        ProjectsMap::fetch("http://phabricator.acme.tld", $mock);
    }

}
