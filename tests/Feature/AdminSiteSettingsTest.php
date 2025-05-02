<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminSiteSettingsTest extends TestCase {
    /******************************************************************************
        ADMIN / SITE SETTINGS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->make();
    }

    /**
     * Test site settings access.
     */
    public function testCanGetSiteSettingsIndex() {
        $this->actingAs($this->admin)
            ->get('/admin/site-settings')
            ->assertStatus(200);
    }

    /**
     * Test site setting editing.
     */
    public function testCanPostEditSiteSetting() {
        // Ensure site settings are present to modify
        $this->artisan('app:add-site-settings');

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

    /**
     * Test site setting editing.
     *
     * @param string $key
     * @param bool   $value
     * @param bool   $expected
     */
    #[DataProvider('settingsProvider')]
    public function testPostEditSiteSetting($key, $value, $expected) {
        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/site-settings/'.$key, [$key.'_value' => $value]);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('site_settings', [
                'key'   => $key,
                'value' => $value,
            ]);
        } else {
            $response->assertSessionHasErrors();
        }
    }

    public static function settingsProvider() {
        return [
            'open site'         => ['visitors_can_read', 0, 1],
            'open registration' => ['is_registration_open', 0, 1],
            'invalid setting'   => ['invalid', 1, 0],
        ];
    }
}
