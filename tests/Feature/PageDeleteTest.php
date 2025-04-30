<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageProtection;
use App\Models\Page\PageRelationship;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Services\ImageManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageDeleteTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->page = Page::factory()->create();
        $this->editor = User::factory()->editor()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();

        $this->admin = User::factory()->admin()->create();
    }

    /**
     * Test page deletion access.
     *
     * @param bool $isValid
     */
    #[DataProvider('getDeletePageProvider')]
    public function testGetDeletePage($isValid) {
        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($isValid ? $this->page->id : 9999).'/delete');

        $response->assertStatus(200);

        if ($isValid) {
            $response->assertSee('You are about to delete the page');
        } else {
            $response->assertSee('Invalid page selected.');
        }
    }

    public static function getDeletePageProvider() {
        return [
            'valid'   => [1],
            'invalid' => [0],
        ];
    }

    /**
     * Test (soft) page deletion.
     *
     * @param int  $rank
     * @param bool $withProtection
     * @param bool $withChild
     * @param bool $withReason
     * @param bool $expected
     */
    #[DataProvider('postDeletePageProvider')]
    public function testPostSoftDeletePage($rank, $withProtection, $withChild, $withReason, $expected) {
        $user = User::factory()->create([
            'rank_id' => Rank::where('sort', $rank)->first()->id,
        ]);

        if ($withProtection) {
            PageProtection::factory()->page($this->page->id)->user($this->admin->id)->create();
        }

        if ($withChild) {
            Page::factory()->create(['parent_id' => $this->page->id]);
        }

        $data = [
            'reason' => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$this->page->id.'/delete', $data);

        if ($expected) {
            $this->assertSoftDeleted($this->page);
            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('page_versions', [
                'page_id' => $this->page->id,
                'type'    => 'Page Deleted',
                'reason'  => $data['reason'],
            ]);
        } else {
            $this->assertNotSoftDeleted($this->page);
            $response->assertSessionHasErrors();
        }
    }

    public static function postDeletePageProvider() {
        return [
            'editor basic'                 => [1, 0, 0, 0, 1],
            'editor with reason'           => [1, 0, 0, 1, 1],
            'editor protected'             => [1, 1, 0, 0, 0],
            'editor protected with reason' => [1, 1, 0, 1, 0],
            'editor with child'            => [1, 0, 1, 0, 0],
            'editor with reason, child'    => [1, 0, 1, 1, 0],
            'editor with everything'       => [1, 1, 1, 1, 0],
            'admin basic'                  => [2, 0, 0, 0, 1],
            'admin with reason'            => [2, 0, 0, 1, 1],
            'admin protected'              => [2, 1, 0, 0, 1],
            'admin protected with reason'  => [2, 1, 0, 1, 1],
            'admin with child'             => [2, 0, 1, 0, 0],
            'admin with reason, child'     => [2, 0, 1, 1, 0],
            'admin with everything'        => [2, 1, 1, 1, 0],
        ];
    }

    /**
     * Test (soft) page deletion with associated images.
     *
     * @param int $pages
     */
    #[DataProvider('postDeletePageWithImagesProvider')]
    public function testPostSoftDeletePageWithImages($pages) {
        for ($i = 1; $i <= $pages; $i++) {
            // Make a page
            $page[$i] = Page::factory()->create();
            // As well as accompanying version
            PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

            // Create an image and associated records, as well as test files
            $image[$i] = PageImage::factory()->create();
            $imageVersion[$i] = PageImageVersion::factory()->image($image[$i]->id)->user($this->editor->id)->create();
            PageImageCreator::factory()->image($image[$i]->id)->user($this->editor->id)->create();
            PagePageImage::factory()->page($page[1]->id)->image($image[$i]->id)->create();
            (new ImageManager)->testImages($image[$i], $imageVersion[$i]);
        }

        if ($pages > 1) {
            // Attach one of the images to page 2 as well
            PagePageImage::factory()->page($page[2]->id)->image($image[2]->id)->create();
        }

        // Try to post data
        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page[1]->id.'/delete');

        $this->assertSoftDeleted($image[1]);
        $this->assertSoftDeleted($page[1]);
        $response->assertSessionHasNoErrors();

        if ($pages > 1) {
            $this->assertNotSoftDeleted($image[2]);
        }

        for ($i = 1; $i <= $pages; $i++) {
            (new ImageManager)->testImages($image[$i], $imageVersion[$i], false);
        }
    }

    public static function postDeletePageWithImagesProvider() {
        return [
            'one page'  => [1],
            'two pages' => [2],
        ];
    }

    /**
     * Test (soft) page deletion with a relationship.
     */
    public function testPostSoftDeletePageWithRelationship() {
        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
            PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page[1]->id.'/delete');

        $this->assertModelExists($relationship);
        $this->assertSoftDeleted($page[1]);
        $response->assertSessionHasNoErrors();
    }

    /**
     * Tests deleted pages access.
     *
     * @param bool $withPage
     * @param bool $isDeleted
     */
    #[DataProvider('getDeletedPagesProvider')]
    public function testGetDeletedPages($withPage, $isDeleted) {
        if ($withPage) {
            $page = Page::factory();
            $version = PageVersion::factory()->user($this->editor->id);
            if ($isDeleted) {
                $page = $page->deleted();
                $version = $version->deleted();
            }
            $page = $page->create();
            $version = $version->page($page->id)->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-pages')
            ->assertStatus(200);

        if ($withPage) {
            if ($isDeleted) {
                $response->assertSeeText($page->title);
            } else {
                $response->assertDontSeeText($page->title);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getDeletedPagesProvider() {
        return [
            'without page'        => [0, 0],
            'with deleted page'   => [1, 1],
            'with undeleted page' => [1, 0],
        ];
    }

    /**
     * Test deleted page access.
     *
     * @param bool $isValid
     */
    #[DataProvider('getDeletePageProvider')]
    public function testGetDeletedPage($isValid) {
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($this->editor->id)->deleted()->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-pages/'.($isValid ? $page->id : $this->page->id));

        $response->assertStatus($isValid ? 200 : 404);
    }

    /**
     * Test restore page access.
     *
     * @param bool $isValid
     */
    #[DataProvider('getDeletePageProvider')]
    public function testGetRestorePage($isValid) {
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($this->editor->id)->deleted()->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-pages/'.($isValid ? $page->id : $this->page->id).'/restore');

        $response->assertStatus(200);

        if ($isValid) {
            $response->assertSee('You are about to restore the page');
        } else {
            $response->assertSee('Invalid page selected.');
        }
    }

    /**
     * Test page restoration.
     *
     * @param bool $isDeleted
     * @param bool $withReason
     * @param bool $withImage
     * @param bool $expected
     */
    #[DataProvider('postRestorePageProvider')]
    public function testPostRestorePage($isDeleted, $withReason, $withImage, $expected) {
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($this->editor->id)->deleted()->create();

        if ($withImage) {
            $image = PageImage::factory()->deleted()->create();
            $imageVersion = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->deleted()->create();
            PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
            PagePageImage::factory()->page($page->id)->image($image->id)->create();
            (new ImageManager)->testImages($image, $imageVersion);
        }

        $data = [
            'reason' => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/special/deleted-pages/'.($isDeleted ? $page->id : $this->page->id).'/restore', $data);

        if ($expected) {
            $this->assertNotSoftDeleted($page);
            $response->assertSessionHasNoErrors();

            if ($withReason) {
                $this->assertDatabaseHas('page_versions', [
                    'page_id' => $page->id,
                    'type'    => 'Page Restored',
                    'reason'  => $data['reason'],
                ]);
            }

            if ($withImage) {
                $this->assertNotSoftDeleted($image);
                (new ImageManager)->testImages($image, $imageVersion, false);
            }
        } else {
            $this->assertSoftDeleted($page);
            $response->assertSessionHasErrors();
        }
    }

    public static function postRestorePageProvider() {
        return [
            'basic'              => [1, 0, 0, 1],
            'with reason'        => [1, 1, 0, 1],
            'with image'         => [1, 0, 1, 1],
            'with reason, image' => [1, 1, 1, 1],
            'undeleted page'     => [0, 0, 0, 0],
        ];
    }

    /**
     * Test full/force page deletion.
     *
     * @param bool $withProtection
     * @param bool $withImage
     * @param bool $withRelationship
     */
    #[DataProvider('postForceDeletePageProvider')]
    public function testPostForceDeletePage($withProtection, $withImage, $withRelationship) {
        // Make a category for the page to go into/to delete
        $category = SubjectCategory::factory()->create();

        for ($i = 1; $i <= ($withRelationship ? 2 : 1); $i++) {
            // Make a deleted page
            $page[$i] = Page::factory()->category($category->id)->deleted()->create();
            // As well as accompanying version
            $version[$i] = PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->deleted()->create();
        }

        if ($withProtection) {
            $protection = PageProtection::factory()->page($page[1]->id)->user($this->admin->id)->create();
        }

        if ($withImage) {
            $image = PageImage::factory()->deleted()->create();
            $imageVersion = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->deleted()->create();
            PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
            PagePageImage::factory()->page($page[1]->id)->image($image->id)->create();
            (new ImageManager)->testImages($image, $imageVersion);
        }

        if ($withRelationship) {
            $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();
        }

        // Delete the category, since that is the only way to force-delete pages
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/delete/'.$category->id);

        $this->assertModelMissing($page[1]);
        $this->assertModelMissing($version[1]);
        $response->assertSessionHasNoErrors();

        if ($withProtection) {
            $this->assertModelMissing($protection);
        }
        if ($withImage) {
            $this->assertModelMissing($image);
        }
        if ($withRelationship) {
            $this->assertModelMissing($relationship);
        }
    }

    public static function postForceDeletePageProvider() {
        return [
            'basic'                         => [0, 0, 0],
            'with protection'               => [1, 0, 0],
            'with protection, image'        => [1, 1, 0],
            'with protection, relationship' => [1, 0, 1],
            'with image'                    => [0, 1, 0],
            'with image, relationship'      => [0, 1, 1],
            'with relationship'             => [0, 0, 1],
            'with everything'               => [1, 1, 1],
        ];
    }
}
