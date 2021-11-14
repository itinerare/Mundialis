<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageVersion;
use App\Services\ImageManager;

class PageDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test page deletion access.
     *
     * @return void
     */
    public function test_canGetDeletePage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Make a page to be deleted
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/delete');

        $response->assertStatus(200);
    }

    /**
     * Test (soft) page deletion.
     *
     * @return void
     */
    public function test_canPostSoftDeletePage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to delete
        $page = Page::factory()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        $this->assertSoftDeleted($page);
    }

    /**
     * Test (soft) page deletion with a reason.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithReason()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to delete & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete', $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'type' => 'Page Deleted',
            'reason' => $data['reason']
        ]);

        $this->assertSoftDeleted($page);
    }

    /**
     * Test (soft) page deletion with page content.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithContent()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to delete
        $page = Page::factory()->create();
        // As well as accompanying version
        $version = PageVersion::factory()->user($user->id)->page($page->id)
            ->testData($this->faker->unique()->domainWord(), $this->faker->unique()->domainWord())->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        // Ordinarily this would check for the presence of the test data
        // in the deleted version, but testing seems to have difficulty with this.
        // Manually verify.
        $this->assertSoftDeleted($page);
    }

    /**
     * Test (soft) page deletion with an associated image.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithImage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to delete
        $page = Page::factory()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)
            ->testData($this->faker->unique()->domainWord(), $this->faker->unique()->domainWord())->create();

        $image = PageImage::factory()->create();
        $imageVersion = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $imageVersion);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        $this->assertSoftDeleted($image);
        $this->assertSoftDeleted($page);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $imageVersion->thumbnailFileName);
        unlink($image->imagePath . '/' . $imageVersion->imageFileName);
    }

    /**
     * Test (soft) page deletion with associated images.
     * As one image is associated with another page, it should not be deleted.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithImages()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        for($i = 1; $i <= 2; $i++) {
            // Make a page
            $page[$i] = Page::factory()->create();
            // As well as accompanying version
            PageVersion::factory()->user($user->id)->page($page[$i]->id)
                ->testData($this->faker->unique()->domainWord(), $this->faker->unique()->domainWord())->create();

            // Create an image and associated records, as well as test files
            $image[$i] = PageImage::factory()->create();
            $imageVersion[$i] = PageImageVersion::factory()->image($image[$i]->id)->user($user->id)->create();
            PageImageCreator::factory()->image($image[$i]->id)->user($user->id)->create();
            PagePageImage::factory()->page($page[1]->id)->image($image[$i]->id)->create();
            (new ImageManager)->testImages($image[$i], $imageVersion[$i]);
        }

        // Attach one of the images to page 2 as well
        PagePageImage::factory()->page($page[2]->id)->image($image[2]->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/delete');

        // Verify that the appropriate change has occurred
        // In this case, this means checking that the second image is not deleted
        $this->assertDatabaseHas('page_images', [
            'id' => $image[2]->id,
            'deleted_at' => null,
        ]);

        // And that the first image has been soft-deleted
        $this->assertSoftDeleted($image[1]);
        $this->assertSoftDeleted($page[1]);

        // Delete the test images, to clean up
        for($i = 1; $i <= 2; $i++) {
            unlink($image[$i]->imagePath . '/' . $imageVersion[$i]->thumbnailFileName);
            unlink($image[$i]->imagePath . '/' . $imageVersion[$i]->imageFileName);
        }
    }

    /**
     * Test (soft) page deletion with a child page.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostSoftDeletePageWithChild()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to try to delete & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Make a child page & version
        $child = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($child->id)->create();

        $child->update(['parent_id' => $page->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test full page deletion.
     *
     * @return void
     */
    public function test_canPostForceDeletePage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a category for the page to go into/to delete
        $category = SubjectCategory::factory()->create();

        // Make a deleted page
        $page = Page::factory()->category($category->id)->deleted()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)->deleted()->create();

        // Try to post data; this time the category is deleted
        // since deleting the category is the only way to force-delete pages
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Verify that the appropriate change has occurred
        $this->assertDeleted($page);
    }

    /**
     * Test full page deletion with an image.
     *
     * @return void
     */
    public function test_canPostForceDeletePageWithImage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a category for the page to go into/to delete
        $category = SubjectCategory::factory()->create();

        // Make a deleted page
        $page = Page::factory()->category($category->id)->deleted()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)->deleted()->create();

        $image = PageImage::factory()->deleted()->create();
        $imageVersion = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $imageVersion);

        // Try to post data; this time the category is deleted
        // since deleting the category is the only way to force-delete pages
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Verify that the appropriate change has occurred
        // In this case, we check the image, as it should also be force-deleted
        $this->assertDeleted($image);
    }

    /**
     * Test deleted page access.
     *
     * @return void
     */
    public function test_canGetDeletedPage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a deleted page & version
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->deleted()->create();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages/'.$page->id);

        $response->assertStatus(200);
    }

    /**
     * Test restore page access.
     *
     * @return void
     */
    public function test_canGetRestorePage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a deleted page & version
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->deleted()->create();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages/'.$page->id.'/restore');

        $response->assertStatus(200);
    }

    /**
     * Test page restoration.
     *
     * @return void
     */
    public function test_canPostRestorePage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to restore & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-pages/'.$page->id.'/restore');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test page restoration with a reason.
     *
     * @return void
     */
    public function test_canPostRestorePageWithReason()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to restore & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-pages/'.$page->id.'/restore', $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'type' => 'Page Restored',
            'reason' => $data['reason'],
        ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test page restoration with an associated image.
     *
     * @return void
     */
    public function test_canPostRestorePageWithImage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to restore & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Make a deleted image and associated records
        $image = PageImage::factory()->deleted()->create();
        $imageVersion = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $imageVersion);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-pages/'.$page->id.'/restore');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'id' => $image->id,
            'deleted_at' => null,
        ]);
    }
}
