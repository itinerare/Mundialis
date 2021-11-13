<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class UserNotificationTest extends TestCase
{
    use RefreshDatabase;

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
}
