<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageProtection;
use App\Models\Page\PageVersion;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Services\ImageManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageImageDeleteTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
        $this->admin = User::factory()->admin()->create();

        $this->page = Page::factory()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();

        $this->image = PageImage::factory()->create();
        $this->version = PageImageVersion::factory()->image($this->image->id)->user($this->editor->id)->create();
        PageImageCreator::factory()->image($this->image->id)->user($this->editor->id)->create();
        PagePageImage::factory()->page($this->page->id)->image($this->image->id)->create();

        $this->service = new ImageManager;
        $this->service->testImages($this->image, $this->version);
    }

    protected function tearDown(): void {
        parent::tearDown();

        $this->service->testImages($this->image, $this->version, false);
    }

    /**
     * Test image deletion access.
     *
     * @dataProvider getDeleteImageProvider
     *
     * @param bool $isValid
     */
    public function testGetDeleteImage($isValid) {
        $response = $this->actingAs($this->editor)
            ->get('/pages/'.$this->page->id.'/gallery/delete/'.($isValid ? $this->image->id : 9999));

        $response->assertStatus(200);

        if ($isValid) {
            $response->assertSeeText('You are about to delete image #'.$this->image->id);
        } else {
            $response->assertSeeText('Invalid image selected.');
        }
    }

    public static function getDeleteImageProvider() {
        return [
            'valid'   => [1],
            'invalid' => [0],
        ];
    }

    /**
     * Test (soft) image deletion.
     *
     * @dataProvider postDeleteImageProvider
     *
     * @param int  $rank
     * @param bool $withProtection
     * @param bool $isActive
     * @param bool $withReason
     * @param bool $expected
     */
    public function testPostSoftDeleteImage($rank, $withProtection, $isActive, $withReason, $expected) {
        $user = User::factory()->create([
            'rank_id' => Rank::where('sort', $rank)->first()->id,
        ]);

        if ($isActive) {
            $this->page->update(['image_id' => $this->image->id]);
        }

        if ($withProtection) {
            PageProtection::factory()->page($this->page->id)->user($this->admin->id)->create();
        }

        $data = [
            'reason' => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$this->page->id.'/gallery/delete/'.$this->image->id, $data);

        if ($expected) {
            $this->assertSoftDeleted($this->image);
            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('page_image_versions', [
                'page_image_id' => $this->image->id,
                'type'          => 'Image Deleted',
                'reason'        => $data['reason'],
            ]);

            if ($isActive) {
                $this->assertDatabaseHas('pages', [
                    'id'       => $this->page->id,
                    'image_id' => null,
                ]);
            }
        } else {
            $this->assertNotSoftDeleted($this->image);
            $response->assertSessionHasErrors();
        }
    }

    public static function postDeleteImageProvider() {
        return [
            'editor basic'                 => [1, 0, 0, 0, 1],
            'editor with reason'           => [1, 0, 0, 1, 1],
            'editor protected'             => [1, 1, 0, 0, 0],
            'editor protected with reason' => [1, 1, 0, 1, 0],
            'editor, active'               => [1, 0, 1, 0, 1],
            'editor with reason, active'   => [1, 0, 1, 1, 1],
            'editor with everything'       => [1, 1, 1, 1, 0],
            'admin basic'                  => [2, 0, 0, 0, 1],
            'admin with reason'            => [2, 0, 0, 1, 1],
            'admin protected'              => [2, 1, 0, 0, 1],
            'admin protected with reason'  => [2, 1, 0, 1, 1],
            'admin, active'                => [2, 0, 1, 0, 1],
            'admin with reason, active'    => [2, 0, 1, 1, 1],
            'admin with everything'        => [2, 1, 1, 1, 1],
        ];
    }

    /**
     * Tests deleted images access.
     *
     * @dataProvider getDeletedImagesProvider
     *
     * @param bool $withImage
     * @param bool $isDeleted
     */
    public function testGetDeletedImages($withImage, $isDeleted) {
        if ($withImage) {
            $image = PageImage::factory();
            $version = PageImageVersion::factory()->user($this->editor->id);
            if ($isDeleted) {
                $image = $image->deleted();
                $version = $version->deleted();
            }
            $image = $image->create();
            $version = $version->image($image->id)->create();
            PagePageImage::factory()->page($this->page->id)->image($image->id)->create();

            $this->service->testImages($image, $version);
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-images')
            ->assertStatus(200);

        if ($withImage) {
            if ($isDeleted) {
                $response->assertSee($image->thumbnailUrl);
            } else {
                $response->assertDontSee($image->thumbnailUrl);
            }

            $this->service->testImages($image, $version, false);
        } else {
            $response->assertViewHas('images', function ($images) {
                return $images->count() == 0;
            });
        }
    }

    public static function getDeletedImagesProvider() {
        return [
            'without image'        => [0, 0],
            'with deleted image'   => [1, 1],
            'with undeleted image' => [1, 0],
        ];
    }

    /**
     * Test deleted image access.
     *
     * @dataProvider getDeleteImageProvider
     *
     * @param bool $isValid
     */
    public function testGetDeletedImage($isValid) {
        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
        PagePageImage::factory()->page($this->page->id)->image($image->id)->create();
        $this->service->testImages($image, $version);

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-images/'.($isValid ? $image->id : $this->image->id));

        $response->assertStatus($isValid ? 200 : 404);

        $this->service->testImages($image, $version, false);
    }

    /**
     * Test restore image access.
     *
     * @dataProvider getRestoreImageProvider
     *
     * @param bool $withPage
     * @param bool $isDeleted
     */
    public function testGetRestoreImage($withPage, $isDeleted) {
        if (!$withPage) {
            $page = Page::factory()->deleted()->create();
            PageVersion::factory()->page($page->id)->user($this->editor->id)->deleted()->create();
        }

        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
        PagePageImage::factory()->page($withPage ? $this->page->id : $page->id)->image($image->id)->create();
        $this->service->testImages($image, $version);

        $response = $this->actingAs($this->admin)
            ->get('/admin/special/deleted-images/'.($isDeleted ? $image->id : $this->image->id).'/restore');

        $response->assertStatus(200);

        if ($isDeleted && $withPage) {
            $response->assertSeeText('You are about to restore image #'.$image->id);
        } else {
            $response->assertSeeText('Invalid image selected.');
        }

        $this->service->testImages($image, $version, false);
    }

    public static function getRestoreImageProvider() {
        return [
            'with page, deleted'      => [1, 1],
            'without page, deleted'   => [0, 1],
            'with page, undeleted'    => [1, 0],
            'without page, undeleted' => [0, 0],
        ];
    }

    /**
     * Test image restoration.
     *
     * @dataProvider postRestorePageProvider
     *
     * @param bool $isDeleted
     * @param bool $withReason
     * @param bool $withPage
     * @param bool $expected
     */
    public function testPostRestoreImage($isDeleted, $withReason, $withPage, $expected) {
        if (!$withPage) {
            $page = Page::factory()->deleted()->create();
            PageVersion::factory()->page($page->id)->user($this->editor->id)->deleted()->create();
        }

        $image = PageImage::factory()->deleted()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->deleted()->create();
        PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
        PagePageImage::factory()->page($withPage ? $this->page->id : $page->id)->image($image->id)->create();
        $this->service->testImages($image, $version);

        $data = [
            'reason' => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/special/deleted-images/'.($isDeleted ? $image->id : $this->image->id).'/restore', $data);

        if ($expected) {
            $this->assertNotSoftDeleted($image);
            $response->assertSessionHasNoErrors();

            if ($withReason) {
                $this->assertDatabaseHas('page_image_versions', [
                    'page_image_id' => $image->id,
                    'type'          => 'Image Restored',
                    'reason'        => $data['reason'],
                ]);
            }
        } else {
            $this->assertSoftDeleted($image);
            $response->assertSessionHasErrors();
        }

        $this->service->testImages($image, $version, false);
    }

    public static function postRestorePageProvider() {
        return [
            'basic'                     => [1, 0, 1, 1],
            'with reason'               => [1, 1, 1, 1],
            'without page'              => [1, 0, 0, 0],
            'with reason, without page' => [1, 1, 0, 0],
            'undeleted image'           => [0, 0, 0, 0],
        ];
    }
}
