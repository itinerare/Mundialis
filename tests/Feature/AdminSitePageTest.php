<?php

namespace Tests\Feature;

use App\Models\SitePage;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminSitePageTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        ADMIN / SITE PAGES
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->artisan('add-site-pages');
        $this->admin = User::factory()->admin()->make();
    }

    /**
     * Test site page index access.
     */
    public function testGetSitePageIndex() {
        $this->actingAs($this->admin)
            ->get('/admin/pages')
            ->assertStatus(200);
    }

    /**
     * Test site page editing.
     *
     * @dataProvider sitePageProvider
     *
     * @param string $key
     */
    public function testPostEditSitePage($key) {
        // Get the information for the page
        $page = SitePage::where('key', $key)->first();

        // Generate some test data
        $text = '<p>'.$this->faker->unique()->domainWord().'</p>';

        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/pages/edit/'.$page->id, [
                'text' => $text,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('site_pages', [
            'key'  => $key,
            'text' => $text,
        ]);
    }

    public static function sitePageProvider() {
        return [
            'about'            => ['about'],
            'privacy policy'   => ['privacy'],
            'terms of service' => ['terms'],
        ];
    }
}
