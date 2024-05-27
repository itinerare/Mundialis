<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTagTest extends TestCase {
    use RefreshDatabase, withFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test page creation with utility tags.
     */
    public function testPostCreatePageWithUtilityTags() {
        $this->markTestIncomplete();
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Define some basic data
        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
            'utility_tag' => [0 => 'wip'],
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_tags', [
            'page_id' => $page->id,
            'type'    => 'utility',
            'tag'     => 'wip',
        ]);
    }

    /**
     * Test page editing with utility tags.
     */
    public function testCanPostEditPageWithUtilityTags() {
        $this->markTestIncomplete();
        $page = Page::factory()->create();

        // Define some basic data
        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'utility_tag' => [0 => 'wip'],
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_tags', [
            'page_id' => $page->id,
            'type'    => 'utility',
            'tag'     => 'wip',
        ]);
    }

    /**
     * Test page creation with page tags.
     */
    public function testCanPostCreatePageWithPageTags() {
        $this->markTestIncomplete();
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Define some basic data
        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
            'page_tag'    => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_tags', [
            'page_id' => $page->id,
            'type'    => 'page_tag',
            'tag'     => $data['page_tag'],
        ]);
    }

    /**
     * Test page editing with page tags.
     */
    public function testCanPostEditPageWithPageTags() {
        $this->markTestIncomplete();
        $page = Page::factory()->create();

        // Define some basic data
        $data = [
            'title'    => $this->faker->unique()->domainWord(),
            'summary'  => null,
            'page_tag' => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_tags', [
            'page_id' => $page->id,
            'type'    => 'page_tag',
            'tag'     => $data['page_tag'],
        ]);
    }
}
