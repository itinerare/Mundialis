<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        PASSWORD RESET
    *******************************************************************************/

    /**
     * Test password reset access.
     */
    public function test_canGetPasswordReset()
    {
        $response = $this->get('forgot-password');

        $response->assertStatus(200);
    }

    /**
     * Test password reset email with a valid user.
     * This should work.
     */
    public function test_canPostValidPasswordResetEmail()
    {
        $user = User::factory()->create();

        $this->expectsNotification($user, ResetPassword::class);

        $response = $this->post('forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
    }

    /**
     * Test password reset email with an invalid user.
     * This shouldn't work.
     */
    public function test_cannotPostInvalidPasswordResetEmail()
    {
        $this->doesntExpectJobs(ResetPassword::class);

        $this->post('forgot-password', [
            'email' => 'invalid@email.com',
        ]);
    }

    /**
     * Test password reset form access.
     */
    public function test_canGetPasswordResetForm()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get('reset-password/'.$token);

        $response->assertStatus(200);
    }

    /**
     * Test password resetting.
     */
    public function test_canResetUserPassword()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
