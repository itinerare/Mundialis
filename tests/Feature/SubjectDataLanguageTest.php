<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\LexiconSetting;
use App\Models\Subject\LexiconCategory;
use App\Models\Lexicon\LexiconEntry;

class SubjectDataLanguageTest extends TestCase
{
    use withFaker;

    /******************************************************************************
        LANGUAGE
    *******************************************************************************/

    /**
     * Test lexicon settings access.
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
     * Test lexicon setting creation.
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
     * Test lexicon setting editing.
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
     * Test lexicon categories access.
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
     * Test create lexicon category access.
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
     * Test edit lexicon category access.
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
     * Test lexicon category creation.
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
     * Test lexicon category editing.
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
     * Test lexicon category creation with a parent.
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
     * Test lexicon category editing with a parent.
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
     * Test lexicon category creation with some data.
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
     * Test lexicon category editing with some data.
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
     * Test lexicon category editing, removing data.
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryRemovingData()
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
     * Test lexicon category editing with extended data.
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
     * Test lexicon category editing, removing extended data.
     *
     * @return void
     */
    public function test_canPostEditLexiconCategoryRemovingExtendedData()
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

    /**
     * Test lexicon category delete access.
     *
     * @return void
     */
    public function test_canGetDeleteLexiconCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/language/lexicon-categories/delete/'.$category->id)
            ->assertStatus(200);
    }

    /**
     * Test lexicon category deletion.
     * This should work.
     *
     * @return void
     */
    public function test_canPostDeleteLexiconCategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Create a category to delete
        $category = LexiconCategory::factory()->create();

        // Count existing categories
        $oldCount = LexiconCategory::all()->count();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/delete/'.$category->id);

        // Check that there are fewer categories than before
        $this->assertTrue(LexiconCategory::all()->count() < $oldCount);
    }

    /**
     * Test lexicon category deletion with a lexicon entry.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostDeleteLexiconCategoryWithEntry()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing categories
        $oldCount = LexiconCategory::all()->count();
        // Create a category to delete
        $category = LexiconCategory::factory()->create();
        // Create an entry in the category
        $entry = LexiconEntry::factory()->category($category->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/delete/'.$category->id);

        // Check that there are the same number of categories or more
        $this->assertTrue(LexiconCategory::all()->count() >= $oldCount);
    }

    /**
     * Test lexicon category deletion with a sub-category.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostDeleteLexiconCategoryWithSubcategory()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing categories
        $oldCount = LexiconCategory::all()->count();
        // Create a category to delete
        $category = LexiconCategory::factory()->create();
        // Create a subcategory of the category, and set its parent ID
        $subcategory = LexiconCategory::factory()->create();
        $subcategory->update(['parent_id' => $category->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/language/lexicon-categories/delete/'.$category->id);

        // Check that there are the same number of categories or more
        $this->assertTrue(LexiconCategory::all()->count() >= $oldCount);
    }
}
