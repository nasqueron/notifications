<?php

namespace Nasqueron\Notifications\Tests\Console;

use Nasqueron\Notifications\Tests\TestCase;

use Nasqueron\Notifications\Console\Kernel;
use Illuminate\Contracts\Console\Kernel as BaseKernel;

use Artisan;
use File;

class KernelTest extends TestCase {
    /**
     * @var \Nasqueron\Notifications\Console\Kernel
     */
    private $kernel;

    /**
     * The actual list of services providers
     *
     * @var string[]
     */
    private $commands;

    /**
     * The service providers' namespace
     *
     * @var string
     */
    private $namespace;

    public function setUp () {
        parent::setUp();

        $this->kernel = $this->app->make(BaseKernel::class);
        $this->commands = $this->kernel->all();
        $this->namespace = $this->app->getInstance()->getNamespace()
                         . 'Console\\Commands\\';
    }

    public function testOmittedFiles () {
        $files = File::allFiles(app_path('Console/Commands'));

        foreach ($files as $file) {
            $class = $this->namespace . $file->getBasename('.php');
            $this->assertArrayContainsInstanceOf(
                $class,
                $this->commands,
                "The class $class should be added to app/Console/Kernel.php."
            );
        }
    }

    public function testGet () {
        $this->assertInstanceOf(
            \Nasqueron\Notifications\Console\Commands\Inspire::class,
            $this->kernel->get('inspire')
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetWhenCommandDoesNotExist () {
        $this->kernel->get('notexisting');
    }

    public function testGetByClass () {
        $class = \Nasqueron\Notifications\Console\Commands\Inspire::class;
        $this->assertInstanceOf($class, $this->kernel->getByClass($class));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetByClassWhenCommandDoesNotExist () {
        $this->kernel->getByClass('notexisting');
    }

    ///
    /// Custom assertions
    ///

    /**
     * Asserts the specified array contains an element of an expected type.
     *
     * @param mixed $expectedType The type to find among the array elements
     * @param array $haystack The array where to find
     * @param string $message The test message
     */
    public static function assertArrayContainsInstanceOf ($expectedType, $haystack, $message = '') {
        self::assertThat(
            self::arrayContainsInstanceOf($expectedType, $haystack),
            self::isTrue(),
            $message
        );
    }

    /**
     * Determines if the specified array contains at least one instance of the
     * specified type.
     *
     * @param mixed $expectedType The type to find among the array elements
     * @param array $haystack The array where to find
     * @return bool
     */
    protected static function arrayContainsInstanceOf ($expectedType, $haystack) {
        foreach ($haystack as $item) {
            if ($item instanceof $expectedType) {
                return true;
            }
        }

        return false;
    }

}
