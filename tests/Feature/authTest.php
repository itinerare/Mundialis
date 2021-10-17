<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

use App\Models\User\User;

use App\Services\InvitationService;

class authTest extends TestCase
{
    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        REGISTRATION
    *******************************************************************************/

    /**
     * Test registration page access.
     * This should always return positive regardless of
     * whether registration is currently open or not.
     *
     * @return void
     */
    public function test_canGetRegisterForm()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Test registration.
     * A valid user cannot be registered when registration is closed.
     *
     * @return void
     */
    public function test_cannotPostValidRegistrationWhenClosed()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set registration to closed to test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 0]);

        $user = User::factory()->safeUsername()->make();

        // Create a persistent admin to generate an invitation code
        $admin = User::factory()->admin()->create();
        $code = (new InvitationService)->generateInvitation($admin);

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'agreement' => 1,
            'code' => $code->code
        ]);

        $this->assertGuest();
    }

    /**
     * Test registration.
     * Registration requires an invitation code.
     *
     * @return void
     */
    public function test_cannotPostValidRegistrationWhenOpenWithoutCode()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set registration to open to test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 1]);

        $user = User::factory()->safeUsername()->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'agreement' => 1,
            'code' => null
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test registration.
     * Registration requires a valid invitation code.
     *
     * @return void
     */
    public function test_cannotPostValidRegistrationWhenOpenWithInvalidCode()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set registration to open to test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 1]);

        $user = User::factory()->safeUsername()->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'agreement' => 1,
            'code' => randomString(15)
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test registration.
     * Ensure valid user (with unused invitation code) can be registered.
     *
     * @return void
     */
    public function test_canPostValidRegistrationWhenOpenWithCode()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set registration to open to test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 1]);

        $user = User::factory()->safeUsername()->make();

        // Create a persistent admin to generate an invitation code
        $admin = User::factory()->admin()->create();
        $code = (new InvitationService)->generateInvitation($admin);

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'agreement' => 1,
            'code' => $code->code
        ]);

        $response->assertStatus(302);

        $this->assertAuthenticated();
    }

    /**
     * Test registration.
     * Ensure an invalid user cannot be registered.
     *
     * @return void
     */
    public function test_cannotPostInvalidRegistration()
    {
        $user = User::factory()->safeUsername()->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'invalid'
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

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

    /******************************************************************************
        PASSWORD RESET
    *******************************************************************************/

    /**
     * Test password reset access.
     *
     * @return void
     */
    public function test_canGetPasswordReset()
    {
        $response = $this->get('forgot-password');

        $response->assertStatus(200);
    }

    /**
     * Test password reset email with a valid user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostValidPasswordResetEmail()
    {
        $user = User::factory()->create();

        $this->expectsNotification($user, ResetPassword::class);

        $response = $this->post('forgot-password', [
            'email' => $user->email
        ]);

        $response->assertStatus(302);
    }

    /**
     * Test password reset email with an invalid user.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostInvalidPasswordResetEmail()
    {
        $this->doesntExpectJobs(ResetPassword::class);

        $this->post('forgot-password', [
            'email' => 'invalid@email.com'
        ]);
    }

    /**
     * Test password reset form access.
     *
     * @return void
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
     *
     * @return void
     */
    public function test_canResetUserPassword()
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post('reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
