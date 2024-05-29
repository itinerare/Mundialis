<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageEditTest extends TestCase {
    use RefreshDatabase, withFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test page creation access.
     *
     * @dataProvider getCreatePageProvider
     *
     * @param bool $withCategory
     * @param int  $status
     */
    public function testGetCreatePage($withCategory, $status) {
        if ($withCategory) {
            $category = SubjectCategory::factory()->create();
        }

        $response = $this->actingAs($this->editor)
            ->get('/pages/create/'.($withCategory ? $category->id : 9999));

        $response->assertStatus($status);
    }

    public static function getCreatePageProvider() {
        return [
            'with category'    => [1, 200],
            'without category' => [0, 404],
        ];
    }

    /**
     * Test page editing access.
     *
     * @dataProvider getEditPageProvider
     *
     * @param bool $withPage
     * @param int  $status
     */
    public function testGetEditPage($withPage, $status) {
        if ($withPage) {
            $page = Page::factory()->create();
        }

        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/edit');

        $response->assertStatus($status);
    }

    public static function getEditPageProvider() {
        return [
            'with category'    => [1, 200],
            'without category' => [0, 404],
        ];
    }

    /**
     * Test page creation.
     *
     * @dataProvider postCreateEditPageProvider
     * @dataProvider postCreatePageProvider
     *
     * @param bool $withCategory
     * @param bool $withTitle
     * @param bool $withSummary
     * @param bool $withData
     * @param bool $withParent
     * @param bool $withUtilityTag
     * @param bool $withPageTag
     * @param bool $expected
     */
    public function testPostCreatePage($withCategory, $withTitle, $withSummary, $withData, $withParent, $withUtilityTag, $withPageTag, $expected) {
        if ($withCategory) {
            $category = SubjectCategory::factory()->testData()->create();
        }

        if ($withParent) {
            $parent = Page::factory()->create();
            PageVersion::factory()->page($parent->id)->user($this->editor->id)->create();
        }

        $data = [
            'title'               => $withTitle ? $this->faker->unique()->domainWord().$this->faker->unique()->domainWord() : null,
            'summary'             => $withSummary ? $this->faker->unique()->domainWord() : null,
            'category_id'         => $withCategory ? $category->id : null,
            'parent_id'           => $withParent ? $parent->id : null,
            'test_category_field' => $withData ? $this->faker->unique()->domainWord() : null,
            'utility_tag'         => $withUtilityTag ? [0 => 'wip'] : null,
            'page_tag'            => $withPageTag ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('pages', [
                'category_id' => $category->id,
                'title'       => $data['title'],
                'parent_id'   => $withParent ? $parent->id : null,
            ]);

            $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

            $this->assertDatabaseHas('page_versions', [
                'page_id' => $page->id,
                'data'    => '{"data":{"description":null,"test_category_field":'.($withData ? '"'.$data['test_category_field'].'"' : 'null').',"parsed":{"description":null,"test_category_field":'.($withData ? '"'.$data['test_category_field'].'"' : 'null').'}},"title":"'.$data['title'].'","is_visible":0,"summary":'.($withSummary ? '"'.$data['summary'].'"' : 'null').',"utility_tag":'.($withUtilityTag ? '["'.$data['utility_tag'][0].'"]' : 'null').',"page_tag":'.($withPageTag ? '["'.$data['page_tag'].'"]' : 'null').($withParent ? ',"parent_id":'.$parent->id : '').'}',
            ]);

            if ($withUtilityTag) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $data['utility_tag'][0],
                ]);
            }

            if ($withPageTag) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'page_tag',
                    'tag'     => $data['page_tag'],
                ]);
            }
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('pages', [
                'title' => $data['title'],
            ] + ($withCategory ? [
                'category_id' => $category->id,
            ] : []));
        }
    }

    public static function postCreatePageProvider() {
        return [
            'without category' => [0, 1, 0, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * Test page editing.
     *
     * @dataProvider postCreateEditPageProvider
     * @dataProvider postEditPageProvider
     *
     * @param bool $withPage
     * @param bool $withTitle
     * @param bool $withSummary
     * @param bool $withData
     * @param bool $withParent
     * @param bool $withUtilityTag
     * @param bool $withPageTag
     * @param bool $expected
     */
    public function testPostEditPage($withPage, $withTitle, $withSummary, $withData, $withParent, $withUtilityTag, $withPageTag, $expected) {
        $category = SubjectCategory::factory()->testData()->create();

        if ($withPage) {
            $page = Page::factory()->category($category->id)->create();
        }

        if ($withParent) {
            $parent = Page::factory()->create();
            PageVersion::factory()->page($parent->id)->user($this->editor->id)->create();
        }

        $data = [
            'title'               => $withTitle ? $this->faker->unique()->domainWord().$this->faker->unique()->domainWord() : null,
            'summary'             => $withSummary ? $this->faker->unique()->domainWord() : null,
            'parent_id'           => $withParent ? $parent->id : null,
            'test_category_field' => $withData ? $this->faker->unique()->domainWord() : null,
            'utility_tag'         => $withUtilityTag ? [0 => 'wip'] : null,
            'page_tag'            => $withPageTag ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.($withPage ? $page->id : 9999).'/edit', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('pages', [
                'id'        => $page->id,
                'title'     => $data['title'],
                'parent_id' => $withParent ? $parent->id : null,
            ]);

            $this->assertDatabaseHas('page_versions', [
                'page_id' => $page->id,
                'data'    => '{"data":{"description":null,"test_category_field":'.($withData ? '"'.$data['test_category_field'].'"' : 'null').',"parsed":{"description":null,"test_category_field":'.($withData ? '"'.$data['test_category_field'].'"' : 'null').'}},"title":"'.$data['title'].'","is_visible":0,"summary":'.($withSummary ? '"'.$data['summary'].'"' : 'null').',"utility_tag":'.($withUtilityTag ? '["'.$data['utility_tag'][0].'"]' : 'null').',"page_tag":'.($withPageTag ? '["'.$data['page_tag'].'"]' : 'null').($withParent ? ',"parent_id":'.$parent->id : '').'}',
            ]);

            if ($withUtilityTag) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $data['utility_tag'][0],
                ]);
            }

            if ($withPageTag) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'page_tag',
                    'tag'     => $data['page_tag'],
                ]);
            }
        } else {
            if ($withPage) {
                $response->assertSessionHasErrors();
                $this->assertDatabaseMissing('pages', [
                    'id'    => $page->id,
                    'title' => $data['title'],
                ]);
            } else {
                $response->assertStatus(404);
            }
        }
    }

    public static function postCreateEditPageProvider() {
        return [
            'basic'                     => [1, 1, 0, 0, 0, 0, 0, 1],
            'with summary'              => [1, 1, 1, 0, 0, 0, 0, 1],
            'with data'                 => [1, 1, 0, 1, 0, 0, 0, 1],
            'with parent'               => [1, 1, 0, 0, 1, 0, 0, 1],
            'with utility tag'          => [1, 1, 0, 0, 0, 1, 0, 1],
            'with page tag'             => [1, 1, 0, 0, 0, 0, 1, 1],
            'with summary, data'        => [1, 1, 1, 1, 0, 0, 0, 1],
            'with summary, parent'      => [1, 1, 1, 0, 1, 0, 0, 1],
            'with summary, utility tag' => [1, 1, 1, 0, 0, 1, 0, 1],
            'with summary, page tag'    => [1, 1, 1, 0, 0, 0, 1, 1],
            'with data, parent'         => [1, 1, 0, 1, 1, 0, 0, 1],
            'with data, utility tag'    => [1, 1, 0, 1, 0, 1, 0, 1],
            'with data, page tag'       => [1, 1, 0, 1, 0, 0, 1, 1],
            'with parent, utility tag'  => [1, 1, 0, 0, 1, 1, 0, 1],
            'with parent, page tag'     => [1, 1, 0, 0, 1, 0, 1, 1],
            'with utility, page tag'    => [1, 1, 0, 0, 0, 1, 1, 1],
            'with all'                  => [1, 1, 1, 1, 1, 1, 1, 1],
            'without title'             => [1, 0, 0, 0, 0, 0, 0, 0],
        ];
    }

    public static function postEditPageProvider() {
        return [
            'without page' => [0, 1, 0, 0, 0, 0, 0, 0],
        ];
    }
}
