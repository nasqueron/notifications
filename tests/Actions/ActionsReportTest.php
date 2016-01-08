<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\ActionsReport;
use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Tests\TestCase;

class ActionsReportTest extends TestCase {

    protected $report;

    public function setUp () {
        $this->report = new ActionsReport();
    }

    public function testReport () {
        // Empty report
        $this->assertEmpty($this->report->gate);
        $this->assertEmpty($this->report->door);
        $this->assertFalse($this->report->containsError());
        $this->assertEquals(0, count($this->report->actions));

        // Adds a first action
        // Our report should be valid.
        $action = new AMQPAction(
            'method',
            'target'
        );
        $this->report->addAction($action);

        $this->assertEquals(1, count($this->report->actions));
        $this->assertFalse($this->report->containsError());

        // Let's attach an exception to a new action.
        // Our report should then be invalid.
        $action = new AMQPAction(
            'methodWithException',
            'target'
        );
        $ex = new \RuntimeException('Lorem ipsum dolor');
        $action->attachError(new ActionError($ex));
        $this->report->addAction($action);

        $this->assertEquals(2, count($this->report->actions));
        $this->assertTrue($this->report->containsError());

        // Attaches to gate
        $this->report->attachToGate('QuuxGate', 'Quuxians');
        $this->assertEquals('QuuxGate', $this->report->gate);
        $this->assertEquals('Quuxians', $this->report->door);

        // Test rendering
        $actualReport = (string)$this->report;
        $expectedReport = file_get_contents(__DIR__ . '/../data/report.json');

        $score = similar_text($expectedReport, $actualReport);
        $this->assertGreaterThan(550, $score, 'data/report.json and rendered report differ too much. Try $this->assertEquals($expectedReport, $actualReport) to see a diff.');
    }
}
