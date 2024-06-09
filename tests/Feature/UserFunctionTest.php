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

class UserFunctionTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        USER / SETTINGS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Test profile editing.
     */
    public function testPostEditProfile() {
        // Generate some test data
        $text = '<p>'.$this->faker->unique()->domainWord().'</p>';

        $response = $this->actingAs($this->user)
            ->post('account/profile', [
                'profile_text' => $text,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'id'           => $this->user->id,
            'profile_text' => $text,
        ]);
    }

    /**
     * Test avatar editing.
     */
    public function testPostEditAvatar() {
        // Fake public disk & create a fake image
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test_image.png');

        // Remove the current avatar if it exists
        if (File::exists(public_path('images/avatars/'.$this->user->id.'.png'))) {
            unlink('public/images/avatars/'.$this->user->id.'.png');
        }

        $response = $this
            ->actingAs($this->user)
            ->post('/account/avatar', [
                'avatar' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('images/avatars/'.$this->user->id.'.png')));

        unlink(public_path('images/avatars/'.$this->user->id.'.png'));
    }

    /**
     * Test email editing.
     *
     * @dataProvider userEditProvider
     *
     * @param bool $isValid
     * @param bool $expected
     */
    public function testPostEditEmail($isValid, $expected) {
        // Generate some test data
        if ($isValid) {
            $email = $this->faker->unique()->safeEmail();
        } else {
            $email = $this->faker->domainWord();
        }

        $response = $this->actingAs($this->user)
            ->post('account/email', [
                'email' => $email,
            ]);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('users', [
                'id'    => $this->user->id,
                'email' => $email,
            ]);
        } else {
            $response->assertSessionHasErrors();
        }
    }

    /**
     * Test password editing.
     *
     * @dataProvider userEditProvider
     *
     * @param bool $isValid
     * @param bool $expected
     */
    public function testPostEditPassword($isValid, $expected) {
        // Make a persistent user with a simple password
        $user = User::factory()->simplePass()->create();

        $response = $this->actingAs($user)
            ->post('account/password', [
                'old_password'              => 'simple_password',
                'new_password'              => 'password',
                'new_password_confirmation' => $isValid ? 'password' : 'not_password',
            ]);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertTrue(Hash::check('password', $user->fresh()->password));
        } else {
            $response->assertSessionHasErrors();
        }
    }

    public static function userEditProvider() {
        return [
            'valid'   => [1, 1],
            'invalid' => [0, 0],
        ];
    }
}
