<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Page\Page;
use App\Models\Page\PageRelationship;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;

class PageRelationshipTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test page relationships access.
     *
     * @return void
     */
    public function test_canGetRelationships()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a page in the category
        $page = Page::factory()->category($category->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/relationships');

        $response->assertStatus(200);
    }

    /**
     * Test page relationships access.
     *
     * @return void
     */
    public function test_canGetRelationshipsWithRelationship()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page[1]->id.'/relationships');

        $response->assertStatus(200);
    }

    /**
     * Test page relationship creation access.
     *
     * @return void
     */
    public function test_canGetCreateRelationship()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a page in the category
        $page = Page::factory()->category($category->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/relationships/create');

        $response->assertStatus(200);
    }

    /**
     * Test page relationship editing access.
     *
     * @return void
     */
    public function test_canGetEditRelationship()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id);

        $response->assertStatus(200);
    }

    /**
     * Test relationship creation with minimal data.
     *
     * @return void
     */
    public function test_canPostCreateRelationship()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ]);
    }

    /**
     * Test relationship editing with minimal data.
     *
     * @return void
     */
    public function test_canPostEditRelationship()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->familial()->create();

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ]);
    }

    /**
     * Test relationship creation with type info.
     *
     * @return void
     */
    public function test_canPostCreateRelationshipWithTypeInfo()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_one_info' => $this->faker->unique()->domainWord(),
            'type_two' => 'platonic_friend',
            'type_two_info' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_one_info' => $data['type_one_info'],
            'type_two' => 'platonic_friend',
            'type_two_info' => $data['type_two_info'],
        ]);
    }

    /**
     * Test relationship editing with type info.
     *
     * @return void
     */
    public function test_canPostEditRelationshipWithTypeInfo()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->familial()->create();

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_one_info' => $this->faker->unique()->domainWord(),
            'type_two' => 'platonic_friend',
            'type_two_info' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_one_info' => $data['type_one_info'],
            'type_two' => 'platonic_friend',
            'type_two_info' => $data['type_two_info'],
        ]);
    }

    /**
     * Test relationship creation with details.
     *
     * @return void
     */
    public function test_canPostCreateRelationshipWithDetails()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'details_one' => $this->faker->unique()->domainWord(),
            'type_two' => 'platonic_friend',
            'details_two' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'details_one' => $data['details_one'],
            'type_two' => 'platonic_friend',
            'details_two' => $data['details_two'],
        ]);
    }

    /**
     * Test relationship editing with details.
     *
     * @return void
     */
    public function test_canPostEditRelationshipWithDetails()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'details_one' => $this->faker->unique()->domainWord(),
            'type_two' => 'platonic_friend',
            'details_two' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'details_one' => $data['details_one'],
            'type_two' => 'platonic_friend',
            'details_two' => $data['details_two'],
        ]);
    }

    /**
     * Test relationship creation with a custom type and info.
     *
     * @return void
     */
    public function test_canPostCreateRelationshipWithCustomType()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_one_info' => $this->faker->unique()->domainWord(),
            'type_two' => 'custom',
            'type_two_info' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_one_info' => $data['type_one_info'],
            'type_two' => 'custom',
            'type_two_info' => $data['type_two_info'],
        ]);
    }

    /**
     * Test relationship editing with a custom type and info.
     *
     * @return void
     */
    public function test_canPostEditRelationshipWithCustomType()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->familial()->create();

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_one_info' => $this->faker->unique()->domainWord(),
            'type_two' => 'custom',
            'type_two_info' => $this->faker->unique()->domainWord(),
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_one_info' => $data['type_one_info'],
            'type_two' => 'custom',
            'type_two_info' => $data['type_two_info'],
        ]);
    }

    /**
     * Test relationship creation with a custom type but no info.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostCreateRelationshipWithCustomTypeWithoutInfo()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_two' => 'custom',
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/create', $data);

        // Directly verify that no change has occurred
        $this->assertDatabaseMissing('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_two' => 'custom',
        ]);
    }

    /**
     * Test relationship creation with a custom type but no info.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostEditRelationshipWithCustomTypeWithoutInfo()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        // Define some basic data
        $data = [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'custom',
            'type_two' => 'custom',
        ];

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/edit/'.$relationship->id, $data);

        // Directly verify that no change has occurred
        $this->assertDatabaseHas('page_relationships', [
            'page_one_id' => $page[1]->id,
            'page_two_id' => $page[2]->id,
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ]);
    }

    /******************************************************************************
        DELETION
    *******************************************************************************/

    /**
     * Test page relationship editing access.
     *
     * @return void
     */
    public function test_canGetDeleteRelationship()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page[1]->id.'/relationships/delete/'.$relationship->id);

        $response->assertStatus(200);
    }

    /**
     * Test relationship deletion.
     *
     * @return void
     */
    public function test_canPostDeleteRelationship()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a relationship for the two pages
        $relationship = PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page[1]->id.'/relationships/delete/'.$relationship->id);

        // Verify that the appropriate change has occurred
        $this->assertDeleted($relationship);
    }

    /******************************************************************************
        FAMILY TREE ACCESS
    *******************************************************************************/

    /**
     * Test page family tree access.
     *
     * @return void
     */
    public function test_canGetFamilyTree()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a familial relationship for the two pages
        PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->familial()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page[1]->id.'/relationships/tree');

        $response->assertStatus(200);
    }

    /**
     * Test page family tree access without relationships.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotGetFamilyTreeWithoutRelationships()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a page in the category
        $page = Page::factory()->category($category->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/relationships/tree');

        $response->assertStatus(404);
    }

    /**
     * Test page family tree access without any familial relationships.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotGetFamilyTreeWithoutFamily()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a category in the "People" subject
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a couple pages to link
        for ($i = 1; $i <= 2; $i++) {
            $page[$i] = Page::factory()->category($category->id)->create();
        }

        // Create a non-familial relationship for the two pages
        PageRelationship::factory()->pageOne($page[1]->id)->pageTwo($page[2]->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page[1]->id.'/relationships/tree');

        $response->assertStatus(404);
    }
}
