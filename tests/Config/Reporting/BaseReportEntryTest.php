<?php

namespace Nasqueron\Notifications\Tests\Config\Reporting;

use Nasqueron\Notifications\Config\Reporting\BaseReportEntry;
use Nasqueron\Notifications\Tests\TestCase;

class BaseReportEntryTest extends TestCase {

    public function testFancyString() {
        $this->assertSame('ø', BaseReportEntry::fancyString('', 'ø'));
        $this->assertSame('ø', BaseReportEntry::fancyString('ø', 'ø'));
        $this->assertSame('o', BaseReportEntry::fancyString('o', 'ø'));
        $this->assertSame('', BaseReportEntry::fancyString('', ''));
    }

    public function testFancyBool() {
        $this->assertSame('ø', BaseReportEntry::fancyBool(false, '✓', 'ø'));
        $this->assertSame('✓', BaseReportEntry::fancyBool(true, '✓', 'ø'));
        $this->assertSame('', BaseReportEntry::fancyBool(false, '✓'));
        $this->assertSame('✓', BaseReportEntry::fancyBool(true, '✓'));
        $this->assertSame('', BaseReportEntry::fancyBool(true, '', ''));
        $this->assertSame('', BaseReportEntry::fancyBool(false, '', ''));
    }

}
