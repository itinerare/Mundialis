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
        AUTH / LOGIN
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();
    }

    /**
     * Test login form access.
     */
    public function testGetLoginForm() {
        $this
            ->get('/login')
            ->assertStatus(200);
    }

    /**
     * Test login as a valid user.
     * This should work.
     */
    public function testPostValidLogin() {
        $user = User::factory()->create();

        $this
            ->post('/login', [
                'email'    => $user->email,
                'password' => 'password',
            ])->assertStatus(302);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login as an invalid user.
     * This shouldn't work.
     */
    public function testnotPostInvalidLogin() {
        $user = User::factory()->create();

        $this
            ->post('/login', [
                'email'    => $user->email,
                'password' => 'invalid',
            ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test user logout.
     */
    public function testPostLogout() {
        $this
            ->actingAs(User::factory()->create())
            ->post('/logout')
            ->assertStatus(302);

        $this->assertGuest();
    }
}
