<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Facades\Mailgun;
use Nasqueron\Notifications\Tests\TestCase;

use Keruald\Mailgun\MailgunMessageFactory;

class MailgunTest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            MailgunMessageFactory::class,
            Mailgun::getFacadeRoot()
        );
    }

}
