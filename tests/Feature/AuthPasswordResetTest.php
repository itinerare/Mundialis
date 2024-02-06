<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthPasswordResetTest extends TestCase {
    use RefreshDatabase;

    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        AUTH / PASSWORD RESET
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();
    }

    /**
     * Test password reset access.
     */
    public function testGetPasswordReset() {
        $this->get('forgot-password')
            ->assertStatus(200);
    }

    /**
     * Test password reset email.
     *
     * @dataProvider passwordResetProvider
     *
     * @param bool $isValid
     * @param bool $expected
     */
    public function testPostPasswordResetEmail($isValid, $expected) {
        if ($isValid) {
            $user = User::factory()->create();
            $this->expectsNotification($user, ResetPassword::class);
        } else {
            $this->doesntExpectJobs(ResetPassword::class);
        }

        $response = $this->post('forgot-password', [
            'email' => $isValid ? $user->email : 'invalid@email.com',
        ]);

        if ($expected) {
            $response->assertStatus(302);
            $response->assertSessionHasNoErrors();
        } else {
            $response->assertSessionHasErrors();
        }
    }

    public static function passwordResetProvider() {
        return [
            'valid user'   => [1, 1],
            'invalid user' => [0, 0],
        ];
    }

    /**
     * Test password reset form access.
     */
    public function testGetPasswordResetForm() {
        $token = Password::createToken($this->user);

        $this->get('reset-password/'.$token)
            ->assertStatus(200);
    }

    /**
     * Test password resetting.
     */
    public function testResetUserPassword() {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
