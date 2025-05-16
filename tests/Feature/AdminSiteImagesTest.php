<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminSiteImagesTest extends TestCase {
    /******************************************************************************
        ADMIN / SITE IMAGES
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->make();
    }

    /**
     * Test site image index access.
     */
    public function testGetSiteImagesIndex() {
        $this->actingAs($this->admin)
            ->get('/admin/site-images')
            ->assertStatus(200);
    }

    /**
     * Test image uploading.
     *
     * @param string $key
     */
    #[DataProvider('siteImageProvider')]
    public function testPostUploadImage($key) {
        // Remove the current file if it exists
        if (Storage::fileExists('images/'.$key.'.png')) {
            Storage::delete('images/'.$key.'.png');
        }

        // Create a fake file
        $this->file = UploadedFile::fake()->image('test_image.png');

        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/site-images/upload', [
                'file' => $this->file,
                'key'  => $key,
            ]);

        $response->assertSessionHasNoErrors();
        // Check that the file is now present
        $this->
            assertTrue(Storage::fileExists('/images/'.$key.'.png'));

        // Replace with default images for tidiness
        $this->artisan('app:copy-default-images');
    }

    public static function siteImageProvider() {
        return [
            'logo'       => ['logo'],
            'meta-image' => ['meta-image'],
        ];
    }

    /**
     * Test custom css uploading.
     */
    public function testPostUploadSiteCss() {
        // Create a fake file
        $file = UploadedFile::fake()->create('test.css', 50);

        // Check that the file is absent, and if not, remove it
        if (Storage::fileExists('/css/custom.css')) {
            Storage::delete('/css/custom.css');
        }

        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/site-images/upload/css', [
                'file' => $file,
            ]);

        $response->assertSessionHasNoErrors();
        // Check that the file is now present
        $this->
            assertTrue(Storage::fileExists('/css/custom.css'));

        // Clean up
        Storage::delete('/css/custom.css');
    }
}
