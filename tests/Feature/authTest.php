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
     * The registration form can be displayed.
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
     * A valid user can be registered.
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
     * An invalid user is not registered.
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
     * The login form can be displayed.
     *
     * @return void
     */
    public function test_canGetLoginForm()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * A valid user can be logged in.
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
     * An invalid user cannot be logged in.
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
     * A logged in user can be logged out.
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
     * Displays the reset password request form.
     *
     * @return void
     */
    public function test_canGetPasswordReset()
    {
        $response = $this->get('forgot-password');

        $response->assertStatus(200);
    }

    /**
     * Sends the password reset email when the user exists.
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
     * Does not send a password reset email when the user does not exist.
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
     * Displays the form to reset a password.
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
     * Allows a user to reset their password.
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
