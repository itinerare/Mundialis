<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\User\User;

use App\Services\ImageManager;

use Tests\TestCase;

class PageImageDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test image deletion access.
     *
     * @return void
     */
    public function test_canGetDeleteImage()
    {
        // Create a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/delete/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Test (soft) image deletion.
     *
     * @return void
     */
    public function test_canPostSoftDeleteImage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/delete/'.$image->id);

        // Verify that the appropriate change has occurred
        $this->assertSoftDeleted($image);
    }

    /**
     * Test (soft) image deletion with a reason.
     *
     * @return void
     */
    public function test_canPostSoftDeleteImageWithReason()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/delete/'.$image->id, $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_versions', [
            'page_image_id' => $page->id,
            'type' => 'Image Deleted',
            'reason' => $data['reason']
        ]);
    }

    /**
     * Test (soft) active image deletion.
     *
     * @return void
     */
    public function test_canPostSoftDeleteActiveImage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Set the page's active image
        $page->update(['image_id' => $image->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/delete/'.$image->id);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'image_id' => null,
        ]);

        $this->assertSoftDeleted($image);
    }

    /**
     * Test deleted image access.
     *
     * @return void
     */
    public function test_canGetDeletedImage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-images/'.$image->id);

        $response->assertStatus(200);
    }

    /**
     * Test image restoration.
     *
     * @return void
     */
    public function test_canPostRestoreImage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-images/'.$image->id.'/restore');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'id' => $image->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test page restoration with a reason.
     *
     * @return void
     */
    public function test_canPostRestoreImageWithReason()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-images/'.$image->id.'/restore', $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_versions', [
            'page_image_id' => $image->id,
            'type' => 'Image Restored',
            'reason' => $data['reason'],
        ]);

        $this->assertDatabaseHas('page_images', [
            'id' => $image->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test image restoration for a deleted page.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostRestoreDeletedPageImage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to attach the image to
        $page = Page::factory()->deleted()->create();

        // Create the image and associated records
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-images/'.$image->id.'/restore');

        // Verify that the appropriate change has occurred
        $this->assertSoftDeleted($image);
    }
}
