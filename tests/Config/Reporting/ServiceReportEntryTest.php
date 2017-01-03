<?php

namespace Nasqueron\Notifications\Tests\Config\Reporting;

use Nasqueron\Notifications\Config\Reporting\ServiceReportEntry;
use Nasqueron\Notifications\Config\Services\Service;
use Nasqueron\Notifications\Tests\TestCase;

class ServiceReportEntryTest extends TestCase {

    /**
     * @var ServiceReportEntry
     */
    private $serviceEntry;

    public function setUp () {
        $service = $this->mockService();
        $this->serviceEntry  = new ServiceReportEntry($service);
    }

    public function testToArray() {
        $this->assertSame(
            ["Storm", "Acme", "http://www.perdu.com", ""],
            $this->serviceEntry->toArray()
        );
    }

    public function testToFancyArray() {
        $this->assertSame(
            ["Storm", "Acme", "http://www.perdu.com", "✓"],
            $this->serviceEntry->toFancyArray()
        );
    }

}
