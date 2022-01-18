<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageEditTest extends TestCase
{
    use RefreshDatabase;
    use withFaker;

    /**
     * Test page creation access.
     *
     * @return void
     */
    public function test_canGetCreatePage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/create/'.$category->id);

        $response->assertStatus(200);
    }

    /**
     * Test page editing access.
     *
     * @return void
     */
    public function test_canGetEditPage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();
        // Create a page to edit
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/edit');

        $response->assertStatus(200);
    }

    /**
     * Test page creation with minimal data.
     * Specifically, this tests editing of the basic page model,
     * rather than information stored on the page version.
     *
     * @return void
     */
    public function test_canPostCreateEmptyPage()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Define some basic data
        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'category_id' => $category->id,
            'title'       => $data['title'],
        ]);
    }

    /**
     * Test page editing with minimal data.
     * Specifically, this tests editing of the basic page model,
     * rather than information stored on the page version.
     *
     * @return void
     */
    public function test_canPostEditEmptyPage()
    {
        $page = Page::factory()->create();

        // Define some basic data
        $data = [
            'title'   => $this->faker->unique()->domainWord(),
            'summary' => null,
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id'    => $page->id,
            'title' => $data['title'],
        ]);
    }

    /**
     * Test page creation with data.
     *
     * @return void
     */
    public function test_canPostCreatePage()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->testData()->create();

        // Define some basic data
        $data = [
            'title'               => $this->faker->unique()->domainWord(),
            'summary'             => null,
            'category_id'         => $category->id,
            'test_category_field' => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"test_category_field":"'.$data['test_category_field'].'","parsed":{"description":null,"test_category_field":"'.$data['test_category_field'].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with data.
     *
     * @return void
     */
    public function test_canPostEditPage()
    {
        $category = SubjectCategory::factory()->testData()->create();
        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'               => $this->faker->unique()->domainWord(),
            'summary'             => null,
            'test_category_field' => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"test_category_field":"'.$data['test_category_field'].'","parsed":{"description":null,"test_category_field":"'.$data['test_category_field'].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a parent.
     *
     * @return void
     */
    public function test_canPostCreatePageWithParent()
    {
        $parent = Page::factory()->create();
        $category = SubjectCategory::factory()->create();

        // Define some basic template data
        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
            'parent_id'   => $parent->id,
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'category_id' => $category->id,
            'title'       => $data['title'],
            'parent_id'   => $parent->id,
        ]);
    }

    /**
     * Test page editing with a parent.
     *
     * @return void
     */
    public function test_canPostEditPageWithParent()
    {
        $page = Page::factory()->create();
        $parent = Page::factory()->create();

        // Define some basic template data
        $data = [
            'title'     => $this->faker->unique()->domainWord(),
            'summary'   => null,
            'parent_id' => $parent->id,
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id'        => $page->id,
            'title'     => $data['title'],
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Test page creation with utility tags.
     *
     * @return void
     */
    public function test_canPostCreatePageWithUtilityTags()
    {
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
     *
     * @return void
     */
    public function test_canPostEditPageWithUtilityTags()
    {
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
     *
     * @return void
     */
    public function test_canPostCreatePageWithPageTags()
    {
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
     *
     * @return void
     */
    public function test_canPostEditPageWithPageTags()
    {
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
