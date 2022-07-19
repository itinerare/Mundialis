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

    /**
     * Test image creation access.
     */
    public function testCanGetCreateImage() {
        // Create a temporary editor
        $user = User::factory()->editor()->make();
        // Create a page to upload the image for
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/create');

        $response->assertStatus(200);
    }

    /**
     * Test image editing access.
     */
    public function testCanGetEditImage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery/edit/'.$image->id);

        $response->assertStatus(200);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test basic image editing.
     */
    public function testCanPostEditImage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => $this->faker->unique()->domainWord(),
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'id'          => $image->id,
            'description' => $data['description'],
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image visibility editing.
     */
    public function testCanPostEditImageVisibility() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => null,
            'is_visible'  => 0,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'id'         => $image->id,
            'is_visible' => 0,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image validity editing.
     */
    public function testCanPostEditImageValidity() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => null,
            'is_valid'    => 0,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page->id,
            'page_image_id' => $image->id,
            'is_valid'      => 0,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test active image editing.
     */
    public function testCanPostEditActiveImage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => null,
            'is_valid'    => 1,
            'mark_active' => 1,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id'       => $page->id,
            'image_id' => $image->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image creator editing, replacing the creator.
     */
    public function testCanPostEditImageCreatorWithUser() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Create a persistent user to be the added creator
        $creator = User::factory()->create();

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [
                1 => $creator->id,
            ],
            'creator_url' => [
                0 => null,
            ],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => $creator->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image creator editing, replacing the creator.
     */
    public function testCanPostEditImageCreatorWithUrl() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Create a persistent user to be the added creator
        $creator = User::factory()->create();

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [
                0 => null,
            ],
            'creator_url' => [
                0 => $page->url,
            ],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => null,
            'url'           => $page->url,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image creator editing, adding a creator.
     */
    public function testCanPostEditImageAddCreatorWithUsers() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Create a persistent user to be the added creator
        $creator = User::factory()->create();

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [
                0 => $user->id,
                1 => $creator->id,
            ],
            'creator_url' => [
                0 => null,
                1 => null,
            ],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => $creator->id,
        ]);

        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => $user->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image creator editing, adding a creator.
     */
    public function testCanPostEditImageAddCreatorWithUrls() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->url($page->url)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Create a persistent user to be the added creator
        $creator = User::factory()->create();

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [
                0 => null,
                1 => null,
            ],
            'creator_url' => [
                0 => $page->url,
                1 => $image->imageUrl,
            ],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => null,
            'url'           => $page->url,
        ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => null,
            'url'           => $image->imageUrl,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image creator editing, adding a creator.
     */
    public function testCanPostEditImageAddCreatorMixed() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        $page = Page::factory()->create();

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->url($page->url)->create();
        PagePageImage::factory()->page($page->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Create a persistent user to be the added creator
        $creator = User::factory()->create();

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [
                0 => null,
                1 => $user->id,
            ],
            'creator_url' => [
                0 => $page->url,
                1 => null,
            ],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => null,
            'url'           => $page->url,
        ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_image_creators', [
            'page_image_id' => $image->id,
            'user_id'       => $user->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image page attachment.
     */
    public function testCanPostEditAttachImagePage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->create();
        }

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        PagePageImage::factory()->page($page[1]->id)->image($image->id)->create();
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'page_id'     => [0 => $page[2]->id],
            'description' => null,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[2]->id,
            'page_image_id' => $image->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image page retention through editing via a different page.
     */
    public function testCanPostEditRetainImagePage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->create();
        }

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        for ($i = 1; $i <= 2; $i++) {
            PagePageImage::factory()->page($page[$i]->id)->image($image->id)->create();
        }
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'page_id'     => [0 => $page[1]->id],
            'description' => null,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[2]->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[1]->id,
            'page_image_id' => $image->id,
        ]);

        $this->assertDatabaseHas('page_page_image', [
            'page_id'       => $page[2]->id,
            'page_image_id' => $image->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image page detachment.
     */
    public function testCanPostEditDetachImagePage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->create();
        }

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        for ($i = 1; $i <= 2; $i++) {
            PagePageImage::factory()->page($page[$i]->id)->image($image->id)->create();
        }
        (new ImageManager)->testImages($image, $version);

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[2]->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseMissing('page_page_image', [
            'page_id'       => $page[1]->id,
            'page_image_id' => $image->id,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test image page detachment unsetting the detached page's active image.
     */
    public function testCanPostEditDetachingImagePageUnsetsActiveImage() {
        // Create a persistent editor
        $user = User::factory()->editor()->create();
        // Create a page to attach the image to
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->create();
        }

        // Create the image and associated records
        $image = PageImage::factory()->create();
        $version = PageImageVersion::factory()->image($image->id)->user($user->id)->create();
        PageImageCreator::factory()->image($image->id)->user($user->id)->create();
        for ($i = 1; $i <= 2; $i++) {
            PagePageImage::factory()->page($page[$i]->id)->image($image->id)->create();
        }
        (new ImageManager)->testImages($image, $version);

        // Set the page-to-be-detached's active image
        $page[1]->update(['image_id' => $image->id]);

        // Define some basic data
        $data = [
            'description' => null,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[2]->id.'/gallery/edit/'.$image->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id'       => $page[1]->id,
            'image_id' => null,
        ]);

        // Delete the test images, to clean up
        unlink($image->imagePath.'/'.$version->thumbnailFileName);
        unlink($image->imagePath.'/'.$version->imageFileName);
    }

    /**
     * Test page image uploading.
     * This does not work due to Intervention not cooperating in a test environment,
     * but remains here for posterity.
     */
    public function canPostCreateImage() {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a page for the image to be attached to
        $page = Page::factory()->create();

        // Create a fake image and a fake thumbnail
        $image = UploadedFile::fake()->image('test_image.png');
        $thumbnail = UploadedFile::fake()->image('test_thumb.png');

        // Define some basic data
        $data = [
            'image'       => $image,
            'thumbnail'   => $thumbnail,
            'x0'          => 0, 'x1' => 0,
            'y0'          => 0, 'y1' => 0,
            'creator_id'  => [0 => $user->id],
            'creator_url' => [0 => null],
            'description' => $this->faker->unique()->domainWord(),
            'is_valid'    => 1,
            'is_visible'  => 1,
            'mark_active' => 0,
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/gallery/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_images', [
            'description' => $data['description'],
            'is_visible'  => $data['is_visible'],
        ]);
    }
}
