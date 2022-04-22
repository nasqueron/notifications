<?php

namespace Nasqueron\Notifications\Tests\Config\Reporting;

use Nasqueron\Notifications\Config\Reporting\FeatureReportEntry;
use Nasqueron\Notifications\Tests\TestCase;

class FeatureReportEntryTest extends TestCase {

    /**
     * @var FeatureReportEntry
     */
    private $enabledFeatureEntry;

    /**
     * @var FeatureReportEntry
     */
    private $disabledFeatureEntry;

    public function setUp (): void {
        $this->enabledFeatureEntry  = new FeatureReportEntry("foo", true);
        $this->disabledFeatureEntry = new FeatureReportEntry("bar", false);
    }

    public function testToArray() {
        $this->assertSame(
            ["foo", (string)true],
            $this->enabledFeatureEntry->toArray()

        );
        $this->assertSame(
            ["bar", (string)false],
            $this->disabledFeatureEntry->toArray()
        );
    }

    public function testToFancyArray() {
        $this->assertSame(
            ["foo", "✓"],
            $this->enabledFeatureEntry->toFancyArray()
        );
        $this->assertSame(
            ["bar", ""],
            $this->disabledFeatureEntry->toFancyArray()
        );
    }

}
