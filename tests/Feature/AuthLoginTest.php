<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase {
    use RefreshDatabase;

    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        LOGIN
    *******************************************************************************/

    /**
     * Test login form access.
     */
    public function testCanGetLoginForm() {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test login as a valid user.
     * This should work.
     */
    public function testCanPostValidLogin() {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login as an invalid user.
     * This shouldn't work.
     */
    public function testCannotPostInvalidLogin() {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'invalid',
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test user logout.
     */
    public function testCanPostLogout() {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }
}
