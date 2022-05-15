<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageVersion;
use App\Models\User\User;
use App\Models\User\WatchedPage;
use App\Services\ImageManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test notifications access.
     */
    public function testCanGetEmptyNotifications()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/notifications')
            ->assertStatus(200);
    }

    /**
     * Test notifications access with a notification.
     */
    public function testCanGetNotificationsWithNotification()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to view
        Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->get('/notifications')
            ->assertStatus(200);
    }

    /**
     * Test clearing all notifs without any notifications.
     */
    public function testCanPostClearAllNotificationsEmpty()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear')
            ->assertStatus(302);
    }

    /**
     * Test clearing all notifs with a notification.
     */
    public function testCanPostClearAllNotificationsWithNotification()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to clear
        $notification = Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->post('/notifications/clear');

        // Verify that the notification has been deleted
        $this->assertModelMissing($notification);
    }

    /**
     * Test clearing notifs of a set type without any notifications.
     */
    public function testCanPostClearTypedNotificationsEmpty()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/1')
            ->assertStatus(302);
    }

    /**
     * Test clearing notifs of a set type with a notification.
     */
    public function testCanPostClearTypedNotificationsWithNotification()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to clear
        $notification = Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/0');

        // Verify that the notification has been deleted
        $this->assertModelMissing($notification);
    }

    /**
     * Test clearing notifs only of a set type, leaving others alone.
     */
    public function testCanPostClearOnlyTypedNotifications()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to persist
        $notification = Notification::factory()->user($user->id)->watchedPageDeleted()->create();

        // Create a notification to clear
        Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/0');

        // Verify that the notification has been deleted
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
        ]);
    }

    /**
     * Test sending a notification by editing a watched page.
     */
    public function testCanSendPageUpdateNotification()
    {
        // Make a persistent user to receive the notification
        $user = User::factory()->create();
        // Make a persistent editor to make changes
        $editor = User::factory()->editor()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->create();

        // Create a page watch record
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        // Define some basic data
        $data = [
            'title'   => $this->faker->unique()->domainWord(),
            'summary' => null,
        ];

        // Edit the page; this should send a notification to the user
        $response = $this->actingAs($editor)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('notifications', [
            'user_id'              => $user->id,
            'notification_type_id' => 0,
        ]);
    }

    /**
     * Test basic image editing.
     */
    public function testCanSendPageImageUpdateNotification()
    {
        // Make a persistent user to receive the notification
        $user = User::factory()->create();
        // Make a persistent editor to make changes
        $editor = User::factory()->editor()->create();

        // Create a page to watch & attach the image to
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->create();

        // Create a page watch record
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($editor->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => $this->faker->unique()->domainWord(),
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($editor)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('notifications', [
            'user_id'              => $user->id,
            'notification_type_id' => 1,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test sending a notification by deleting a watched page.
     */
    public function testCanSendPageDeleteNotification()
    {
        // Make a persistent user to receive the notification
        $user = User::factory()->create();
        // Make a persistent editor to make changes
        $editor = User::factory()->editor()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->create();

        // Create a page watch record
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        // Edit the page; this should send a notification to the user
        $response = $this->actingAs($editor)
            ->post('/pages/'.$page->id.'/delete');

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('notifications', [
            'user_id'              => $user->id,
            'notification_type_id' => 2,
        ]);
    }
}
