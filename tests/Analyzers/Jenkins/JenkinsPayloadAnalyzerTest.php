<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzer;
use Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzerConfiguration;
use Nasqueron\Notifications\Tests\TestCase;

class JenkinsPayloadAnalyzerTest extends TestCase {

    /**
     * Jenkins analyzer to a successful build
     *
     * @var \Nasqueron\Notifications\Analyzers\Jenkins\JenkinsPayloadAnalyzer
     */
    protected $analyzer;

    /**
     * @var \stdClass
     */
    protected $payload;

    /**
     * Prepares the test
     */
    public function setUp () {
        parent::setUp();

        $filename = __DIR__ . '/../../data/payloads/JenkinsToIgnorePayload.json';
        $this->payload = json_decode(file_get_contents($filename));
        $this->analyzer = new JenkinsPayloadAnalyzer("Nasqueron", $this->payload);
    }

    public function testGetItemName () {
        $this->assertSame("test-prod-env", $this->analyzer->getItemName());
    }

    public function testGetGroup () {
        $this->assertSame("ops", $this->analyzer->getGroup());
    }

    public function testGetGroupWhenWeNeedDefaultFallback () {
        $this->payload->name = "quux";
        $this->assertSame("ci", $this->analyzer->getGroup());
    }

    public function testShouldNotifyWhenStatusIsUndefined () {
        unset($this->payload->build->status);
        $this->assertFalse($this->analyzer->shouldNotify());
    }

    /**
     * @dataProvider payloadStatusProvider
     */
    public function testShouldNotifyByStatus ($status, $shouldNotify) {
        $this->payload->build->status = $status;
        $this->assertSame($shouldNotify, $this->analyzer->shouldNotify());
    }

    /**
     * Provides data for testShouldNotifyByStatus
     *
     * @return array
     */
    public function payloadStatusProvider () {
        return [
            // Build status to notify
            ["FAILURE", true],
            ["ABORTED", true],
            ["UNSTABLE", true],

            // Build status to ignore
            ["SUCCESS", false],
            ["NOT_BUILT", false],
        ];
    }

}
