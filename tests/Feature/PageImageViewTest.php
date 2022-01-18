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

class PageImageViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test image modal access.
     *
     * @return void
     */
    public function test_canGetImageModal()
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
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('pages/get-image/'.$page->id.'/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test hidden image modal access.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotGetHiddenImageModal()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a persistent editor
        $editor = User::factory()->editor()->create();
        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->hidden()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('pages/get-image/'.$page->id.'/'.$image->id);

        $response->assertStatus(404);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test hidden image modal access.
     * This should work.
     *
     * @return void
     */
    public function test_canGetHiddenImageModalAsEditor()
    {
        // Create a persistent editor
        $editor = User::factory()->editor()->create();
        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->hidden()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($editor->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($editor)
            ->get('pages/get-image/'.$page->id.'/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test image page access.
     *
     * @return void
     */
    public function test_canGetImagePage()
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
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test hidden image page access.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotGetHiddenImagePage()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a persistent editor
        $editor = User::factory()->editor()->create();
        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->hidden()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/'.$image->id);

        $response->assertStatus(404);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test hidden image page access.
     * This should work.
     *
     * @return void
     */
    public function test_canGetHiddenImagePageAsEditor()
    {
        // Create a persistent editor
        $editor = User::factory()->editor()->create();
        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->hidden()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($image->id)->user($editor->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($editor)
            ->get('/pages/'.$page->id.'/gallery/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }
}
