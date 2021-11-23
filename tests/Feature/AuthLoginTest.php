<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

use App\Models\User\User;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        LOGIN
    *******************************************************************************/

    /**
     * Test login form access.
     *
     * @return void
     */
    public function test_canGetLoginForm()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test login as a valid user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostValidLogin()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login as an invalid user.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostInvalidLogin()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'invalid'
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function test_canPostLogout()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }
}
