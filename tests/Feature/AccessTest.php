<?php

namespace Tests\Feature;

use App\Models\User\User;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessTest extends TestCase
{
    use RefreshDatabase;

    // These tests check that visitor/user access to different routes is as expected
    // In other words, they are a cursory check of middleware functionality

    /**
     * Test most basic site access.
     */
    public function test_canAccessSite()
    {
        // Attempt to access the site on the most basic level
        $response = $this
            ->get('/')
            ->assertStatus(200);
    }

    /**
     * Test visitor access when the site is closed.
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
     * Test visitor access when the site is open.
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
     * Ensure visitor cannot access member-only routes.
     */
    public function test_visitorCannotGetAccountSettings()
    {
        $response = $this
            ->get('/account/settings')
            ->assertStatus(302);
    }

    /**
     * Ensure visitor cannot access editor routes.
     */
    public function test_visitorCannotGetWrite()
    {
        $response = $this
            ->get('/language/lexicon/create')
            ->assertStatus(302);
    }

    /**
     * Ensure visitor cannot access admin routes.
     */
    public function test_visitorCannotGetAdminIndex()
    {
        $response = $this
            ->get('/admin')
            ->assertStatus(302);
    }

    /**
     * Ensure user can access member-only routes.
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
     * Ensure user cannot access editor routes.
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
     * Ensure user cannot access admin routes.
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
     * Ensure editor can access editor routes.
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
     * Ensure editor cannot access admin routes.
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
     * Ensure admin can access editor routes.
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
     * Ensure admin can access admin routes.
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
