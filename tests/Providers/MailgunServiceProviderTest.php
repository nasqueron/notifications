<?php

namespace Nasqueron\Notifications\Tests\Providers;

class MailgunServiceProviderTest extends TestCase {

    public function testType () {
        $this->assertServiceInstanceOf(
            'Keruald\Mailgun\MailgunMessageFactory',
            'mailgun'
        );
    }

}
