<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubjectDataLanguageTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    /******************************************************************************
        LEXICON SETTINGS
    *******************************************************************************/

    /**
     * Test lexicon settings access.
     *
     * @param bool $withClass
     */
    #[DataProvider('getLexiconSettingsProvider')]
    public function testGetLexiconSettings($withClass) {
        if ($withClass) {
            $class = LexiconSetting::create([
                'name'         => $this->faker->unique()->domainWord(),
                'abbreviation' => $this->faker->unique()->domainWord(),
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/language/lexicon-settings')
            ->assertStatus(200);

        if ($withClass) {
            $response->assertSee($class->name);
        } else {
            $response->assertViewHas('parts', function ($parts) {
                return $parts->count() == 0;
            });
        }
    }

    public static function getLexiconSettingsProvider() {
        return [
            'basic'      => [0],
            'with class' => [1],
        ];
    }

    /**
     * Test lexicon setting creation.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $expected
     */
    #[DataProvider('postLexiconSettingsProvider')]
    public function testPostCreateLexiconSetting($withName, $withAbbreviation, $expected) {
        $data = [
            'name'         => [0 => $withName ? $this->faker->unique()->domainWord() : null],
            'abbreviation' => [0 => $withAbbreviation ? $this->faker->unique()->domainWord() : null],
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-settings', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_settings', [
                'name'         => $data['name'][0],
                'abbreviation' => $data['abbreviation'][0],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('lexicon_settings', [
                'name'         => $data['name'][0],
                'abbreviation' => $data['abbreviation'][0],
            ]);
        }
    }

    /**
     * Test lexicon setting editing.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $expected
     */
    #[DataProvider('postLexiconSettingsProvider')]
    public function testPostEditLexiconSettings($withName, $withAbbreviation, $expected) {
        for ($i = 0; $i <= 1; $i++) {
            $class[$i] = LexiconSetting::create([
                'name'         => $this->faker->unique()->domainWord(),
                'abbreviation' => $this->faker->unique()->domainWord(),
            ]);
        }

        $data = [
            'id' => [
                0 => $class[0]->id,
                1 => $class[1]->id,
            ],
            'name' => [
                0 => $withName ? $this->faker->unique()->domainWord() : null,
                1 => $class[1]->name,
            ],
            'abbreviation' => [
                0 => $withAbbreviation ? $this->faker->unique()->domainWord() : null,
                1 => $class[1]->abbreviation,
            ],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-settings', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_settings', [
                'id'            => $class[0]->id,
                'name'          => $data['name'][0],
                'abbreviation'  => $data['abbreviation'][0],
            ]);

            $this->assertDatabaseHas('lexicon_settings', [
                'id'            => $class[1]->id,
                'name'          => $data['name'][1],
                'abbreviation'  => $data['abbreviation'][1],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('lexicon_settings', [
                'id'            => $class[0]->id,
                'name'          => $data['name'][0],
                'abbreviation'  => $data['abbreviation'][0],
            ]);
        }
    }

    public static function postLexiconSettingsProvider() {
        return [
            'basic'             => [1, 0, 1],
            'with abbreviation' => [1, 1, 1],
            'without name'      => [0, 0, 0],
        ];
    }

    /******************************************************************************
        LEXICON CATEGORIES
    *******************************************************************************/

    /**
     * Test lexicon categories access.
     *
     * @param bool $withCategory
     */
    #[DataProvider('getLexiconCategoriesProvider')]
    public function testGetLexiconCategories($withCategory) {
        if ($withCategory) {
            $category = LexiconCategory::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/language/lexicon-categories')
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSeeText($category->name);
        } else {
            $response->assertViewHas('categories', function ($categories) {
                return $categories->count() == 0;
            });
        }
    }

    public static function getLexiconCategoriesProvider() {
        return [
            'basic'         => [0],
            'with category' => [1],
        ];
    }

    /**
     * Test create lexicon category access.
     *
     * @param bool $withCategory
     */
    #[DataProvider('getLexiconCategoriesProvider')]
    public function testGetCreateLexiconCategory($withCategory) {
        if ($withCategory) {
            $category = LexiconCategory::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/language/lexicon-categories/create')
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSeeText($category->name);
        } else {
            $response->assertViewHas('categoryOptions', function ($categories) {
                return count($categories) == 0;
            });
        }
    }

    /**
     * Test edit lexicon category access.
     *
     * @param bool $withCategory
     */
    #[DataProvider('getLexiconCategoriesProvider')]
    public function testGetEditLexiconCategory($withCategory) {
        $category = LexiconCategory::factory()->create();

        if ($withCategory) {
            $categoryOption = LexiconCategory::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/language/lexicon-categories/edit/'.$category->id)
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSee($categoryOption->name);
        } else {
            $response->assertViewHas('categoryOptions', function ($categories) {
                return count($categories) == 0;
            });
        }
    }

    /**
     * Test lexicon category creation.
     *
     * @param bool $withName
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $withData
     * @param bool $expected
     */
    #[DataProvider('postLexiconCategoryProvider')]
    public function testPostCreateLexiconCategory($withName, $withParent, $withDescription, $withData, $expected) {
        // Ensure lexical classes are present to utilize
        $this->artisan('app:add-lexicon-settings');

        if ($withParent) {
            $parent = LexiconCategory::factory()->create();
        }

        $data = [
            'name'        => $withName ? $this->faker->unique()->domainWord() : null,
            'parent_id'   => $withParent ? $parent->id : null,
            'description' => $withDescription ? $this->faker->unique()->domainWord() : null,
        ] + ($withData ? [
            'property_name'       => [
                0 => 'Number',
                1 => 'Case',
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative',
            ],
            'property_class'      => [
                0 => 1,
                1 => 1,
            ],
        ] : []);

        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_categories', [
                'name'         => $data['name'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
                'data'         => $withData ? '{"1":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}' : null,
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('lexicon_categories', [
                'name'         => $data['name'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
                'data'         => $withData ? '{"1":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}' : null,
            ]);
        }
    }

    /**
     * Test lexicon category editing.
     *
     * @param bool $withName
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $withData
     * @param bool $expected
     */
    #[DataProvider('postLexiconCategoryProvider')]
    public function testPostEditLexiconCategory($withName, $withParent, $withDescription, $withData, $expected) {
        // Ensure lexical classes are present to utilize
        $this->artisan('app:add-lexicon-settings');

        $category = LexiconCategory::factory()->create();

        if ($withParent) {
            $parent = LexiconCategory::factory()->create();
        }

        if ($withData) {
            $class = LexiconSetting::all()->first();
        }

        $data = [
            'name'        => $withName ? $this->faker->unique()->domainWord() : null,
            'parent_id'   => $withParent ? $parent->id : null,
            'description' => $withDescription ? $this->faker->unique()->domainWord() : null,
        ] + ($withData ? [
            'property_name'       => [
                0 => 'Number',
                1 => 'Case',
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative',
            ],
            'property_class'      => [
                0 => $class->id,
                1 => $class->id,
            ],
        ] : []);

        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_categories', [
                'id'           => $category->id,
                'name'         => $data['name'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
                'data'         => $withData ? '{"'.$class->id.'":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}' : null,
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('lexicon_categories', [
                'id'           => $category->id,
                'name'         => $data['name'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
                'data'         => $withData ? '{"'.$class->id.'":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}' : null,
            ]);
        }
    }

    public static function postLexiconCategoryProvider() {
        return [
            'with name'                      => [1, 0, 0, 0, 1],
            'with name, parent'              => [1, 1, 0, 0, 1],
            'with name, description'         => [1, 0, 1, 0, 1],
            'with name, data'                => [1, 0, 0, 1, 1],
            'with name, parent, description' => [1, 1, 1, 0, 1],
            'with name, parent, data'        => [1, 1, 0, 1, 1],
            'with name, description, data'   => [1, 0, 1, 1, 1],
            'with everything'                => [1, 1, 1, 1, 1],
            'without name'                   => [0, 0, 0, 0, 0],
        ];
    }

    /**
     * Test lexicon category editing with extended data.
     */
    public function testPostEditLexiconCategoryWithExtendedData() {
        // Ensure lexical classes are present to utilize
        $this->artisan('app:add-lexicon-settings');

        $category = LexiconCategory::factory()->testData()->create();
        $class = LexiconSetting::all()->first();

        $data = [
            'name'          => $this->faker->unique()->domainWord(),
            'property_name' => [
                0 => 'Number',
                1 => 'Case',
            ],
            'property_dimensions' => [
                0 => 'Singular,Plural',
                1 => 'Nominative,Accusative,Dative',
            ],
            'property_class' => [
                0 => $class->id,
                1 => $class->id,
            ],
            'declension_criteria' => [
                $class->id => [
                    0 => 'a',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ],
            ],
            'declension_regex' => [
                $class->id => [
                    0 => '^',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ],
            ],
            'declension_replacement' => [
                $class->id => [
                    0 => 'b',
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => null,
                ],
            ],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lexicon_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => '{"'.$class->id.'":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}},"conjugation":[{"criteria":["a"],"regex":["^"],"replacement":["b"]}]}}',
        ]);
    }

    /**
     * Test lexicon category editing, populating data from a parent.
     */
    public function testPopulateLexiconEntryData() {
        $parent = LexiconCategory::factory()->testData()->create();
        $category = LexiconCategory::factory()->create([
            'parent_id' => $parent->id,
        ]);

        $data = [
            'name'              => $this->faker->unique()->domainWord(),
            'populate_settings' => 1,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lexicon_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => '{"1":{"properties":{"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative"]},"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]}}}}',
        ]);
    }

    /**
     * Test lexicon category editing, removing data.
     */
    public function testRemoveLexiconCategoryData() {
        $category = LexiconCategory::factory()->testData()->create();

        $data = [
            'name' => $this->faker->unique()->domainWord(),
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lexicon_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => null,
        ]);
    }

    /**
     * Test lexicon category delete access.
     *
     * @param bool $withCategory
     */
    #[DataProvider('getLexiconCategoriesProvider')]
    public function testGetDeleteLexiconCategory($withCategory) {
        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/language/lexicon-categories/delete/'.($withCategory ? $category->id : 9999))
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSeeText('You are about to delete the category '.$category->name);
        } else {
            $response->assertSeeText('Invalid category selected');
        }
    }

    /**
     * Test lexicon category deletion.
     *
     * @param bool  $withCategory
     * @param bool  $withChild
     * @param bool  $expected
     * @param mixed $withEntry
     */
    #[DataProvider('postDeleteLexiconCategoryProvider')]
    public function testPostDeleteLexiconCategory($withCategory, $withChild, $withEntry, $expected) {
        $category = LexiconCategory::factory()->create();

        if ($withChild) {
            LexiconCategory::factory()->create([
                'parent_id' => $category->id,
            ]);
        }

        if ($withEntry) {
            $entry = LexiconEntry::factory()->category($category->id)->create();
        }

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/language/lexicon-categories/delete/'.($withCategory ? $category->id : 9999));

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertModelMissing($category);
        } else {
            $response->assertSessionHasErrors();
            $this->assertModelExists($category);
        }
    }

    public static function postDeleteLexiconCategoryProvider() {
        return [
            'with category'        => [1, 0, 0, 1],
            'with category, child' => [1, 1, 0, 0],
            'with category, entry' => [1, 0, 1, 0],
            'with everything'      => [1, 1, 1, 0],
            'without category'     => [0, 0, 0, 0],
        ];
    }
}
