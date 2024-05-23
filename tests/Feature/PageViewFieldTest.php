<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageViewFieldTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        $this->markTestIncomplete();
    }

    /**
     * Test page access with an infobox text field.
     */
    public function testCanGetPageWithInfoboxTextField() {
        // Generate some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with an infobox number field.
     */
    public function testCanGetPageWithInfoboxNumberField() {
        // Generate some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'number')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => mt_rand(1, 100),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":'.$data[$fieldData['key']].',"parsed":{"description":null,"'.$fieldData['key'].'":'.$data[$fieldData['key']].'}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with an infobox checkbox field.
     */
    public function testCanGetPageWithInfoboxCheckboxField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with an infobox choose one field.
     */
    public function testCanGetPageWithInfoboxChooseOneField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with an infobox choose multiple field.
     */
    public function testCanGetPageWithInfoboxChooseMultipleField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->infoboxField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":["'.$data[$fieldData['key']][0].'","'.$data[$fieldData['key']][1].'"],"parsed":{"description":null,"'.$fieldData['key'].'":["'.$data[$fieldData['key']][0].'","'.$data[$fieldData['key']][1].'"]}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with a text field.
     */
    public function testCanGetPageWithTextField() {
        // Generate some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => $this->faker->unique()->domainWord(),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with a number field.
     */
    public function testCanGetPageWithNumberField() {
        // Generate some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'number')->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => mt_rand(1, 100),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":'.$data[$fieldData['key']].',"parsed":{"description":null,"'.$fieldData['key'].'":'.$data[$fieldData['key']].'}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with a checkbox field.
     */
    public function testCanGetPageWithCheckboxField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with a choose one field.
     */
    public function testCanGetPageWithChooseOneField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'choice', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            // This being passed in as string echoes the form input
            $fieldData['key'] => (string) mt_rand(0, 1),
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'","parsed":{"description":null,"'.$fieldData['key'].'":"'.$data[$fieldData['key']].'"}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access with a choose multiple field.
     */
    public function testCanGetPageWithChooseMultipleField() {
        // Generate some data for the field
        $fieldData = [
            'key'     => $this->faker->unique()->domainWord(),
            'label'   => $this->faker->unique()->domainWord(),
            'choices' => '["Choice 1","Choice 2"]',
        ];

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->bodyField($fieldData['key'], $fieldData['label'], 'multiple', null, $fieldData['choices'])->create();

        $page = Page::factory()->category($category->id)->create();

        // Define some basic data
        $data = [
            'title'           => $this->faker->unique()->domainWord(),
            'summary'         => null,
            $fieldData['key'] => [
                0 => (string) mt_rand(0, 1),
                1 => (string) mt_rand(0, 1),
            ],
        ];

        // Create page version and update with field data
        $version = PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();
        $version->update(['data' => '{"data":{"description":null,"'.$fieldData['key'].'":["'.$data[$fieldData['key']][0].'","'.$data[$fieldData['key']][1].'"],"parsed":{"description":null,"'.$fieldData['key'].'":["'.$data[$fieldData['key']][0].'","'.$data[$fieldData['key']][1].'"]}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}']);

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }
}
