<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\SubjectTemplate;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SubjectDataTest extends TestCase {
    use RefreshDatabase, withFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->admin()->make();
    }

    /******************************************************************************
        SUBJECT / ADMIN INDICES
    *******************************************************************************/

    /**
     * Test subject category index access.
     *
     * @dataProvider getSubjectIndexProvider
     *
     * @param string $subject
     * @param bool   $withCategory
     * @param bool   $withUnrelated
     */
    public function testGetSubjectIndex($subject, $withCategory, $withUnrelated) {
        if ($withCategory) {
            $category = SubjectCategory::factory()->subject($subject)->create();
        }
        if ($withUnrelated) {
            $unrelated = SubjectCategory::factory()->subject($subject != 'misc' ? 'misc' : 'people')->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/admin/data/'.$subject)
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSee($category->name);
        }
        if ($withUnrelated) {
            $response->assertDontSee($unrelated->name);
        }
    }

    public function getSubjectIndexProvider() {
        return [
            'people'                  => ['people', 0, 0],
            'people with category'    => ['people', 1, 0],
            'people with unrelated'   => ['people', 0, 1],
            'places'                  => ['places', 0, 0],
            'places with category'    => ['places', 1, 0],
            'places with unrelated'   => ['places', 0, 1],
            'species'                 => ['species', 0, 0],
            'species with category'   => ['species', 1, 0],
            'species with unrelated'  => ['species', 0, 1],
            'things'                  => ['things', 0, 0],
            'things with category'    => ['things', 1, 0],
            'things with unrelated'   => ['things', 0, 1],
            'concepts'                => ['concepts', 0, 0],
            'concepts with category'  => ['concepts', 1, 0],
            'concepts with unrelated' => ['concepts', 0, 1],
            'time'                    => ['time', 0, 0],
            'time with category'      => ['time', 1, 0],
            'time with unrelated'     => ['time', 0, 1],
            'language'                => ['language', 0, 0],
            'language with category'  => ['language', 1, 0],
            'language with unrelated' => ['language', 0, 1],
            'misc'                    => ['misc', 0, 0],
            'misc with category'      => ['misc', 1, 0],
            'misc with unrelated'     => ['misc', 0, 1],
        ];
    }

    /******************************************************************************
        SUBJECT / TEMPLATE EDITING
    *******************************************************************************/

    /**
     * Test subject template access.
     *
     * @dataProvider getEditSubjectTemplateProvider
     *
     * @param string $subject
     * @param bool   $withData
     * @param int    $status
     */
    public function testGetEditSubjectTemplate($subject, $withData, $status) {
        if ($withData) {
            SubjectTemplate::factory()->subject($subject)->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/admin/data/'.$subject.'/edit')
            ->assertStatus($status);

        if ($withData && $status == 200) {
            // The template factory supplies some pre-set data,
            // so just check for one of the field keys
            $response->assertSee('test_subject_field');
        }
    }

    public function getEditSubjectTemplateProvider() {
        return [
            'people'             => ['people', 0, 200],
            'people with data'   => ['people', 1, 200],
            'places'             => ['places', 0, 200],
            'places with data'   => ['places', 1, 200],
            'species'            => ['species', 0, 200],
            'species with data'  => ['species', 1, 200],
            'things'             => ['things', 0, 200],
            'things with data'   => ['things', 1, 200],
            'concepts'           => ['concepts', 0, 200],
            'concepts with data' => ['concepts', 1, 200],
            'time'               => ['time', 0, 200],
            'time with data'     => ['time', 1, 200],
            'language'           => ['language', 0, 200],
            'language with data' => ['language', 1, 200],
            'misc'               => ['misc', 0, 200],
            'misc with data'     => ['misc', 1, 200],
            'invalid'            => ['invalid', 0, 404],
            'invalid with data'  => ['invalid', 1, 404],
        ];
    }

    /**
     * Test subject template editing.
     *
     * @dataProvider postEditSubjectTemplateProvider
     *
     * @param string $subject
     * @param bool   $withData
     * @param bool   $cascade
     * @param bool   $expected
     */
    public function testPostEditSubjectTemplate($subject, $withData, $cascade, $expected) {
        if ($withData) {
            // Supply some test data
            $data = [
                //
                'section_key'      => [0 => 'test_section'],
                'section_name'     => [0 => 'Test Section'],
                'infobox_key'      => [0 => 'test_field'],
                'infobox_type'     => [0 => 'text'],
                'infobox_label'    => [0 => 'Test Field'],
                'infobox_rules'    => [0 => null],
                'infobox_choices'  => [0 => null],
                'infobox_value'    => [0 => null],
                'infobox_help'     => [0 => null],
                'cascade_template' => $cascade,
            ];
        } else {
            // Else supply an empty array
            $data = [];
        }

        if ($cascade) {
            // Create a category to cascade changes to
            $category = SubjectCategory::factory()->subject($subject)->testData()->create();
        }

        $response = $this
            ->actingAs($this->user)
            ->post('/admin/data/'.$subject.'/edit', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('subject_templates', [
                'subject' => $subject,
                'data'    => $withData ? '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}' : null,
            ]);

            if ($cascade) {
                // Verify that the data has cascaded to the category
                $this->assertDatabaseHas('subject_categories', [
                    'id'   => $category->id,
                    'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
                ]);
            }
        } else {
            // The subject-related routes are directly configured
            // to only accept input provided in the site's subject config file,
            // so an invalid subject simply results in a 404
            $response->assertStatus(404);
        }
    }

    public function postEditSubjectTemplateProvider() {
        return [
            'people'             => ['people', 0, 0, 1],
            'people with data'   => ['people', 1, 0, 1],
            'people cascaded'    => ['people', 1, 1, 1],
            'places'             => ['places', 0, 0, 1],
            'places with data'   => ['places', 1, 0, 1],
            'places cascaded'    => ['places', 1, 1, 1],
            'species'            => ['species', 0, 0, 1],
            'species with data'  => ['species', 1, 0, 1],
            'species cascaded'   => ['species', 1, 1, 1],
            'things'             => ['things', 0, 0, 1],
            'things with data'   => ['things', 1, 0, 1],
            'things cascaded'    => ['things', 1, 1, 1],
            'concepts'           => ['concepts', 0, 0, 1],
            'concepts with data' => ['concepts', 1, 0, 1],
            'concepts cascaded'  => ['concepts', 1, 1, 1],
            'time'               => ['time', 0, 0, 1],
            'time with data'     => ['time', 1, 0, 1],
            'time cascaded'      => ['time', 1, 1, 1],
            'language'           => ['language', 0, 0, 1],
            'language with data' => ['language', 1, 0, 1],
            'language cascaded'  => ['language', 1, 1, 1],
            'misc'               => ['misc', 0, 0, 1],
            'misc with data'     => ['misc', 1, 0, 1],
            'misc cascaded'      => ['misc', 1, 1, 1],
            'invalid'            => ['invalid', 0, 0, 0],
            'invalid with data'  => ['invalid', 1, 0, 0],
            'invalid cascaded'   => ['invalid', 1, 1, 0],
        ];
    }

    /******************************************************************************
        SUBJECT CATEGORIES
    *******************************************************************************/

    /**
     * Test subject category create access.
     */
    public function testCanGetCreateSubjectCategory() {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/misc/create')
            ->assertStatus(200);
    }

    /**
     * Test subject category edit access.
     */
    public function testCanGetEditSubjectCategory() {
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
    public function testCanPostCreateEmptySubjectCategory() {
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
    public function testCanPostEditEmptySubjectCategory() {
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
    public function testCanPostCreateSubjectCategory() {
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
    public function testCanPostEditSubjectCategory() {
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
    public function testCanPostCreateSubjectCategoryWithPopulatedTemplate() {
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
    public function testCanPostEditSubjectCategoryWithPopulatedTemplate() {
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
    public function testCanPostCreateSubjectCategoryWithParent() {
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
    public function testCanPostEditSubjectCategoryWithParent() {
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
    public function testCanPostEditSubjectCategoryAndCascade() {
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
    public function testCanPostEditSubjectCategoryAndCascadeRecursively() {
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
    public function testCanGetDeleteSubjectCategory() {
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
    public function testCanPostDeleteSubjectCategory() {
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
    public function testCannotPostDeleteSubjectCategoryWithPage() {
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
    public function testCannotPostDeleteSubjectCategoryWithSubcategory() {
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
