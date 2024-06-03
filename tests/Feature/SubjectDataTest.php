<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\SubjectTemplate;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubjectDataTest extends TestCase {
    use RefreshDatabase, withFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->make();

        // Delete any subject categories present due to other tests
        if (SubjectCategory::query()->count()) {
            SubjectCategory::query()->delete();
        }
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

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/'.$subject)
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSee($category->name);
        }
        if ($withUnrelated) {
            $response->assertDontSee($unrelated->name);
        }
    }

    public static function getSubjectIndexProvider() {
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

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/'.$subject.'/edit')
            ->assertStatus($status);

        if ($withData && $status == 200) {
            // The template factory supplies some pre-set data,
            // so just check for one of the field keys
            $response->assertSee('test_subject_field');
        }
    }

    public static function getEditSubjectTemplateProvider() {
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
            ->actingAs($this->admin)
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

    public static function postEditSubjectTemplateProvider() {
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
     *
     * @dataProvider getSubjectCategoryProvider
     *
     * @param bool $withCategory
     */
    public function testGetCreateSubjectCategory($withCategory) {
        if ($withCategory) {
            $category = SubjectCategory::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/misc/create')
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSeeText($category->name);
        } else {
            $response->assertViewHas('categoryOptions', function ($categories) {
                return count($categories) == 0;
            });
        }
    }

    public static function getSubjectCategoryProvider() {
        return [
            'basic'         => [0],
            'with category' => [1],
        ];
    }

    /**
     * Test subject category edit access.
     *
     * @dataProvider getSubjectCategoryProvider
     *
     * @param bool $withCategory
     */
    public function testGetEditSubjectCategory($withCategory) {
        $category = SubjectCategory::factory()->create();

        if ($withCategory) {
            $categoryOption = SubjectCategory::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/categories/edit/'.$category->id)
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
     * Test subject category creation.
     *
     * @dataProvider postSubjectCategoryProvider
     *
     * @param bool $withName
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $withImage
     * @param bool $populateData
     * @param bool $expected
     */
    public function testPostCreateSubjectCategory($withName, $withParent, $withDescription, $withImage, $populateData, $expected) {
        if ($withParent) {
            $parent = SubjectCategory::factory()->testData()->create();
        }

        if ($withImage) {
            Storage::fake('public');
            $file = UploadedFile::fake()->image('test_image.png');
        }

        if ($populateData && !$withParent) {
            SubjectTemplate::factory()->create();
        }

        $data = [
            'name'              => $withName ? $this->faker->unique()->domainWord() : null,
            'parent_id'         => $withParent ? $parent->id : null,
            'description'       => $withDescription ? $this->faker->unique()->domainWord() : null,
            'populate_template' => $populateData ? 1 : null,
            'image'             => $withImage ? $file : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/misc/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('subject_categories', [
                'subject'     => 'misc',
                'name'        => $data['name'],
                'parent_id'   => $data['parent_id'],
                'description' => $data['description'],
                'has_image'   => $withImage,
                'data'        => $populateData ? ($withParent ? '{"sections":{"test_category_section":{"name":"Test Category Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}' : '{"sections":{"test_subject_section":{"name":"Test Subject Section"}},"infobox":{"test_subject_field":{"label":"Test Subject Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}') : null,
            ]);

            if ($withImage) {
                $category = SubjectCategory::where('subject', 'misc')->where('name', $data['name'])->first();

                $this->assertTrue(File::exists(public_path('images/data/categories/'.$category->id.'-image.png')));

                unlink(public_path('images/data/categories/'.$category->id.'-image.png'));
            }
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('subject_categories', [
                'subject'     => 'misc',
                'name'        => $data['name'],
                'parent_id'   => $data['parent_id'],
                'description' => $data['description'],
                'has_image'   => $withImage,
                'data'        => $populateData ? ($withParent ? '{"sections":{"test_category_section":{"name":"Test Category Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}' : '{"sections":{"test_subject_section":{"name":"Test Subject Section"}},"infobox":{"test_subject_field":{"label":"Test Subject Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}') : null,
            ]);
        }
    }

    /**
     * Test subject category editing.
     *
     * @dataProvider postSubjectCategoryProvider
     *
     * @param bool $withName
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $withImage
     * @param bool $populateData
     * @param bool $expected
     */
    public function testPostEditSubjectCategory($withName, $withParent, $withDescription, $withImage, $populateData, $expected) {
        if ($withParent) {
            $parent = SubjectCategory::factory()->testData()->create();
        }

        $category = SubjectCategory::factory()->testData()->create([
            'parent_id' => $withParent ? $parent->id : null,
        ]);

        if ($withImage) {
            Storage::fake('public');
            $file = UploadedFile::fake()->image('test_image.png');
        }

        if ($populateData && !$withParent) {
            SubjectTemplate::factory()->create();
        }

        $data = [
            'name'              => $withName ? $this->faker->unique()->domainWord() : null,
            'parent_id'         => $withParent ? $parent->id : null,
            'description'       => $withDescription ? $this->faker->unique()->domainWord() : null,
            'populate_template' => $populateData ? 1 : null,
            'image'             => $withImage ? $file : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('subject_categories', [
                'id'          => $category->id,
                'subject'     => 'misc',
                'name'        => $data['name'],
                'parent_id'   => $data['parent_id'],
                'description' => $data['description'],
                'has_image'   => $withImage,
                'data'        => $populateData ? ($withParent ? '{"sections":{"test_category_section":{"name":"Test Category Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}' : '{"sections":{"test_subject_section":{"name":"Test Subject Section"}},"infobox":{"test_subject_field":{"label":"Test Subject Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}') : null,
            ]);

            if ($withImage) {
                $category = SubjectCategory::where('subject', 'misc')->where('name', $data['name'])->first();

                $this->assertTrue(File::exists(public_path('images/data/categories/'.$category->id.'-image.png')));

                unlink(public_path('images/data/categories/'.$category->id.'-image.png'));
            }
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('subject_categories', [
                'id'          => $category->id,
                'subject'     => 'misc',
                'name'        => $data['name'],
                'parent_id'   => $data['parent_id'],
                'description' => $data['description'],
                'has_image'   => $withImage,
                'data'        => $populateData ? ($withParent ? '{"sections":{"test_category_section":{"name":"Test Category Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}' : '{"sections":{"test_subject_section":{"name":"Test Subject Section"}},"infobox":{"test_subject_field":{"label":"Test Subject Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}') : null,
            ]);
        }
    }

    public static function postSubjectCategoryProvider() {
        return [
            'with name'                        => [1, 0, 0, 0, 0, 1],
            'with name, parent'                => [1, 1, 0, 0, 0, 1],
            'with name, description'           => [1, 0, 1, 0, 0, 1],
            'with name, parent, description'   => [1, 1, 1, 0, 0, 1],
            'with name, image'                 => [1, 0, 0, 1, 0, 1],
            'with name, parent, image'         => [1, 1, 0, 1, 0, 1],
            'with name, description, image'    => [1, 0, 1, 1, 0, 1],
            'with name, populate data'         => [1, 0, 0, 0, 1, 1],
            'with name, parent, populate data' => [1, 1, 0, 0, 1, 1],
            'with everything'                  => [1, 1, 1, 1, 0, 1],
            'without name'                     => [0, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * Test subject category editing with data.
     */
    public function testPostEditSubjectCategoryWithData() {
        $category = SubjectCategory::factory()->create();

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

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $category->id,
            'name' => $data['name'],
            'data' => '{"sections":{"test_category_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing and cascading.
     */
    public function testPostEditSubjectCategoryAndCascade() {
        $category = SubjectCategory::factory()->subject('concepts')->create();
        $recipient = SubjectCategory::factory()->subject('concepts')->testData()->create();

        $recipient->update(['parent_id' => $category->id]);

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

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $recipient->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing and cascading recursively.
     */
    public function testPostEditSubjectCategoryAndCascadeRecursively() {
        $category = SubjectCategory::factory()->subject('concepts')->create();
        $child = SubjectCategory::factory()->subject('concepts')->testData()->create();
        $grandchild = SubjectCategory::factory()->subject('concepts')->testData()->create();

        $child->update(['parent_id' => $category->id]);
        $grandchild->update(['parent_id' => $child->id]);

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

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/edit/'.$category->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('subject_categories', [
            'id'   => $grandchild->id,
            'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"},"test_section":{"name":"Test Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null},"test_field":{"label":"Test Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject category delete access.
     *
     * @dataProvider getSubjectCategoryProvider
     *
     * @param bool $withCategory
     */
    public function testGetDeleteSubjectCategory($withCategory) {
        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/categories/delete/'.($withCategory ? $category->id : 9999))
            ->assertStatus(200);

        if ($withCategory) {
            $response->assertSeeText('You are about to delete the category '.$category->name);
        } else {
            $response->assertSeeText('Invalid category selected');
        }
    }

    /**
     * Test subject category deletion.
     *
     * @dataProvider postDeleteSubjectCategoryProvider
     *
     * @param bool $withCategory
     * @param bool $withChild
     * @param bool $withPage
     * @param bool $expected
     */
    public function testPostDeleteSubjectCategory($withCategory, $withChild, $withPage, $expected) {
        $category = SubjectCategory::factory()->create();

        if ($withChild) {
            SubjectCategory::factory()->create([
                'parent_id' => $category->id,
            ]);
        }

        if ($withPage) {
            Page::factory()->category($category->id)->create();
        }

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/categories/delete/'.($withCategory ? $category->id : 9999));

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertModelMissing($category);
        } else {
            $response->assertSessionHasErrors();
            $this->assertModelExists($category);
        }
    }

    public static function postDeleteSubjectCategoryProvider() {
        return [
            'with category'        => [1, 0, 0, 1],
            'with category, child' => [1, 1, 0, 0],
            'with category, page'  => [1, 0, 1, 0],
            'with everything'      => [1, 1, 1, 0],
            'without category'     => [0, 0, 0, 0],
        ];
    }
}
