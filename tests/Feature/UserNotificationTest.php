<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Notification;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\User\WatchedPage;

class UserNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test notifications access.
     *
     * @return void
     */
    public function test_canGetEmptyNotifications()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/notifications')
            ->assertStatus(200);
    }

    /**
     * Test notifications access with a notification.
     *
     * @return void
     */
    public function test_canGetNotificationsWithNotification()
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
     *
     * @return void
     */
    public function test_canPostClearAllNotificationsEmpty()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear')
            ->assertStatus(302);
    }

    /**
     * Test clearing all notifs with a notification.
     *
     * @return void
     */
    public function test_canPostClearAllNotificationsWithNotification()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to clear
        $notification = Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->post('/notifications/clear');

        // Verify that the notification has been deleted
        $this->assertDeleted($notification);
    }

    /**
     * Test clearing notifs of a set type without any notifications.
     *
     * @return void
     */
    public function test_canPostClearTypedNotificationsEmpty()
    {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/1')
            ->assertStatus(302);
    }

    /**
     * Test clearing notifs of a set type with a notification.
     *
     * @return void
     */
    public function test_canPostClearTypedNotificationsWithNotification()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a notification to clear
        $notification = Notification::factory()->user($user->id)->watchedPageUpdated()->create();

        $response = $this->actingAs($user)
            ->post('/notifications/clear/0');

        // Verify that the notification has been deleted
        $this->assertDeleted($notification);
    }

    /**
     * Test clearing notifs only of a set type, leaving others alone.
     *
     * @return void
     */
    public function test_canPostClearOnlyTypedNotifications()
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
            'id' => $notification->id
        ]);
    }

    /**
     * Test sending a notification by editing a watched page.
     *
     * @return void
     */
    public function test_canSendPageUpdateNotification()
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
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null
        ];

        // Edit the page; this should send a notification to the user
        $response = $this->actingAs($editor)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'notification_type_id' => 0,
        ]);
    }

    /**
     * Test sending a notification by deleting a watched page.
     *
     * @return void
     */
    public function test_canSendPageDeleteNotification()
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
            'user_id' => $user->id,
            'notification_type_id' => 2,
        ]);
    }
}
