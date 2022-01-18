<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserFunctionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /******************************************************************************
        SETTINGS
    *******************************************************************************/

    /**
     * Test profile editing.
     *
     * @return void
     */
    public function test_canPostEditProfile()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/profile', [
                'profile_text' => 'Profile editing test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'         => $user->name,
            'profile_text' => 'Profile editing test',
        ]);
    }

    /**
     * Test avatar editing.
     *
     * @return void
     */
    public function test_canPostEditAvatar()
    {
        // Make a temporary user
        $user = User::factory()->create();

        // Fake public disk
        Storage::fake('public');

        // Create a fake file
        $file = UploadedFile::fake()->image('test_image.png');

        // Remove the current avatar if it exists
        if (File::exists(public_path('images/avatars/'.$user->id.'.png'))) {
            unlink('public/images/avatars/'.$user->id.'.png');
        }

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/account/avatar', [
                'avatar' => $file,
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('images/avatars/'.$user->id.'.png')));
    }

    /**
     * Test email editing.
     *
     * @return void
     */
    public function test_canPostEditEmail()
    {
        // Make a persistent user
        $user = User::factory()->create();

        // Generate an email address
        $email = $this->faker->unique()->safeEmail();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/email', [
                'email' => $email,
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'  => $user->name,
            'email' => $email,
        ]);
    }

    /**
     * Test password editing with a valid password.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditValidPassword()
    {
        // Make a persistent user
        $user = User::factory()->simplePass()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/password', [
                'old_password'              => 'simple_password',
                'new_password'              => 'password',
                'new_password_confirmation' => 'password',
            ]);

        $this->
            assertTrue(Hash::check('password', $user->fresh()->password));
    }

    /**
     * Test password editing with an invalid password.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostEditInvalidPassword()
    {
        // Make a persistent user
        $user = User::factory()->simplePass()->create();

        // Attempt to post data
        $response = $this->actingAs($user)
            ->post('account/password', [
                'old_password'              => 'simple_password',
                'new_password'              => 'password',
                'new_password_confirmation' => 'not_password',
            ]);

        $response->assertSessionHasErrors();
    }
}
