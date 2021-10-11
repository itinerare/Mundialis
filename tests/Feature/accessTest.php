<?php

namespace Tests\Feature;

use DB;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class accessTest extends TestCase
{
    use RefreshDatabase;

    // These tests check that visitor/user access to different routes is as expected.

    /**
     * Performs basic site setup/
     * tests that it can be accessed at the most basic level
     *
     * @return void
     */
    public function test_siteSetup()
    {
        // First, perform basic site setup. We'll need this sooner or later.
        $this->artisan('add-site-pages');
        $this->artisan('add-lexicon-settings');
        $this->artisan('copy-default-images');

        // Attempt to access the site on the most basic level
        $response = $this
            ->get('/')
            ->assertStatus(200);
    }

    /**
     * Test visitor access when the site is closed
     *
     * @return void
     */
    public function test_visitorCannotReadWhenClosed()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set the site to private to test
        DB::table('site_settings')->where('key', 'visitors_can_read')->update(['value' => 0]);

        // Attempt page access
        $response = $this
            ->get('/misc')
            ->assertStatus(302);
    }

    /**
     * Test visitor access when the site is open
     *
     * @return void
     */
    public function test_visitorCanReadWhenOpen()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set the site to open to test
        DB::table('site_settings')->where('key', 'visitors_can_read')->update(['value' => 1]);

        // Attempt page access
        $response = $this
            ->get('/misc')
            ->assertStatus(200);
    }

    /**
     * Test visitor access.
     *
     * @return void
     */
    public function test_visitorCannotGetAccountSettings()
    {
        $response = $this
            ->get('/account/settings')
            ->assertStatus(302);
    }

    /**
     * Test write access.
     *
     * @return void
     */
    public function test_visitorCannotGetWrite()
    {
        $response = $this
            ->get('/language/lexicon/create')
            ->assertStatus(302);
    }

    /**
     * Test visitor access.
     *
     * @return void
     */
    public function test_visitorCannotGetAdminIndex()
    {
        $response = $this
            ->get('/admin')
            ->assertStatus(302);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_userCanGetUserSettings()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/account/settings')
            ->assertStatus(200);
    }

    /**
     * Test write access.
     *
     * @return void
     */
    public function test_userCannotGetWrite()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/create')
            ->assertStatus(302);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_userCannotGetAdminIndex()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(302);
    }

    /**
     * Test write access.
     *
     * @return void
     */
    public function test_editorCanGetWrite()
    {
        // Make a temporary user
        $user = User::factory()->editor()->make();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/create')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_editorCannotGetAdminIndex()
    {
        // Make a temporary user
        $user = User::factory()->editor()->make();

        $response = $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(302);
    }

    /**
     * Test write access.
     *
     * @return void
     */
    public function test_adminCanGetWrite()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/create')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_adminCanGetAdminIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Try to access admin dashboard
        $response = $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }
}
