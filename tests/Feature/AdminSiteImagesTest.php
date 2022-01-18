<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminSiteImagesTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        SITE IMAGES
    *******************************************************************************/

    /**
     * Test site image index access.
     *
     * @return void
     */
    public function test_canGetSiteImagesIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/site-images')
            ->assertStatus(200);
    }

    /**
     * Test site image uploading.
     *
     * @return void
     */
    public function test_canPostEditSiteImage()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Create a fake file
        $file = UploadedFile::fake()->image('test_image.png');

        // Remove the current logo file if it exists
        if (File::exists(public_path('images/logo.png'))) {
            unlink('public/images/logo.png');
        }

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/site-images/upload', [
                'file' => $file,
                'key'  => 'logo',
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('images/logo.png')));

        // Replace with default images for tidiness
        $this->artisan('copy-default-images');
    }

    /**
     * Test custom css uploading.
     *
     * @return void
     */
    public function test_canPostEditSiteCss()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Create a fake file
        $file = UploadedFile::fake()->create('test.css', 50);

        // Check that the file is absent, and if not, remove it
        if (File::exists(public_path('css/custom.css'))) {
            unlink('public/css/custom.css');
        }

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/site-images/upload/css', [
                'file' => $file,
            ]);

        // Check that the file is now present
        $this->
            assertTrue(File::exists(public_path('css/custom.css')));

        // Clean up
        unlink('public/css/custom.css');
    }
}
