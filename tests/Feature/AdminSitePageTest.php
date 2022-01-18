<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SitePage;
use App\Models\User\User;

class AdminSitePageTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        SITE PAGES
    *******************************************************************************/

    /**
     * Test site page index access.
     *
     * @return void
     */
    public function test_canGetSitePageIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/pages')
            ->assertStatus(200);
    }

    /**
     * Test site page editing.
     *
     * @return void
     */
    public function test_canPostEditSitePage()
    {
        // Ensure site pages are present to modify
        $this->artisan('add-site-pages');

        // Make a temporary user
        $user = User::factory()->admin()->make();
        // Get the information for the 'about' page
        $page = SitePage::where('key', 'about')->first();

        // Make sure the setting is default so as to consistently test
        $page->update(['text' => 'Info about your site goes here. This can be edited from the site\'s admin panel!']);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/pages/edit/' . $page->id, [
                'text' => 'TEST SUCCESS',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('site_pages', [
            'key' => 'about',
            'text' => 'TEST SUCCESS',
        ]);
    }
}
