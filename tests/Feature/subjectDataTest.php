<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\SubjectTemplate;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\LexiconSetting;
use App\Models\Subject\LexiconCategory;

class subjectDataTest extends TestCase
{
    use withFaker;

    /******************************************************************************
        SUBJECT TEMPLATE EDITING
    *******************************************************************************/

    /**
     * Test admin access.
     *
     * @return void
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
     * Test subject template creation/editing
     *
     * @return void
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
            'data' => null
        ]);
    }

    /**
     * Test subject template creation/editing
     *
     * @return void
     */
    public function test_canPostEditSubjectTemplate()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template creation/editing
     *
     * @return void
     */
    public function test_canPostEditSubjectTemplateAndCascade()
    {
        // Create a category to cascade changes to
        $category = SubjectCategory::factory()->subject('concepts')->testData()->create();

        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null],
            'cascade_template' => 1,
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // First, ensure the template exists and has empty data
        if(DB::table('subject_templates')->where('subject', 'concepts')->first()) {
            DB::table('subject_templates')->where('subject', 'concepts')->update(
                [
                    'data' => null
                ]
            );
        }
        else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'concepts',
                    'data' => null
                ],
            ]);
        }

        // Then attempt to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/concepts/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $category->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /******************************************************************************
        SUBJECT CATEGORIES
    *******************************************************************************/

    /**
     * Test admin access.
     *
     * @return void
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
     * Test admin access.
     *
     * @return void
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
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostCreateEmptySubjectCategory()
    {
        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
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
            'name' => $data['name'],
            'data' => null
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditEmptySubjectCategory()
    {
        $category = SubjectCategory::factory()->testData()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => null
        ]);
    }

    /**
     * Test subject category creation/editing
     * In practice this will usually be handled by a factory
     * But it's important to also check that they can be created
     *
     * @return void
     */
    public function test_canPostCreateSubjectCategory()
    {
        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'section_key' => [0 => 'test_category_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_category_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'name' => $data['name'],
            'data' => '{"sections":{"test_category_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditSubjectCategory()
    {
        $category = SubjectCategory::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'section_key' => [0 => 'test_category_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_category_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => '{"sections":{"test_category_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostCreateSubjectCategoryWithPopulatedTemplate()
    {
        // Ensure 'things' has specific template data to use
        if(DB::table('subject_templates')->where('subject', 'things')->first()) {
            DB::table('subject_templates')->where('subject', 'things')->update(
                [
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
                ]
            );
        }
        else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'things',
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
                ],
            ]);
        }

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'populate_template' => 1
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
            'name' => $data['name'],
            'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditSubjectCategoryWithPopulatedTemplate()
    {
        // Ensure 'things' has specific template data to use
        if(DB::table('subject_templates')->where('subject', 'things')->first()) {
            DB::table('subject_templates')->where('subject', 'things')->update(
                [
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
                ]
            );
        }
        else {
            DB::table('subject_templates')->insert([
                [
                    'subject' => 'things',
                    'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
                ],
            ]);
        }

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'populate_template' => 1
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
            'id' => $category->id,
            'name' => $data['name'],
            'data' => '{"sections":{"test_section":{"name":"Test Section"}},"infobox":{"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostCreateSubjectCategoryWithParent()
    {
        $parent = SubjectCategory::factory()->subject('places')->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/places/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'subject' => 'places',
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostEditSubjectCategoryWithParent()
    {
        $category = SubjectCategory::factory()->subject('places')->create();
        $parent = SubjectCategory::factory()->subject('places')->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test subject template creation/editing
     *
     * @return void
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
            'name' => $category->name,
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null],
            'cascade_template' => 1,
        ];

        // Then attempt to edit the cascading category
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $recipient->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template creation/editing
     *
     * @return void
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
            'name' => $category->name,
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => 'Test Section'],
            'infobox_key' => [0 => 'test_field'],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => 'Test Field'],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null],
            'cascade_template' => 1,
            'cascade_recursively' => 1
        ];

        // Then attempt to edit the cascading category
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_categories', [
            'id' => $grandchild->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /******************************************************************************
        TIME
    *******************************************************************************/

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetEditTimeDivisions()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/divisions')
            ->assertStatus(200);
    }

    /**
     * Test time division creation/editing
     *
     * @return void
     */
    public function test_canPostCreateTimeDivision()
    {
        // Define some basic template data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
            'unit' => [0 => mt_rand(1,100)]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/divisions', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_divisions', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0]
        ]);
    }

    /**
     * Test time division creation/editing
     *
     * @return void
     */
    public function test_canPostEditTimeDivisions()
    {
        // Define some basic template data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
            'unit' => [0 => mt_rand(1,100)]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $division = TimeDivision::create([
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0],
        ]);

        // Define some more basic template data
        $data['name'][1] = $this->faker->unique()->domainWord();
        $data['abbreviation'][1] = $this->faker->unique()->domainWord();
        $data['unit'][1] = mt_rand(1,100);
        $data['id'][0] = $division->id;

        // Try to post data again
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/divisions', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_divisions', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0],
            'id' => $division->id
        ]);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetTimeChronologies()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetCreateTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology/create')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetEditTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology/edit/'.$chronology->id)
            ->assertStatus(200);
    }

    /**
     * Test subject category creation/editing
     * In practice this will usually be handled by a factory
     * But it's important to also check that they can be created
     *
     * @return void
     */
    public function test_canPostCreateTimeChronology()
    {
        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'description' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditTimeChronology()
    {
        $chronology = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/edit/'.$chronology->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'id' => $chronology->id,
            'name' => $data['name']
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostCreateTimeChronologyWithParent()
    {
        $parent = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostEditTimeChronologyWithParent()
    {
        $chronology = TimeChronology::factory()->create();
        $parent = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/edit/'.$chronology->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'id' => $chronology->id,
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /******************************************************************************
        LANGUAGE
    *******************************************************************************/

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetEditLexiconSettings()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/language/lexicon-settings')
            ->assertStatus(200);
    }

    /**
     * Test lexicon setting creation/editing
     *
     * @return void
     */
    public function test_canPostCreateLexiconSettings()
    {
        // Define some basic data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-settings', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_settings', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0]
        ]);
    }

    /**
     * Test lexicon setting creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconSettings()
    {
        // Define some basic data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $division = LexiconSetting::create([
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
        ]);

        // Define some more basic data
        $data['name'][1] = $this->faker->unique()->domainWord();
        $data['abbreviation'][1] = $this->faker->unique()->domainWord();
        $data['id'][0] = $division->id;

        // Try to post data again
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-settings', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_settings', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'id' => $division->id
        ]);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetLexiconCategories()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/language/lexicon-categories')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetCreateLexiconCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/language/lexicon-categories/create')
            ->assertStatus(200);
    }

    /**
     * Test admin access.
     *
     * @return void
     */
    public function test_canGetEditLexiconCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/language/lexicon-categories/edit/'.$category->id)
            ->assertStatus(200);
    }

    /**
     * Test subject category creation/editing
     * In practice this will usually be handled by a factory
     * But it's important to also check that they can be created
     *
     * @return void
     */
    public function test_canPostCreateLexiconCategory()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'description' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    /**
     * Test subject category creation/editing
     * In practice this will usually be handled by a factory
     * But it's important to also check that they can be created
     *
     * @return void
     */
    public function test_canPostCreateLexiconCategoryWithData()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'description' => $this->faker->unique()->domainWord(),
            'property_name' => [
                0 => 'Number',
                1 => 'Case'
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative'
            ],
            'property_class' => [
                0 => 1,
                1 => 1
            ]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'name' => $data['name'],
            'description' => $data['description'],
            'data' => '{"1":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconCategory()
    {
        $category = LexiconCategory::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name']
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostCreateLexiconCategoryWithParent()
    {
        $parent = LexiconCategory::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test subject category creation/editing with a parent
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryWithParent()
    {
        $category = LexiconCategory::factory()->create();
        $parent = LexiconCategory::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryWithData()
    {
        $category = LexiconCategory::factory()->create();
        $class = LexiconSetting::all()->first();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'property_name' => [
                0 => 'Number',
                1 => 'Case'
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative'
            ],
            'property_class' => [
                0 => $class->id,
                1 => $class->id
            ]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => '{"'.$class->id.'":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryWithoutData()
    {
        $category = LexiconCategory::factory()->testData()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => null
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryWithExtendedData()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');

        $category = LexiconCategory::factory()->testData()->create();
        $class = LexiconSetting::all()->first();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'property_name' => [
                0 => 'Number',
                1 => 'Case'
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative'
            ],
            'property_class' => [
                0 => $class->id,
                1 => $class->id
            ],
            'declension_criteria' => [
                $class->id => [
                    0 => 'a',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ]
            ],
            'declension_regex' => [
                $class->id => [
                    0 => '^',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ]
            ],
            'declension_replacement' => [
                $class->id => [
                    0 => 'b',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ]
            ],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => '{"'.$class->id.'":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}},"conjugation":[{"criteria":["a"],"regex":["^"],"replacement":["b"]}]}}'
        ]);
    }

    /**
     * Test subject category creation/editing
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryWithoutExtendedData()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');

        $category = LexiconCategory::factory()->extendedData()->create();
        $class = LexiconSetting::all()->first();

        // Define some basic template data
        $data = [
            'name' => $category->name
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_categories', [
            'id' => $category->id,
            'name' => $data['name'],
            'data' => null
        ]);
    }

}
