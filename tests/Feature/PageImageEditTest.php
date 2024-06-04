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
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PageImageEditTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        $this->service = new ImageManager;
    }

    /**
     * Test image creation access.
     *
     * @dataProvider getCreateImageProvider
     *
     * @param bool $withPage
     * @param int  $status
     */
    public function testGetCreateImage($withPage, $status) {
        if ($withPage) {
            $page = Page::factory()->create();
        }

        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/gallery/create');

        $response->assertStatus($status);
    }

    public static function getCreateImageProvider() {
        return [
            'with page'    => [1, 200],
            'without page' => [0, 404],
        ];
    }

    /**
     * Test image editing access.
     *
     * @dataProvider getEditImageProvider
     *
     * @param bool $withImage
     * @param int  $status
     */
    public function testCanGetEditImage($withImage, $status) {
        $page = Page::factory()->create();
        if ($withImage) {
            $imageData = $this->createImage($page);
        }

        $response = $this->actingAs($this->editor)
            ->get('/pages/'.$page->id.'/gallery/edit/'.($withImage ? $imageData['image']->id : 9999));

        $response->assertStatus($status);

        if ($withImage) {
            $this->service->testImages($imageData['image'], $imageData['version'], false);
        }
    }

    public static function getEditImageProvider() {
        return [
            'with image'    => [1, 200],
            'without image' => [0, 404],
        ];
    }

    /**
     * Test page image uploading.
     *
     * @dataProvider postCreateImageProvider
     *
     * @param array $fileData
     * @param bool  $withDescription
     * @param array $creatorData
     * @param bool  $isVisible
     * @param bool  $isValid
     * @param bool  $isActive
     * @param bool  $expected
     */
    public function testPostCreateImage($fileData, $withDescription, $creatorData, $isVisible, $isValid, $isActive, $expected) {
        $page = Page::factory()->create();

        if ($fileData[0]) {
            $file = UploadedFile::fake()->image('test_image.png')
                ->size($fileData[2] ? 10000 : 22000);
        } else {
            $file = UploadedFile::fake()
                ->create('invalid.pdf', $fileData[2] ? 10000 : 22000);
        }
        if ($fileData[1]) {
            $thumbnail = UploadedFile::fake()->image('test_thumb.png');
        }

        $data = [
            'image'       => $file,
            'thumbnail'   => $fileData[1] ? $thumbnail : null,
            'use_cropper' => $fileData[1] ? null : 1,
            'x0'          => 0, 'x1' => $fileData[1] ? 0 : 1,
            'y0'          => 0, 'y1' => $fileData[1] ? 0 : 1,
            'creator_id'  => [
                0 => $creatorData[0] ? $this->editor->id : null,
            ] + ($creatorData[2] ? [
                1 => null,
            ] : []),
            'creator_url' => [
                0 => $creatorData[1] && !$creatorData[2] ? $this->faker->url() : null,
            ] + ($creatorData[2] ? [
                1 => $creatorData[1] ? $this->faker->url() : null,
            ] : []),
            'description' => $withDescription ? $this->faker->unique()->domainWord() : null,
            'is_valid'    => $isValid,
            'is_visible'  => $isVisible,
            'mark_active' => $isActive,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page->id.'/gallery/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();

            $this->assertDatabaseHas('page_images', [
                'description' => $data['description'],
                'is_visible'  => $data['is_visible'],
            ]);

            $image = PageImage::where('description', $data['description'])->where('is_visible', $data['is_visible'])->first();

            $this->assertDatabaseHas('pages', [
                'id'       => $page->id,
                'image_id' => $isActive ? $image->id : null,
            ]);

            $this->assertDatabaseHas('page_page_image', [
                'page_id'       => $page->id,
                'page_image_id' => $image->id,
                'is_valid'      => $data['is_valid'],
            ]);

            if ($creatorData[2]) {
                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $image->id,
                    'user_id'       => $data['creator_id'][0],
                    'url'           => null,
                ]);

                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $image->id,
                    'user_id'       => null,
                    'url'           => $data['creator_url'][1],
                ]);
            } else {
                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $image->id,
                    'user_id'       => $creatorData[0] ? $data['creator_id'][0] : null,
                    'url'           => $creatorData[1] && !$creatorData[0] ? $data['creator_url'][0] : null,
                ]);
            }

            $this->assertFileExists($image->imagePath.'/'.$image->version->imageFileName);
            $this->assertFileExists($image->imagePath.'/'.$image->version->thumbnailFileName);

            $this->service->testImages($image, $image->version, false);
        } else {
            $response->assertSessionHasErrors();

            $this->assertDatabaseMissing('page_images', [
                'description' => $data['description'],
                'is_visible'  => $data['is_visible'],
            ]);
        }
    }

    public static function postCreateImageProvider() {
        return [
            // $fileData = [$isImage, $withThumbnail, $isValid]
            // $creatorData = [$withUser, $withUrl, $asMultiple]

            'valid image'                        => [[1, 0, 1], 1, [1, 0, 0], 1, 1, 0, 1],
            'valid image without description'    => [[1, 0, 1], 0, [1, 0, 0], 1, 1, 0, 1],
            'valid image with thumbnail'         => [[1, 1, 1], 1, [1, 0, 0], 1, 1, 0, 1],
            'valid image with url creator'       => [[1, 0, 1], 1, [0, 1, 0], 1, 1, 0, 1],
            'valid image with both creators'     => [[1, 0, 1], 1, [1, 1, 0], 1, 1, 0, 1],
            'valid image with multiple creators' => [[1, 0, 1], 1, [1, 1, 1], 1, 1, 0, 1],
            'valid image without creator'        => [[1, 0, 1], 1, [0, 0, 0], 1, 1, 0, 0],
            'valid image, hidden'                => [[1, 0, 1], 1, [1, 0, 0], 0, 1, 0, 1],
            'valid image, invalid'               => [[1, 0, 1], 1, [1, 0, 0], 1, 0, 0, 1],
            'valid image, active'                => [[1, 0, 1], 1, [1, 0, 0], 1, 1, 1, 1],
            'invalid image'                      => [[1, 0, 0], 1, [1, 0, 0], 1, 1, 0, 0],
            'valid file'                         => [[0, 0, 1], 1, [1, 0, 0], 1, 1, 0, 0],
            'invalid file'                       => [[0, 0, 0], 1, [1, 0, 0], 1, 1, 0, 0],
        ];
    }

    /**
     * Test page image editing.
     *
     * @dataProvider postEditImageProvider
     *
     * @param bool       $withImage
     * @param array|null $fileData
     * @param bool       $withDescription
     * @param array      $creatorData
     * @param bool       $isVisible
     * @param bool       $isValid
     * @param bool       $isActive
     * @param bool       $expected
     */
    public function testPostEditImage($withImage, $fileData, $withDescription, $creatorData, $isVisible, $isValid, $isActive, $expected) {
        $page = Page::factory()->create();
        if ($withImage) {
            $imageData = $this->createImage($page);
        }

        if ($fileData) {
            if ($fileData[0]) {
                $file = UploadedFile::fake()->image('test_image.png')
                    ->size($fileData[2] ? 10000 : 22000);
            } else {
                $file = UploadedFile::fake()
                    ->create('invalid.pdf', $fileData[2] ? 10000 : 22000);
            }
            if ($fileData[1]) {
                $thumbnail = UploadedFile::fake()->image('test_thumb.png');
            }
        }

        $data = [
            'description' => $withDescription ? $this->faker->unique()->domainWord() : null,
            'creator_id'  => [
                0 => $creatorData[0] ? $this->editor->id : null,
            ] + ($creatorData[2] ? [
                1 => null,
            ] : []),
            'creator_url' => [
                0 => $creatorData[1] && !$creatorData[2] ? $this->faker->url() : null,
            ] + ($creatorData[2] ? [
                1 => $creatorData[1] ? $this->faker->url() : null,
            ] : []),
            'is_visible'  => $isVisible,
            'is_valid'    => $isValid,
            'mark_active' => $isActive,
        ] + ($fileData ? [
            'image'       => $file,
            'thumbnail'   => $fileData[1] ? $thumbnail : null,
            'use_cropper' => $fileData[1] ? null : 1,
            'x0'          => 0, 'x1' => $fileData[1] ? 0 : 1,
            'y0'          => 0, 'y1' => $fileData[1] ? 0 : 1,
        ] : []);

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page->id.'/gallery/edit/'.($withImage ? $imageData['image']->id : 9999), $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('page_images', [
                'id'          => $imageData['image']->id,
                'description' => $data['description'],
                'is_visible'  => $data['is_visible'],
            ]);

            $this->assertDatabaseHas('pages', [
                'id'       => $page->id,
                'image_id' => $isActive ? $imageData['image']->id : null,
            ]);

            $this->assertDatabaseHas('page_page_image', [
                'page_id'       => $page->id,
                'page_image_id' => $imageData['image']->id,
                'is_valid'      => $data['is_valid'],
            ]);

            if ($creatorData[2]) {
                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $imageData['image']->id,
                    'user_id'       => $data['creator_id'][0],
                    'url'           => null,
                ]);

                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $imageData['image']->id,
                    'user_id'       => null,
                    'url'           => $data['creator_url'][1],
                ]);
            } else {
                $this->assertDatabaseHas('page_image_creators', [
                    'page_image_id' => $imageData['image']->id,
                    'user_id'       => $creatorData[0] ? $data['creator_id'][0] : null,
                    'url'           => $creatorData[1] && !$creatorData[0] ? $data['creator_url'][0] : null,
                ]);
            }

            if ($fileData) {
                $this->assertFileExists($imageData['image']->imagePath.'/'.$imageData['image']->version->imageFileName);
                $this->assertFileExists($imageData['image']->imagePath.'/'.$imageData['image']->version->thumbnailFileName);

                // Due to the way objects are handled in a test environment,
                // it's simplest to just locate the new version directly
                $version = PageImageVersion::where('id', '!=', $imageData['version']->id)
                    ->where('page_image_id', $imageData['image']->id)->first();
                $this->service->testImages($imageData['image'], $version, false);
            }
        } else {
            $response->assertSessionHasErrors();
        }

        if ($withImage) {
            $this->service->testImages($imageData['image'], $imageData['version'], false);
        }
    }

    public static function postEditImageProvider() {
        return [
            // $fileData = [$isImage, $withThumbnail, $isValid]
            // $creatorData = [$withUser, $withUrl, $asMultiple]

            'with valid image'                        => [1, [1, 0, 1], 0, [1, 0, 0], 1, 1, 0, 1],
            'with valid image with description'       => [1, [1, 0, 1], 1, [1, 0, 0], 1, 1, 0, 1],
            'with valid image with thumbnail'         => [1, [1, 1, 1], 0, [1, 0, 0], 1, 1, 0, 1],
            'with valid image with url creator'       => [1, [1, 0, 1], 0, [0, 1, 0], 1, 1, 0, 1],
            'with valid image with both creators'     => [1, [1, 0, 1], 0, [1, 1, 0], 1, 1, 0, 1],
            'with valid image with multiple creators' => [1, [1, 0, 1], 0, [1, 1, 1], 1, 1, 0, 1],
            'with valid image without creator'        => [1, [1, 0, 1], 0, [0, 0, 0], 1, 1, 0, 0],
            'with valid image, hidden'                => [1, [1, 0, 1], 0, [1, 0, 0], 0, 1, 0, 1],
            'with valid image, invalid'               => [1, [1, 0, 1], 0, [1, 0, 0], 1, 0, 0, 1],
            'with valid image, active'                => [1, [1, 0, 1], 0, [1, 0, 0], 1, 1, 1, 1],
            'with invalid image'                      => [1, [1, 0, 0], 0, [1, 0, 0], 1, 1, 0, 0],
            'with valid file'                         => [1, [0, 0, 1], 0, [1, 0, 0], 1, 1, 0, 0],
            'invalid file'                            => [1, [0, 0, 0], 0, [1, 0, 0], 1, 1, 0, 0],
            'without image'                           => [0, [1, 0, 1], 0, [1, 0, 0], 1, 1, 0, 0],
        ];
    }

    /**
     * Test marking old images attached to a page as invalid.
     */
    public function testMarkOldImagesInvalid() {
        $imageData = $this->createImage();

        $data = [
            'image'        => UploadedFile::fake()->image('test_image.png'),
            'use_cropper'  => 1,
            'x0'           => 0, 'x1' => 1,
            'y0'           => 0, 'y1' => 1,
            'description'  => $this->faker->unique()->domainWord(),
            'is_valid'     => 1,
            'is_visible'   => 1,
            'mark_invalid' => 1,
            'creator_id'   => [0 => $this->editor->id],
            'creator_url'  => [0 => null],
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$imageData['page']->id.'/gallery/create', $data);

        $response->assertSessionHasNoErrors();

        $image = PageImage::where('description', $data['description'])->where('is_visible', $data['is_visible'])->first();

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $imageData['page']->id,
            'page_image_id' => $image->id,
            'is_valid'      => 1,
        ]);

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $imageData['page']->id,
            'page_image_id' => $imageData['image']->id,
            'is_valid'      => 0,
        ]);

        $this->service->testImages($imageData['image'], $imageData['version'], false);
        $this->service->testImages($image, $image->version, false);
    }

    /**
     * Test image page attachment.
     */
    public function testAttachImagePage() {
        for ($i = 0; $i <= 1; $i++) {
            $page[$i] = Page::factory()->create();
        }
        $imageData = $this->createImage($page[0]);

        $data = [
            'page_id'     => [0 => $page[1]->id],
            'description' => null,
            'creator_id'  => [0 => $this->editor->id],
            'creator_url' => [0 => null],
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page[0]->id.'/gallery/edit/'.$imageData['image']->id, $data);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[0]->id,
            'page_image_id' => $imageData['image']->id,
        ]);

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[1]->id,
            'page_image_id' => $imageData['image']->id,
        ]);

        $this->service->testImages($imageData['image'], $imageData['version'], false);
    }

    /**
     * Test image page retention through editing via a different page.
     */
    public function testRetainImagePageAttachment() {
        for ($i = 0; $i <= 1; $i++) {
            $page[$i] = Page::factory()->create();
        }

        $imageData = $this->createImage($page[0]);
        PagePageImage::factory()->page($page[1]->id)->image($imageData['image']->id)->create();

        $data = [
            'page_id'     => [0 => $page[0]->id],
            'description' => null,
            'creator_id'  => [0 => $this->editor->id],
            'creator_url' => [0 => null],
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page[1]->id.'/gallery/edit/'.$imageData['image']->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[0]->id,
            'page_image_id' => $imageData['image']->id,
        ]);

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[1]->id,
            'page_image_id' => $imageData['image']->id,
        ]);

        $this->service->testImages($imageData['image'], $imageData['version'], false);
    }

    /**
     * Test image page detachment.
     *
     * @dataProvider detachImagePageProvider
     *
     * @param bool $isActive
     */
    public function testDetachImagePage($isActive) {
        for ($i = 0; $i <= 1; $i++) {
            $page[$i] = Page::factory()->create();
        }
        $imageData = $this->createImage($page[0]);
        PagePageImage::factory()->page($page[1]->id)->image($imageData['image']->id)->create();

        $data = [
            'description' => null,
            'creator_id'  => [0 => $this->editor->id],
            'creator_url' => [0 => null],
        ];

        if ($isActive) {
            $page[0]->update(['image_id' => $imageData['image']->id]);
        }

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page[1]->id.'/gallery/edit/'.$imageData['image']->id, $data);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('page_page_image', [
            'page_id'       => $page[0]->id,
            'page_image_id' => $imageData['image']->id,
        ]);

        if ($isActive) {
            $this->assertDatabaseHas('pages', [
                'id'       => $page[0]->id,
                'image_id' => null,
            ]);
        }

        $this->service->testImages($imageData['image'], $imageData['version'], false);
    }

    public static function detachImagePageProvider() {
        return [
            'inactive' => [0],
            'active'   => [1],
        ];
    }

    /**
     * Creates an image and associated records.
     *
     * @param Page|null $page
     */
    private function createImage($page = null) {
        if (!$page) {
            $imageData['page'] = Page::factory()->create();
        } else {
            $imageData['page'] = $page;
        }

        $imageData['image'] = PageImage::factory()->create();
        $imageData['version'] = PageImageVersion::factory()->image($imageData['image']->id)->user($this->editor->id)->create();
        PageImageCreator::factory()->image($imageData['image']->id)->user($this->editor->id)->create();
        PagePageImage::factory()->page($imageData['page']->id)->image($imageData['image']->id)->create();
        $this->service->testImages($imageData['image'], $imageData['version']);

        return $imageData;
    }
}
