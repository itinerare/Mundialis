<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageEditFieldTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test page creation with an infobox text field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithInfoboxTextField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with an infobox text field.
     *
     * @return void
     */
    public function test_canPostEditPageWithInfoboxTextField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with an infobox number field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithInfoboxNumberField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'number')->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => mt_rand(1, 100),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with an infobox number field.
     *
     * @return void
     */
    public function test_canPostEditPageWithInfoboxNumberField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'number')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => mt_rand(1, 100),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with an infobox checkbox field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithInfoboxCheckboxField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'checkbox')->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => mt_rand(0, 1),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with an infobox checkbox field.
     *
     * @return void
     */
    public function test_canPostEditPageWithInfoboxCheckboxField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'checkbox')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => mt_rand(0, 1),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with an infobox choose one field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithInfoboxChooseOneField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with an infobox choose one field.
     *
     * @return void
     */
    public function test_canPostEditPageWithInfoboxChooseOneField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with an infobox choose multiple field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithInfoboxChooseMultipleField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"],"parsed":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"]}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with an infobox choose multiple field.
     *
     * @return void
     */
    public function test_canPostEditPageWithInfoboxChooseMultipleField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"],"parsed":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"]}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a text field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithTextField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a text field.
     *
     * @return void
     */
    public function test_canPostEditPageWithTextField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a number field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithumberField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'number')->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => mt_rand(1, 100),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a number field.
     *
     * @return void
     */
    public function test_canPostEditPageWithNumberField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'number')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => mt_rand(1, 100),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a checkbox field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithCheckboxField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'checkbox')->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => mt_rand(0, 1),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a checkbox field.
     *
     * @return void
     */
    public function test_canPostEditPageWithCheckboxField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'checkbox')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => mt_rand(0, 1),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . ',"parsed":{"description":null,"' . $fieldData['key'] . '":' . $data[$fieldData['key']] . '}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a choose one field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithChooseOneField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a choose one field.
     *
     * @return void
     */
    public function test_canPostEditPageWithChooseOneField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '","parsed":{"description":null,"' . $fieldData['key'] . '":"' . $data[$fieldData['key']] . '"}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page creation with a choose multiple field.
     *
     * @return void
     */
    public function test_canPostCreatePageWithChooseMultipleField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
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
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"],"parsed":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"]}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a choose multiple field.
     *
     * @return void
     */
    public function test_canPostEditPageWithChooseMultipleField()
    {
        // Generate some data for the field
        $fieldData = [
            'key' => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"],"parsed":{"description":null,"' . $fieldData['key'] . '":["' . $data[$fieldData['key']][0] . '","' . $data[$fieldData['key']][1] . '"]}},"title":"' . $data['title'] . '","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }
}
