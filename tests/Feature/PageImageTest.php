<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Services\ImageManager;
use Illuminate\Http\UploadedFile;

class PageImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test image upload access.
     *
     * @return void
     */
    public function test_canGetUploadImage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();
        // Create a page to upload the image for
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/create');

        $response->assertStatus(200);
    }

    /**
     * Test image page access.
     *
     * @return void
     */
    public function test_canGetImage()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a persistent editor
        $editor = User::factory()->editor()->create();
        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test page image uploading.
     * This does not work due to Intervention not cooperating in a test environment,
     * but remains here for posterity.
     *
     * @return void
     */
    public function canPostCreateImage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a page for the image to be attached to
        $page = Page::factory()->create();

        // Create a fake image and a fake thumbnail
        $image = UploadedFile::fake()->image('test_image.png');
        $thumbnail = UploadedFile::fake()->image('test_thumb.png');

        // Define some basic data
        $data = [
            'image' => $image,
            'thumbnail' => $thumbnail,
            'x0' => 0, 'x1' => 0,
            'y0' => 0, 'y1' => 0,
            'creator_id' => [0 => $user->id],
            'creator_url' => [0 => null],
            'description' => $this->faker->unique()->domainWord(),
            'is_valid' => 1,
            'is_visible' => 1,
            'mark_active' => 0
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'description' => $data['description'],
            'is_visible' => $data['is_visible'],
        ]);
    }
}
