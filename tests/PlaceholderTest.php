<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlaceholderTest extends TestCase
{
    /**
     * Placeholder homepage works.
     *
     * @return void
     */
    public function testPlaceholder()
    {
        $this->visit('/')
             ->see('Notifications center');
    }
}
