<?php

namespace Nasqueron\Notifications\Tests\Analyzers\Phabricator;

use Nasqueron\Notifications\Analyzers\Phabricator\PhabricatorPayloadAnalyzerConfiguration;
use Nasqueron\Notifications\Phabricator\PhabricatorStory;

/**
 * Helper methods to construct needed objects
 */
trait WithConfiguration {

    private function getPhabricatorPayloadAnalyzerConfiguration () {
        $filename = __DIR__ . '/../../data/PhabricatorPayloadAnalyzer/Nasqueron.json';
        $mapper = new \JsonMapper();
        return $mapper->map(
            json_decode(file_get_contents($filename)),
            new PhabricatorPayloadAnalyzerConfiguration('Nasqueron')
        );
    }

    private function getStory() {
        return new PhabricatorStory("https://phab.acme");
    }

}
