<?php

namespace Nasqueron\Notifications\Tests\Console\Commands;

use Storage;

class ConfigValidateTest extends TestCase {

    /**
     * @var string
     */
    protected $class = 'Nasqueron\Notifications\Console\Commands\ConfigValidate';

    const TEST_FILE = 'bug.json';

    public function testRegularExecute () {
        $this->tester->execute(['command' => $this->command->getName()]);

        // When all files are valid, nothing is displayed
        $this->assertEquals('', $this->tester->getDisplay());
    }

    /**
     * @dataProvider provideErrors
     */
    public function testSyntaxErrorExecute (string $content, string $error) {
        $this->populateTestFile($content); // Not JSON

        $this->tester->execute(['command' => $this->command->getName()]);

        // When all files are valid, nothing is displayed
        $this->assertRegexpInDisplay("/$error/");
    }

    /**
     * Provides invalid JSON strings and associated error
     */
    public function provideErrors () : array {
        return [
            ["lorem ipsum dolor", "Syntax error"],
            ['{"}', "Control character error"]
        ];
    }

    private function populateTestFile (string $content) : void {
        Storage::disk('local')->put(self::TEST_FILE, $content);
    }

    private function deleteTestFile () : void {
        $fs = Storage::disk('local');
        if ($fs->exists(self::TEST_FILE)) {
            $fs->delete(self::TEST_FILE);
        }
    }

    public function tearDown () {
        $this->deleteTestFile();
        parent::tearDown();
    }
}
