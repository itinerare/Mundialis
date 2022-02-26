<?php

namespace Tests\Feature;

use App\Models\User\User;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        SITE SETTINGS
    *******************************************************************************/

    /**
     * Test site settings access.
     */
    public function testCanGetSiteSettingsIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/site-settings')
            ->assertStatus(200);
    }

    /**
     * Test site setting editing.
     */
    public function testCanPostEditSiteSetting()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make sure the setting is true so as to consistently test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 1]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/site-settings/is_registration_open', ['value' => 0]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('site_settings', [
            'key'   => 'is_registration_open',
            'value' => 0,
        ]);
    }
}
