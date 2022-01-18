<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PageLink;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageProtection;
use App\Models\Page\PageVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Services\ImageManager;

class SpecialPageTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /******************************************************************************
        MAINTENANCE REPORTS
    *******************************************************************************/

    /**
     * Tests all special pages access.
     *
     * @return void
     */
    public function test_canGetSpecialPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special');

        $response->assertStatus(200);
    }

    /**
     * Tests untagged pages access.
     *
     * @return void
     */
    public function test_canGetUntaggedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/untagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests untagged pages access with an untagged page.
     *
     * @return void
     */
    public function test_canGetUntaggedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData()->create();

        $response = $this->actingAs($user)
            ->get('/special/untagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most tagged pages access.
     *
     * @return void
     */
    public function test_canGetMostTaggedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/tagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most tagged pages access with a tagged page.
     *
     * @return void
     */
    public function test_canGetMostTaggedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, null, '"'.$this->faker->unique()->domainWord().'", "'.$this->faker->unique()->domainWord().'"')->create();

        $response = $this->actingAs($user)
            ->get('/special/tagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests least revised pages access.
     *
     * @return void
     */
    public function test_canGetLeastRevisedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/least-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests least revised pages access with a page.
     *
     * @return void
     */
    public function test_canGetLeastRevisedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData()->create();

        $response = $this->actingAs($user)
            ->get('/special/least-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most revised pages access.
     *
     * @return void
     */
    public function test_canGetMostRevisedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/most-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most revised pages access with a page.
     *
     * @return void
     */
    public function test_canGetMostRevisedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData()->create();

        $response = $this->actingAs($user)
            ->get('/special/most-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most linked pages access.
     *
     * @return void
     */
    public function test_canGetMostLinkedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/linked-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most linked pages access with a linked page.
     * Does not work in test environment; retained for posterity.
     *
     * @return void
     */
    public function canGetMostLinkedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();

        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->create();
            PageVersion::factory()->page($page[$i]->id)->user($editor->id)->testData()->create();
        }
        PageLink::factory()->parent($page[1]->id)->link($page[2]->id)->create();

        $response = $this->actingAs($user)
            ->get('/special/linked-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited pages access.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/recent-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited pages access with a page.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData()->create();

        $response = $this->actingAs($user)
            ->get('/special/recent-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited images access.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedImages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/recent-images');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited images access with an image.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedImagesWithImage()
    {
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
            ->get('/special/recent-images');

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Tests wanted pages access.
     *
     * @return void
     */
    public function test_canGetWantedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/wanted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests wanted pages access with a wanted page.
     *
     * @return void
     */
    public function test_canGetWantedPagesWithLinks()
    {
        $user = User::factory()->make();

        // Make a page and a wanted page link for it
        $page = Page::factory()->create();
        PageLink::factory()->parent($page->id)->wanted()->create();

        $response = $this->actingAs($user)
            ->get('/special/wanted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests create wanted page access.
     *
     * @return void
     */
    public function test_canGetCreateWantedPage()
    {
        $user = User::factory()->editor()->make();

        // Make a page and a wanted page link for it
        $page = Page::factory()->create();
        $link = PageLink::factory()->parent($page->id)->wanted()->create();

        $response = $this->actingAs($user)
            ->get('/special/create-wanted/'.$link->title);

        $response->assertStatus(200);
    }

    /**
     * Tests protected pages access.
     *
     * @return void
     */
    public function test_canGetProtectedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/protected-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests protected pages access with a page.
     *
     * @return void
     */
    public function test_canGetProtectedPagesWithPage()
    {
        $user = User::factory()->make();

        $admin = User::factory()->admin()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($admin->id)->testData()->create();
        PageProtection::factory()->page($page->id)->user($admin->id)->create();

        $response = $this->actingAs($user)
            ->get('/special/protected-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests WIP pages access.
     *
     * @return void
     */
    public function test_canGetWipPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/wip-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests WIP pages access with a page.
     *
     * @return void
     */
    public function test_canGetWipPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, '"wip"')->create();

        $response = $this->actingAs($user)
            ->get('/special/wip-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests stub pages access.
     *
     * @return void
     */
    public function test_canGetStubPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/stub-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests stub pages access with a page.
     *
     * @return void
     */
    public function test_canGetStubPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, '"stub"')->create();

        $response = $this->actingAs($user)
            ->get('/special/stub-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests outdated pages access.
     *
     * @return void
     */
    public function test_canGetOutdatedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/outdated-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests outdated pages access with a page.
     *
     * @return void
     */
    public function test_canGetOutdatedPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, '"outdated"')->create();

        $response = $this->actingAs($user)
            ->get('/special/outdated-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests pages needing cleanup access.
     *
     * @return void
     */
    public function test_canGetCleanupPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests pages needing cleanup access with a page.
     *
     * @return void
     */
    public function test_canGetCleanupPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, '"cleanup"')->create();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests unwatched pages access.
     *
     * @return void
     */
    public function test_canGetUnwatchedPages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests unwatched pages access with a page.
     *
     * @return void
     */
    public function test_canGetUnwatchedPagesWithPage()
    {
        $user = User::factory()->admin()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData()->create();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /******************************************************************************
        LISTS OF PAGES
    *******************************************************************************/

    /**
     * Tests all pages access.
     *
     * @return void
     */
    public function test_canGetAllPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests all pages access with a page.
     *
     * @return void
     */
    public function test_canGetAllPagesWithPage()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->create();

        $response = $this->actingAs($user)
            ->get('/special/all-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests all tags access.
     *
     * @return void
     */
    public function test_canGetAllTags()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-tags');

        $response->assertStatus(200);
    }

    /**
     * Tests all tags access with a tag.
     *
     * @return void
     */
    public function test_canGetAllTagsWithTag()
    {
        $user = User::factory()->make();

        $editor = User::factory()->editor()->create();
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($page->title, null, null, '"'.$this->faker->unique()->domainWord().'"')->create();

        $response = $this->actingAs($user)
            ->get('/special/all-tags');

        $response->assertStatus(200);
    }

    /**
     * Tests all images access.
     *
     * @return void
     */
    public function test_canGetAllImages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-images');

        $response->assertStatus(200);
    }

    /**
     * Tests all images access with an image.
     *
     * @return void
     */
    public function test_canGetAllImagesWithImage()
    {
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
            ->get('/special/all-images');

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /**
     * Tests deleted pages access.
     *
     * @return void
     */
    public function test_canGetDeletedPages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests deleted pages access with a page.
     *
     * @return void
     */
    public function test_canGetDeletedPagesWithPage()
    {
        $user = User::factory()->admin()->create();

        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->deleted()->create();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests deleted images access.
     *
     * @return void
     */
    public function test_canGetDeletedImages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-images');

        $response->assertStatus(200);
    }

    /**
     * Tests deleted images access with an image.
     *
     * @return void
     */
    public function test_canGetDeletedImagesWithImage()
    {
        $user = User::factory()->admin()->create();

        // Create a page for the image to belong to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager())->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-images');

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath . '/' . $version->thumbnailFileName);
        unlink($image->imagePath . '/' . $version->imageFileName);
    }

    /******************************************************************************
        USERS
    *******************************************************************************/

    /**
     * Tests user list access.
     *
     * @return void
     */
    public function test_canGetUserList()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/user-list');

        $response->assertStatus(200);
    }

    /**
     * Tests user list access with a persistent user.
     *
     * @return void
     */
    public function test_canGetUserListWithUser()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/special/user-list');

        $response->assertStatus(200);
    }

    /******************************************************************************
        OTHER
    *******************************************************************************/

    /**
     * Tests random page access.
     *
     * @return void
     */
    public function test_canGetRandomPage()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/random-page');

        $response->assertStatus(302);
    }
}
