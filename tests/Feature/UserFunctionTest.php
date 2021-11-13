<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\User\WatchedPage;

class UserFunctionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        SETTINGS
    *******************************************************************************/

    /**
     * Test profile editing.
     *
     * @return void
     */
    public function test_canPostEditProfile()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/profile', [
                'profile_text' => 'Profile editing test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'profile_text' => 'Profile editing test'
        ]);
    }

    /**
     * Test avatar editing.
     *
     * @return void
     */
    public function test_canPostEditAvatar()
    {
        // Make a temporary user
        $user = User::factory()->create();

        // Fake public disk
        Storage::fake('public');

        // Create a fake file
        $file = UploadedFile::fake()->image('test_image.png');

        // Remove the current avatar if it exists
        if(File::exists(public_path('images/avatars/'.$user->id.'.png')))
            unlink('public/images/avatars/'.$user->id.'.png');

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/account/avatar', [
                'avatar' => $file
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('images/avatars/'.$user->id.'.png')));
    }

    /**
     * Test email editing.
     *
     * @return void
     */
    public function test_canPostEditEmail()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Generate an email address
        $email = $this->faker->unique()->safeEmail();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/email', [
                'email' => $email
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $email
        ]);
    }

    /**
     * Test password editing with a valid password.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditValidPassword()
    {
        // Make a persistent user
        $user = User::factory()->simplePass()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/password', [
                'old_password' => 'simple_password',
                'new_password' => 'password',
                'new_password_confirmation' => 'password'
            ]);

        $this->
            assertTrue(Hash::check('password', $user->fresh()->password));

    }

    /**
     * Test password editing with an invalid password.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostEditInvalidPassword()
    {
        // Make a persistent user
        $user = User::factory()->simplePass()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/password', [
                'old_password' => 'simple_password',
                'new_password' => 'password',
                'new_password_confirmation' => 'not_password'
            ]);

        $response->assertSessionHasErrors();
    }

    /******************************************************************************
        NOTIFICATIONS
    *******************************************************************************/

    /**
     * Test notifications access.
     *
     * @return void
     */
    public function test_canGetNotifications()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/notifications')
            ->assertStatus(200);
    }

    /**
     * Test clearing all notifs.
     *
     * @return void
     */
    public function test_canPostClearAllNotifications()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear')
            ->assertStatus(302);
    }

    /**
     * Test clearing notifs of a set type.
     *
     * @return void
     */
    public function test_canPostClearTypedNotifications()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/1')
            ->assertStatus(302);
    }

    /******************************************************************************
        WATCHED PAGES
    *******************************************************************************/

    /**
     * Test watched pages access.
     *
     * @return void
     */
    public function test_canGetWatchedPages()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/account/watched-pages')
            ->assertStatus(200);
    }

    /**
     * Test watched pages access with a watched page.
     *
     * @return void
     */
    public function test_canGetWatchedPagesWithPage()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        // Create a page watch record
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        $response = $this->actingAs($user)
            ->get('/account/watched-pages')
            ->assertStatus(200);
    }

    /**
     * Test watching a page.
     *
     * @return void
     */
    public function test_canPostWatchPage()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->post('/account/watched-pages/'.$page->id);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('watched_pages', [
            'user_id' => $user->id,
            'page_id' => $page->id
        ]);
    }

    /**
     * Test unwatching a page.
     *
     * @return void
     */
    public function test_canPostUnwatchPage()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        // Create a page watch record to remove
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        $response = $this->actingAs($user)
            ->post('/account/watched-pages/'.$page->id);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseMissing('watched_pages', [
            'user_id' => $user->id,
            'page_id' => $page->id
        ]);
    }
}
