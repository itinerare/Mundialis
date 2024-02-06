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

class UserNotificationTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        USER / NOTIFICATIONS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Test notifications access.
     *
     * @dataProvider getNotificationsProvider
     *
     * @param bool $withNotification
     * @param int  $status
     */
    public function testGetNotifications($withNotification, $status) {
        if ($withNotification) {
            // Create a notification to view
            Notification::factory()->user($this->user->id)->watchedPageUpdated()->create();
        }

        $this->actingAs($this->user)
            ->get('/notifications')
            ->assertStatus($status);
    }

    public static function getNotificationsProvider() {
        return [
            'empty'             => [0, 200],
            'with notification' => [1, 200],
        ];
    }

    /**
     * Test clearing all notifications.
     *
     * @dataProvider postClearNotificationsProvider
     *
     * @param bool $withNotification
     */
    public function testPostClearAllNotifications($withNotification) {
        if ($withNotification) {
            // Create a notification to clear
            $notification = Notification::factory()->user($this->user->id)->watchedPageUpdated()->create();
        }

        // This operation should always result in a redirect
        $response = $this->actingAs($this->user)
            ->post('/notifications/clear')
            ->assertStatus(302);

        $response->assertSessionHasNoErrors();
        if ($withNotification) {
            $this->assertModelMissing($notification);
        }
    }

    public static function postClearNotificationsProvider() {
        return [
            'empty'             => [0],
            'with notification' => [1],
        ];
    }

    /**
     * Test clearing notifications of a set type.
     *
     * @dataProvider postClearTypedNotificationsProvider
     *
     * @param bool $withNotification
     * @param bool $withUnrelated
     */
    public function testPostClearTypedNotifications($withNotification, $withUnrelated) {
        if ($withNotification) {
            // Create a notification to clear
            $notification = Notification::factory()->user($this->user->id)->watchedPageUpdated()->create();
        }

        if ($withUnrelated) {
            // Create an unrelated notification that should not be cleared
            $unrelatedNotification = Notification::factory()->user($this->user->id)->watchedPageDeleted()->create();
        }

        // This operation should always result in a redirect
        $response = $this->actingAs($this->user)
            ->post('/notifications/clear/0')
            ->assertStatus(302);

        $response->assertSessionHasNoErrors();
        if ($withNotification) {
            $this->assertModelMissing($notification);
        }
        if ($withUnrelated) {
            $this->assertModelExists($unrelatedNotification);
        }
    }

    public static function postClearTypedNotificationsProvider() {
        return [
            'empty'                                 => [0, 0],
            'with notification'                     => [1, 0],
            'with unrelated notif'                  => [0, 1],
            'with notification and unrelated notif' => [1, 1],
        ];
    }

    /**
     * Test sending notifications.
     *
     * @dataProvider sendNotificationsProvider
     *
     * @param int  $type
     * @param bool $userWatched
     * @param bool $editorWatched
     */
    public function testSendNotification($type, $userWatched, $editorWatched) {
        // Make a persistent editor to make changes
        $editor = User::factory()->editor()->create();

        // Create a page & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->create();

        // Create page watch record(s)
        if ($userWatched) {
            WatchedPage::factory()->user($this->user->id)->page($page->id)->create();
        }
        if ($editorWatched) {
            WatchedPage::factory()->user($editor->id)->page($page->id)->create();
        }

        switch ($type) {
            case 0:
                // WATCHED_PAGE_UPDATED

                // Generate some test data
                $data = [
                    'title'   => $this->faker->unique()->domainWord(),
                    'summary' => null,
                ];

                // Edit the page; this should prompt a notification if relevant
                $response = $this->actingAs($editor)
                    ->post('/pages/'.$page->id.'/edit', $data);
                break;
            case 1:
                // WATCHED_PAGE_IMAGE_UPDATED

                // Create the image and associated records
                $image = PageImage::factory()->create();
                $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
                PageImageCreator::factory()->image($image->id)->user($editor->id)->create();
                PagePageImage::factory()->page($page->id)->image($image->id)->create();
                (new ImageManager)->testImages($image, $version);

                // Generate some test data
                $data = [
                    'description' => $this->faker->unique()->domainWord(),
                    'creator_id'  => [0 => $this->user->id],
                    'creator_url' => [0 => null],
                ];

                // Edit the image; this should prompt a notification if relevant
                $response = $this
                    ->actingAs($editor)
                    ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

                // Clean up the test images
                unlink($image->imagePath.'/'.$version->thumbnailFileName);
                unlink($image->imagePath.'/'.$version->imageFileName);
                break;
            case 2:
                // WATCHED_PAGE_DELETED
                $response = $this->actingAs($editor)
                    ->post('/pages/'.$page->id.'/delete');
                break;
        }

        $response->assertSessionHasNoErrors();
        if ($userWatched) {
            $this->assertDatabaseHas('notifications', [
                'user_id'              => $this->user->id,
                'notification_type_id' => $type,
            ]);
        } else {
            $this->assertDatabaseMissing('notifications', [
                'user_id'              => $this->user->id,
                'notification_type_id' => $type,
            ]);
        }
        if ($editorWatched) {
            $this->assertDatabaseMissing('notifications', [
                'user_id'              => $editor,
                'notification_type_id' => $type,
            ]);
        }

        if (!$userWatched && !$editorWatched) {
            $this->assertDatabaseEmpty('notifications');
        }
    }

    public static function sendNotificationsProvider() {
        return [
            'watched page updated, no watchers'            => [0, 0, 0],
            'watched page updated, user watcher'           => [0, 1, 0],
            'watched page updated by editor watcher'       => [0, 0, 1],
            'watched page updated, both watchers'          => [0, 1, 1],
            'watched page image updated, no watchers'      => [1, 0, 0],
            'watched page image updated, user watcher'     => [1, 1, 0],
            'watched page image updated by editor watcher' => [1, 0, 1],
            'watched page image updated, both watchers'    => [1, 1, 1],
            'watched page deleted, no watchers'            => [2, 0, 0],
            'watched page deleted, user watcher'           => [2, 1, 0],
            'watched page deleted by editor watcher'       => [2, 0, 1],
            'watched page deleted, both watchers'          => [2, 1, 1],
        ];
    }
}
