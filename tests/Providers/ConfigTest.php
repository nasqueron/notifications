<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ConfigTest extends TestCase {
    /**
     * The actual list of services providers
     *
     * @var string[]
     */
    private $providers;

    /**
     * The service providers' namespace
     *
     * @var string
     */
    private $namespace;

    public function setUp (): void {
        parent::setUp();

        $this->providers = Config::get('app.providers');
        $this->namespace = $this->app->getInstance()->getNamespace()
                         . 'Providers\\';
    }

    public function testOmittedFiles () {
        $files = File::allFiles(app_path('Providers'));

        foreach ($files as $file) {
            $class = $this->namespace . $file->getBasename('.php');
            $this->assertContains(
                $class, $this->providers,
                "The class $class should be added to config/app.php in the " .
                "providers array."
            );
        }
    }
}
