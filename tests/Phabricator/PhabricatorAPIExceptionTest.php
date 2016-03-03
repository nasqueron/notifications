<?php

namespace Nasqueron\Notifications\Tests\Phabricator;

use Nasqueron\Notifications\Phabricator\PhabricatorAPIException;
use Nasqueron\Notifications\Tests\TestCase;

class PhabricatorAPIExceptionTest extends TestCase {

    /**
     * @var \Nasqueron\Notifications\Phabricator\PhabricatorAPIException
     */
    private $exception;

    public function setUp () {
        $this->exception = new PhabricatorAPIException(
            100,
            "Lorem ipsum dolor"
        );
    }

    public function testGetCode () {
        $this->assertSame(100, $this->exception->getCode());
    }

    public function testGetMessage () {
        $this->assertSame("Lorem ipsum dolor", $this->exception->getMessage());
    }
}
