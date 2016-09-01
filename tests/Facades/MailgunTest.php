<?php

namespace Nasqueron\Notifications\Tests\Facades;

use Nasqueron\Notifications\Tests\TestCase;

use Config;
use Mailgun;

use Keruald\Mailgun\MailgunMessageFactory;

class MailgunTest extends TestCase {

    public function testIfFacadeAccessorCouldBeResolvedInAppContainer () {
        $this->assertInstanceOf(
            MailgunMessageFactory::class,
            Mailgun::getFacadeRoot()
        );
    }

}
