<?php

namespace Nasqueron\Notifications\Tests\Analyzers;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Nasqueron\Notifications\Analyzers\ItemGroupMapping;
use Nasqueron\Notifications\Tests\TestCase;

class ItemGroupMappingTest extends TestCase {

    public function testDoesItemMatch () {
        $this->assertTrue(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                'quuxians'
            )
        );

        $this->assertTrue(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                'quux'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                'foobar',
                'quux'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                '',
                'quuxians'
            )
        );

        $this->assertFalse(
            ItemGroupMapping::doesItemMatch(
                'quux*',
                ''
            )
        );
    }

    /**
     * @dataProvider payloadProvider
     */
    public function testDeserialize (ItemGroupMapping $payload, ItemGroupMapping $expected) {
        $this->assertEquals($payload, $expected);
    }

    private function deserialize ($file) : ItemGroupMapping {
        $mapper = new \JsonMapper();
        $payload = json_decode(file_get_contents($file));
        return $mapper->map($payload, new ItemGroupMapping);
    }

    public function payloadProvider () : array {
        $toProvide = [];

        $path = __DIR__ . '/../data/ItemGroupMapping';
        $files = glob($path . "/*.expected.json");
        foreach ($files as $expectedResultFile) {
            $resultFile = str_replace(".expected", "", $expectedResultFile);
            $toProvide[] = [
                $this->deserialize($resultFile),
                $this->deserialize($expectedResultFile),
            ];
        }

        return $toProvide;
    }

}
