<?php

namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    use LazilyRefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        // Perform basic site setup
        $this->artisan('app:add-site-settings');
        $this->artisan('app:copy-default-images');

        // Clean up any extant users to minimize risk of issues
        // due to the large volume of tests
        if (User::query()->count()) {
            User::query()->delete();
        }

        // Create a temporary user to assist with general testing
        $this->user = User::factory()->make();
    }
}
