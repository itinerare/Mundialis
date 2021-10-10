<?php

namespace Tests\Feature;

use DB;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class accessTest extends TestCase
{
    // This is called in the first test for simplicity,
    // as regenerating the database for each set of tests is actively counterproductive,
    // but we need to make sure the tables involved exist, etc.
    use RefreshDatabase;

    // These tests check that visitor/user access to different routes is as expected.

    /**
     * Test visitor access when the site is closed
     *
     * @return void
     */
    public function test_visitorCannotReadWhenClosed()
    {
        // First, add site settings
        // We'll need these both now and later,
        // so it's practical to simply run the command
        $this->artisan('add-site-settings');

        DB::table('site_settings')->where('key', 'visitors_can_read')->update(['value' => 0]);

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
        DB::table('site_settings')->where('key', 'visitors_can_read')->update(['value' => 1]);

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
        $user = User::factory()->admin()->make();

        // Try to access admin dashboard
        $response = $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }
}
