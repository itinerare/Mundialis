<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User\User;
use App\Services\InvitationService;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

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
            'code' => $code->code,
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
            'code' => null,
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
            'code' => randomString(15),
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    /**
     * Test registration.
     * Registration requires a valid, unused invitation code.
     *
     * @return void
     */
    public function test_cannotPostValidRegistrationWhenOpenWithUsedCode()
    {
        // Ensure site settings are present to modify
        $this->artisan('add-site-settings');

        // Set registration to open to test
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => 1]);

        $user = User::factory()->safeUsername()->make();

        // Create a persistent admin to generate an invitation code
        $admin = User::factory()->admin()->create();
        // Create a code to use,
        $code = (new InvitationService)->generateInvitation($admin);
        // a recipient,
        $recipient = User::factory()->create();
        // and set the recipient's ID
        $code->update(['recipient_id' => $recipient->id]);

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'agreement' => 1,
            'code' => $code->code,
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
            'code' => $code->code,
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
        // Ensure site settings are present
        $this->artisan('add-site-settings');

        $user = User::factory()->safeUsername()->make();

        $response = $this->post('register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'invalid',
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }
}
