<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PagePageImage;
use App\Models\User\User;
use App\Services\ImageManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageImageViewTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        $this->service = new ImageManager;
    }

    /**
     * Test image modal access.
     *
     * @dataProvider getImageProvider
     *
     * @param bool $withImage
     * @param bool $asEditor
     * @param bool $isVisible
     * @param int  $status
     */
    public function testGetImageModal($withImage, $asEditor, $isVisible, $status) {
        $page = Page::factory()->create();
        if ($withImage) {
            $imageData = $this->createImage($page);

            $imageData['image']->update([
                'is_visible' => $isVisible,
            ]);
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('pages/get-image/'.$page->id.'/'.($withImage ? $imageData['image']->id : 9999));

        $response->assertStatus($status);

        if ($withImage) {
            $this->service->testImages($imageData['image'], $imageData['version'], false);
        }
    }

    /**
     * Test image page access.
     *
     * @dataProvider getImageProvider
     *
     * @param bool $withImage
     * @param bool $asEditor
     * @param bool $isVisible
     * @param int  $status
     */
    public function testGetImagePage($withImage, $asEditor, $isVisible, $status) {
        $page = Page::factory()->create();
        if ($withImage) {
            $imageData = $this->createImage($page);

            $imageData['image']->update([
                'is_visible' => $isVisible,
            ]);
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('/pages/'.$page->id.'/gallery/'.($withImage ? $imageData['image']->id : 9999));

        $response->assertStatus($status);

        if ($withImage) {
            $this->service->testImages($imageData['image'], $imageData['version'], false);
        }
    }

    public static function getImageProvider() {
        return [
            'with image'                   => [1, 0, 1, 200],
            'with hidden image'            => [1, 0, 0, 404],
            'with hidden image, as editor' => [1, 1, 0, 200],
            'without image'                => [0, 0, 1, 404],
        ];
    }

    /**
     * Creates an image and associated records.
     *
     * @param \App\Models\Page\Page|null $page
     */
    private function createImage($page = null) {
        if (!$page) {
            $imageData['page'] = Page::factory()->create();
        } else {
            $imageData['page'] = $page;
        }

        $editor = User::factory()->editor()->create();

        $imageData['image'] = PageImage::factory()->create();
        $imageData['version'] = PageImageVersion::factory()->image($imageData['image']->id)->user($editor->id)->create();
        PageImageCreator::factory()->image($imageData['image']->id)->user($editor->id)->create();
        PagePageImage::factory()->page($imageData['page']->id)->image($imageData['image']->id)->create();
        $this->service->testImages($imageData['image'], $imageData['version']);

        return $imageData;
    }
}
