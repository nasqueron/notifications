<?php

namespace Nasqueron\Notifications\Tests\Providers;

use Config;
use File;

class ConfigTest extends TestCase {
    public function testOmittedFiles () {
        $providers = [];

        $actualProviders = Config::get('app.providers');

        $namespace = $this->app->getInstance()->getNamespace() . 'Providers\\';
        $files = File::allFiles(app_path('Providers'));
        foreach ($files as $file) {
            $class = $namespace . $file->getBasename('.php');
            $this->assertContains(
                $class, $actualProviders,
                "The class $class should be added to config/app.php in the " .
                "providers array."
            );
        }
    }
}
