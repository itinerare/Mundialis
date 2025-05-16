<?php

namespace Tests\Feature;

use App\Models\User\User;
use App\Services\InvitationService;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase {
    // These tests center on basic user authentication
    // They are modified from https://github.com/dwightwatson/laravel-auth-tests

    /******************************************************************************
        AUTH / REGISTRATION
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();
    }

    /**
     * Test registration page access.
     * This should always succeed, regardless of if registration is currently open.
     */
    public function testCanGetRegisterForm() {
        $this->get('/register')
            ->assertStatus(200);
    }

    /**
     * Test registration.
     *
     * @param bool  $isValid
     * @param bool  $isOpen
     * @param array $code
     * @param bool  $expected
     */
    #[DataProvider('postRegistrationProvider')]
    public function testPostRegistration($isValid, $isOpen, $code, $expected) {
        // Adjust site settings as necessary
        DB::table('site_settings')->where('key', 'is_registration_open')->update(['value' => $isOpen]);

        if ($code[0] && $code[1]) {
            // Create a persistent admin and generate an invitation code
            $admin = User::factory()->admin()->create();
            $invitation = (new InvitationService)->generateInvitation($admin);

            if ($code[2]) {
                // Mark the code used if relevant
                $recipient = User::factory()->create();
                $invitation->update(['recipient_id' => $recipient->id]);
            }

            $invitationCode = $invitation->code;
        } elseif ($code[0] && !$code[1]) {
            // Otherwise generate a fake "code"
            $invitationCode = randomString(15);
        }

        $response = $this->post('register', [
            'name'                  => $this->user->name,
            'email'                 => $this->user->email,
            'password'              => 'password',
            'password_confirmation' => $isValid ? 'password' : 'invalid',
            'agreement'             => $isValid ?? null,
            'code'                  => $code[0] ? $invitationCode : null,
        ]);

        if ($expected) {
            $response->assertStatus(302);
            $response->assertSessionHasNoErrors();
            $this->assertAuthenticated();
        } else {
            if ($isOpen) {
                // Any errors will only be added to the session if registration is open/
                // the form is accessible, so only check in that instance
                $response->assertSessionHasErrors();
            }
            $this->assertGuest();
        }
    }

    public static function postRegistrationProvider() {
        // $code = [$withCode, $isValid, $isUsed]

        return [
            'valid, open, with unused code'      => [1, 1, [1, 1, 0], 1],
            'valid, open, with used code'        => [1, 1, [1, 1, 1], 0],
            'valid, open, with invalid code'     => [1, 1, [1, 0, 0], 0],
            'valid, open, without code'          => [1, 1, [0, 0, 0], 0],
            'valid, closed, with unused code'    => [1, 0, [1, 1, 0], 0],
            'valid, closed, with used code'      => [1, 0, [1, 1, 1], 0],
            'valid, closed, with invalid code'   => [1, 0, [1, 0, 0], 0],
            'valid, closed, without code'        => [1, 0, [0, 0, 0], 0],
            'invalid, open, with unused code'    => [0, 1, [1, 1, 0], 0],
            'invalid, open, with used code'      => [0, 1, [1, 1, 1], 0],
            'invalid, open, with invalid code'   => [0, 1, [1, 0, 0], 0],
            'invalid, open, without code'        => [0, 1, [0, 0, 0], 0],
            'invalid, closed, with unused code'  => [0, 0, [1, 1, 0], 0],
            'invalid, closed, with used code'    => [0, 0, [1, 1, 1], 0],
            'invalid, closed, with invalid code' => [0, 0, [1, 0, 0], 0],
            'invalid, closed, without code'      => [0, 0, [0, 0, 0], 0],
        ];
    }
}
