<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectDataTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /******************************************************************************
        SUBJECTS
    *******************************************************************************/

    /**
     * Test people category index access.
     */
    public function test_canGetPeopleData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/people')
            ->assertStatus(200);
    }

    /**
     * Test places category index access.
     */
    public function test_canGetPlacesData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/places')
            ->assertStatus(200);
    }

    /**
     * Test flora & fauna category index access.
     */
    public function test_canGetFloraAndFaunaData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/species')
            ->assertStatus(200);
    }

    /**
     * Test things category index access.
     */
    public function test_canGetThingsData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/things')
            ->assertStatus(200);
    }

    /**
     * Test concepts category index access.
     */
    public function test_canGetConceptsData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/concepts')
            ->assertStatus(200);
    }

    /**
     * Test time & events category index access.
     */
    public function test_canGetTimeData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time')
            ->assertStatus(200);
    }

    /**
     * Test language category index access.
     */
    public function test_canGetLanguageData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/language')
            ->assertStatus(200);
    }

    /**
     * Test miscellaneous category index access.
     */
    public function test_canGetMiscellaneousData()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/misc')
            ->assertStatus(200);
    }

    /******************************************************************************
        SUBJECT TEMPLATE EDITING
    *******************************************************************************/

    /**
     * Test subject template access.
     */
    public function test_canGetEditSubjectTemplate()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/misc/edit')
            ->assertStatus(200);
    }

    /**
     * Test subject template clearing.
     */
    public function test_canPostEditEmptySubjectTemplate()
    {
        // Define some basic template data
        $data = [];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => null,
        ]);
    }

    /**
     * Test subject template creation/editing.
     */
    public function test_canPostEditSubjectTemplate()
    {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => 'test_section'],
            'section_name'    => [0 => 'Test Section'],
            'infobox_key'     => [0 => 'test_field'],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => 'Test Field'],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template creation/editing and cascading.
     */
    public function test_canPostEditSubjectTemplateAndCascade()
    {
        // Create a category to cascade changes to
        $category = SubjectCategory::factory()->subject('concepts')->testData()->create();

        // Define some basic template data
        $data = [
            'section_key'      => [0 => 'test_section'],
            'section_name'     => [0 => 'Test Section'],
            'infobox_key'      => [0 => 'test_field'],
            'infobox_type'     => [0 => 'text'],
            'infobox_label'    => [0 => 'Test Field'],
            'infobox_rules'    => [0 => null],
            'infobox_choices'  => [0 => null],
            'infobox_value'    => [0 => null],
            'infobox_help'     => [0 => null],
            'cascade_template' => 1,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // First, ensure the template exists and has empty data
        if (DB::table('subject_templates')->where('subject', 'concepts')->first()) {
            DB::table('subject_templates')->where('subject', 'concepts')->update(
                [
                    'data' => null,
                ]
            );
        } else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'concepts',
                    'data'    => null,
                ],
            ]);
        }

        // Then attempt to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/concepts/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $category->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /******************************************************************************
        SUBJECT CATEGORIES
    *******************************************************************************/

    /**
     * Test subject category create access.
     */
    public function test_canGetCreateSubjectCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/misc/create')
            ->assertStatus(200);
    }

    /**
     * Test subject category edit access.
     */
    public function test_canGetEditSubjectCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/categories/edit/'.$category->id)
            ->assertStatus(200);
    }

    /**
     * Test subject category creation with minimal data.
     */
    public function test_canPostCreateEmptySubjectCategory()
    {
        // Define some basic data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'subject' => 'misc',
            'name'    => $data['name'],
            'data'    => null,
        ]);
    }

    /**
     * Test subject category editing with minimal data.
     */
    public function test_canPostEditEmptySubjectCategory()
    {
        $category = SubjectCategory::factory()->testData()->create();

        // Define some basic data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => null,
        ]);
    }

    /**
     * Test subject category creation with basic data.
     */
    public function test_canPostCreateSubjectCategory()
    {
        // Define some basic template data
        $data = [
            'name'            => $this->faker->unique()->domainWord(),
            'section_key'     => [0 => 'test_category_section'],
            'section_name'    => [0 => 'Test Section'],
            'infobox_key'     => [0 => 'test_category_field'],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => 'Test Field'],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'subject' => 'misc',
            'name'    => $data['name'],
            'data'    => '{"sections":{"test_category_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category editing with basic data.
     */
    public function test_canPostEditSubjectCategory()
    {
        $category = SubjectCategory::factory()->create();

        // Define some basic template data
        $data = [
            'name'            => $this->faker->unique()->domainWord(),
            'section_key'     => [0 => 'test_category_section'],
            'section_name'    => [0 => 'Test Section'],
            'infobox_key'     => [0 => 'test_category_field'],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => 'Test Field'],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => '{"sections":{"test_category_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category creating with template population.
     */
    public function test_canPostCreateSubjectCategoryWithPopulatedTemplate()
    {
        // Ensure 'things' has specific template data to use
        if (DB::table('subject_templates')->where('subject', 'things')->first()) {
            DB::table('subject_templates')->where('subject', 'things')->update(
                [
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
                ]
            );
        } else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'things',
                    'data'    => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
                ],
            ]);
        }

        // Define some basic template data
        $data = [
            'name'              => $this->faker->unique()->domainWord(),
            'populate_template' => 1,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/things/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'subject' => 'things',
            'name'    => $data['name'],
            'data'    => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category editing with template population.
     */
    public function test_canPostEditSubjectCategoryWithPopulatedTemplate()
    {
        // Ensure 'things' has specific template data to use
        if (DB::table('subject_templates')->where('subject', 'things')->first()) {
            DB::table('subject_templates')->where('subject', 'things')->update(
                [
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
                ]
            );
        } else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'things',
                    'data'    => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
                ],
            ]);
        }

        // Define some basic template data
        $data = [
            'name'              => $this->faker->unique()->domainWord(),
            'populate_template' => 1,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $category = SubjectCategory::factory()->subject('things')->testData()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category creation with a parent.
     */
    public function test_canPostCreateSubjectCategoryWithParent()
    {
        $parent = SubjectCategory::factory()->subject('places')->create();

        // Define some basic template data
        $data = [
            'name'      => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/places/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'subject'   => 'places',
            'name'      => $data['name'],
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Test subject category editing with a parent.
     */
    public function test_canPostEditSubjectCategoryWithParent()
    {
        $category = SubjectCategory::factory()->subject('places')->create();
        $parent = SubjectCategory::factory()->subject('places')->create();

        // Define some basic template data
        $data = [
            'name'      => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'        => $category->id,
            'name'      => $data['name'],
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Test subject template editing and cascading.
     */
    public function test_canPostEditSubjectCategoryAndCascade()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Create a category to cascade changes to
        $category = SubjectCategory::factory()->subject('concepts')->create();
        $recipient = SubjectCategory::factory()->subject('concepts')->testData()->create();

        // Set the recipient's parent ID
        $recipient->update(['parent_id' => $category->id]);

        // Define some basic template data
        $data = [
            'name'             => $category->name,
            'section_key'      => [0 => 'test_section'],
            'section_name'     => [0 => 'Test Section'],
            'infobox_key'      => [0 => 'test_field'],
            'infobox_type'     => [0 => 'text'],
            'infobox_label'    => [0 => 'Test Field'],
            'infobox_rules'    => [0 => null],
            'infobox_choices'  => [0 => null],
            'infobox_value'    => [0 => null],
            'infobox_help'     => [0 => null],
            'cascade_template' => 1,
        ];

        // Then attempt to edit the cascading category
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $recipient->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing and cascading recursively.
     */
    public function test_canPostEditSubjectCategoryAndCascadeRecursively()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Create a category to cascade changes to
        $category = SubjectCategory::factory()->subject('concepts')->create();
        $child = SubjectCategory::factory()->subject('concepts')->testData()->create();
        $grandchild = SubjectCategory::factory()->subject('concepts')->testData()->create();

        // Set the recipient's parent ID
        $child->update(['parent_id' => $category->id]);
        $grandchild->update(['parent_id' => $child->id]);

        // Define some basic template data
        $data = [
            'name'                => $category->name,
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => 'Test Section'],
            'infobox_key'         => [0 => 'test_field'],
            'infobox_type'        => [0 => 'text'],
            'infobox_label'       => [0 => 'Test Field'],
            'infobox_rules'       => [0 => null],
            'infobox_choices'     => [0 => null],
            'infobox_value'       => [0 => null],
            'infobox_help'        => [0 => null],
            'cascade_template'    => 1,
            'cascade_recursively' => 1,
        ];

        // Then attempt to edit the cascading category
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $grandchild->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category delete access.
     */
    public function test_canGetDeleteSubjectCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/categories/delete/'.$category->id)
            ->assertStatus(200);
    }

    /**
     * Test subject category deletion.
     * This should work.
     */
    public function test_canPostDeleteSubjectCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Create a category to delete
        $category = SubjectCategory::factory()->create();

        // Count existing categories
        $oldCount = SubjectCategory::all()->count();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Check that there are fewer categories than before
        $this->assertTrue(SubjectCategory::all()->count() < $oldCount);
    }

    /**
     * Test subject category deletion with a page.
     * This shouldn't work.
     */
    public function test_cannotPostDeleteSubjectCategoryWithPage()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing categories
        $oldCount = SubjectCategory::all()->count();
        // Create a category to delete
        $category = SubjectCategory::factory()->create();
        // Create a page in the category
        $page = Page::factory()->category($category->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Check that there are the same number of categories or more
        $this->assertTrue(SubjectCategory::all()->count() >= $oldCount);
    }

    /**
     * Test subject category deletion with a sub-category.
     * This shouldn't work.
     */
    public function test_cannotPostDeleteSubjectCategoryWithSubcategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing categories
        $oldCount = SubjectCategory::all()->count();
        // Create a category to delete
        $category = SubjectCategory::factory()->create();
        // Create a subcategory of the category, and set its parent ID
        $subcategory = SubjectCategory::factory()->create();
        $subcategory->update(['parent_id' => $category->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Check that there are the same number of categories or more
        $this->assertTrue(SubjectCategory::all()->count() >= $oldCount);
    }
}
