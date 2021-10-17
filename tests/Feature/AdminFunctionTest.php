<?php

namespace Tests\Feature;

use DB;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

use App\Models\SitePage;

use App\Models\User\User;
use App\Models\User\Rank;
use App\Models\User\InvitationCode;

use App\Services\InvitationService;

class AdminFunctionTest extends TestCase
{
    // These tests center on the miscellaneous admin functions present within the site

    /******************************************************************************
        INVITATIONS
    *******************************************************************************/

    /**
     * Test invitation code index access.
     *
     * @return void
     */
    public function test_canGetInvitationIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/invitations')
            ->assertStatus(200);
    }

    /**
     * Test invitation code creation.
     *
     * @return void
     */
    public function test_canPostCreateInvitation()
    {
        // Count currently extant invitation codes
        $oldCount = InvitationCode::all()->count();

        // Make a persistent user
        $user = User::factory()->admin()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/create');

        // Check that there are more invitation codes than before
        $this->assertTrue(InvitationCode::all()->count() > $oldCount);
    }

    /**
     * Test invitation code deletion.
     *
     * @return void
     */
    public function test_canPostDeleteInvitation()
    {
        // Make a persistent user (in case a code needs to be generated)
        $user = User::factory()->admin()->create();

        // Count existing codes
        $oldCount = InvitationCode::all()->count();
        // Find or create a code to delete
        $code =
            InvitationCode::where('recipient_id', null)->first() ?
            InvitationCode::where('recipient_id', null)->first() :
            (new InvitationService)->generateInvitation($user);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/delete/'.$code->id);

        // Check that there are fewer invitation codes than before
        $this->assertTrue(InvitationCode::all()->count() < $oldCount);
    }

    /**
     * Ensure a used invitation code cannot be deleted.
     *
     * @return void
     */
    public function test_cannotPostDeleteUsedInvitation()
    {
        // Make a persistent user (in case a code needs to be generated)
        $user = User::factory()->admin()->create();

        // Count existing codes
        $oldCount = InvitationCode::all()->count();
        // Find or create a code to attempt to delete
        $code =
            InvitationCode::where('recipient_id', '!=', null)->first() ?
            InvitationCode::where('recipient_id', '!=', null)->first() :
            (new InvitationService)->generateInvitation($user);

        // If necessary, simulate a "used" code
        if($code->recipient_id == null) {
            // Create a persistent user and mark them as the code's recipient
            $recipient = User::factory()->create();
            $code->update(['recipient_id' => $recipient->id]);
        }

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/delete/'.$code->id);

        // Check that there are the same number of invitation codes or greater
        $this->assertTrue(InvitationCode::all()->count() >= $oldCount);
    }

    /******************************************************************************
        RANKS
    *******************************************************************************/

    /**
     * Test rank index access.
     *
     * @return void
     */
    public function test_canGetRankIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/ranks')
            ->assertStatus(200);
    }

    /**
     * Test rank edit access.
     *
     * @return void
     */
    public function test_canGetEditRank()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/ranks/edit/'.$rank->id)
            ->assertStatus(200);
    }

    /**
     * Test rank editing.
     *
     * @return void
     */
    public function test_canPostEditRank()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();
        // Get the information for the lowest rank
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Make sure the setting is default so as to consistently test
        $rank->update(['description' => 'A regular member of the site.']);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/ranks/edit/'.$rank->id, [
                'name' => 'Member',
                'description' => 'TEST SUCCESS'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('ranks', [
            'name' => 'Member',
            'description' => 'TEST SUCCESS'
        ]);
    }

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
            ->post('/admin/pages/edit/'.$page->id, [
                'text' => 'TEST SUCCESS'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('site_pages', [
            'key' => 'about',
            'text' => 'TEST SUCCESS'
        ]);
    }

    /******************************************************************************
        SITE SETTINGS
    *******************************************************************************/

    /**
     * Test site settings access.
     *
     * @return void
     */
    public function test_canGetSiteSettingsIndex()
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
     *
     * @return void
     */
    public function test_canPostEditSiteSetting()
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
            'key' => 'is_registration_open',
            'value' => 0
        ]);
    }

    /******************************************************************************
        SITE IMAGES
    *******************************************************************************/

    /**
     * Test site image index access.
     *
     * @return void
     */
    public function test_canGetSiteImagesIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/site-images')
            ->assertStatus(200);
    }

    /**
     * Test site image uploading.
     *
     * @return void
     */
    public function test_canPostEditSiteImage()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Create a fake file
        $file = UploadedFile::fake()->image('test_image.png');

        // Remove the current logo file if it exists
        if(File::exists(public_path('images/logo.png')))
            unlink('public/images/logo.png');

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/site-images/upload', [
                'file' => $file,
                'key' => 'logo'
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('images/logo.png')));

        // Replace with default images for tidiness
        $this->artisan('copy-default-images');
    }

    /**
     * Test custom css uploading.
     *
     * @return void
     */
    public function test_canPostEditSiteCss()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Create a fake file
        $file = UploadedFile::fake()->create('test.css', 50);

        // Check that the file is absent, and if not, remove it
        if(File::exists(public_path('css/custom.css')))
            unlink('public/css/custom.css');

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/site-images/upload/css', [
                'file' => $file
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('css/custom.css')));

        // Clean up
        unlink('public/css/custom.css');
    }
}
