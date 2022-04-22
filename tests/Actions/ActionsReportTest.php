<?php

namespace Nasqueron\Notifications\Tests\Actions;

use Nasqueron\Notifications\Actions\ActionError;
use Nasqueron\Notifications\Actions\ActionsReport;
use Nasqueron\Notifications\Actions\AMQPAction;
use Nasqueron\Notifications\Tests\TestCase;

class ActionsReportTest extends TestCase {

    protected $report;

    public function setUp (): void {
        $this->report = new ActionsReport();
    }

    public function testReport () {
        // Empty report
        $this->assertEmpty($this->report->gate);
        $this->assertEmpty($this->report->door);
        $this->assertFalse($this->report->containsError());
        $this->assertSame(0, count($this->report->actions));

        // Adds a first action
        // Our report should be valid.
        $action = new AMQPAction(
            'method',
            'target'
        );
        $this->report->addAction($action);

        $this->assertSame(1, count($this->report->actions));
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

        $this->assertSame(2, count($this->report->actions));
        $this->assertTrue($this->report->containsError());
        $this->assertIsNumeric($this->report->created);

        // Attaches to gate
        $this->report->attachToGate('QuuxGate', 'Quuxians');
        $this->assertSame('QuuxGate', $this->report->gate);
        $this->assertSame('Quuxians', $this->report->door);
    }
}
