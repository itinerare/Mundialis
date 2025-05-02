<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageTag;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageEditTagTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test page creation with utility tags.
     *
     * @param bool $wip
     * @param bool $stub
     * @param bool $outdated
     * @param bool $cleanup
     */
    #[DataProvider('postUtilityTagProvider')]
    public function testPostCreatePageWithUtilityTags($wip, $stub, $outdated, $cleanup) {
        $category = SubjectCategory::factory()->create();

        // Set up an array so that it's easy to loop through the tags
        // and access the relevant bool for each
        $tagList = [
            'wip'      => $wip,
            'stub'     => $stub,
            'outdated' => $outdated,
            'cleanup'  => $cleanup,
        ];

        $utilityTags = [];
        foreach ($tagList as $utilityTag => $bool) {
            if ($bool) {
                $utilityTags[] = $utilityTag;
            }
        }

        $data = [
            'title'       => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
            'utility_tag' => count($utilityTags) ? $utilityTags : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/create', $data);

        $response->assertSessionHasNoErrors();

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        foreach ($tagList as $utilityTag => $bool) {
            if ($bool) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $utilityTag,
                ]);
            } else {
                $this->assertDatabaseMissing('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $utilityTag,
                ]);
            }
        }

        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"parsed":{"description":null}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":'.(count($utilityTags) ? json_encode($utilityTags) : 'null').',"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with utility tags.
     *
     * @param bool $wip
     * @param bool $stub
     * @param bool $outdated
     * @param bool $cleanup
     * @param bool $withTags
     */
    #[DataProvider('postUtilityTagProvider')]
    #[DataProvider('postEditUtilityTagProvider')]
    public function testPostEditPageWithUtilityTags($wip, $stub, $outdated, $cleanup, $withTags) {
        // Set up an array so that it's easy to loop through the tags
        // and access the relevant bool for each
        $tagList = [
            'wip'      => $wip,
            'stub'     => $stub,
            'outdated' => $outdated,
            'cleanup'  => $cleanup,
        ];

        $utilityTags = [];
        if ($withTags) {
            $allTags = [];
        }
        foreach ($tagList as $utilityTag => $bool) {
            if ($bool) {
                $utilityTags[] = $utilityTag;
            }
            if ($withTags) {
                $allTags[] = $utilityTag;
            }
        }

        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($this->editor->id)->create($withTags ? [
            'data'    => '{"data":{"description":null,"parsed":{"description":null}},"title":"'.$page->title.'","is_visible":0,"summary":null,"utility_tag":'.(count($utilityTags) ? json_encode($allTags) : 'null').',"page_tag":null}',
        ] : []);
        if ($withTags) {
            foreach ($allTags as $tag) {
                PageTag::factory()->page($page->id)->create([
                    'type' => 'utility',
                    'tag'  => $tag,
                ]);
            }
        }

        $data = [
            'title'       => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
            'summary'     => null,
            'utility_tag' => count($utilityTags) ? $utilityTags : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page->id.'/edit', $data);

        $response->assertSessionHasNoErrors();
        foreach ($tagList as $utilityTag => $bool) {
            if ($bool) {
                $this->assertDatabaseHas('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $utilityTag,
                ]);
            } else {
                $this->assertDatabaseMissing('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'utility',
                    'tag'     => $utilityTag,
                ]);
            }
        }

        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"parsed":{"description":null}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":'.(count($utilityTags) ? json_encode($utilityTags) : 'null').',"page_tag":null}',
        ]);
    }

    public static function postUtilityTagProvider() {
        return [
            'wip'               => [1, 0, 0, 0, 0],
            'stub'              => [0, 1, 0, 0, 0],
            'outdated'          => [0, 0, 1, 0, 0],
            'cleanup'           => [0, 0, 0, 1, 0],
            'wip, stub'         => [1, 1, 0, 0, 0],
            'wip, outdated'     => [1, 0, 1, 0, 0],
            'wip, cleanup'      => [1, 0, 0, 1, 0],
            'stub, outdated'    => [0, 1, 1, 0, 0],
            'stub, cleanup'     => [0, 1, 0, 1, 0],
            'outdated, cleanup' => [0, 0, 1, 1, 0],
            'all'               => [1, 1, 1, 1, 0],
        ];
    }

    public static function postEditUtilityTagProvider() {
        return [
            'wip with tags'               => [1, 0, 0, 0, 1],
            'stub with tags'              => [0, 1, 0, 0, 1],
            'outdated with tags'          => [0, 0, 1, 0, 1],
            'cleanup with tags'           => [0, 0, 0, 1, 1],
            'wip, stub with tags'         => [1, 1, 0, 0, 1],
            'wip, outdated with tags'     => [1, 0, 1, 0, 1],
            'wip, cleanup with tags'      => [1, 0, 0, 1, 1],
            'stub, outdated with tags'    => [0, 1, 1, 0, 1],
            'stub, cleanup with tags'     => [0, 1, 0, 1, 1],
            'outdated, cleanup with tags' => [0, 0, 1, 1, 1],
            'all with tags'               => [1, 1, 1, 1, 1],
            'none with tags'              => [0, 0, 0, 0, 1],
        ];
    }

    /**
     * Test page creation with page tags.
     *
     * @param int $quantity
     */
    #[DataProvider('postCreatePageTagsProvider')]
    public function testPostCreatePageWithPageTags($quantity) {
        $category = SubjectCategory::factory()->create();

        for ($i = 1; $i <= $quantity; $i++) {
            $tags[] = $this->faker->unique()->domainWord();
        }

        $data = [
            'title'       => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
            'summary'     => null,
            'category_id' => $category->id,
            'page_tag'    => count($tags) ? implode(',', $tags) : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        $response->assertSessionHasNoErrors();
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('page_tags', [
                'page_id' => $page->id,
                'type'    => 'page_tag',
                'tag'     => $tag,
            ]);
        }
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"parsed":{"description":null}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":'.(count($tags) ? json_encode($tags) : 'null').'}',
        ]);
    }

    public static function postCreatePageTagsProvider() {
        return [
            '1 tag'  => [1],
            '2 tags' => [2],
            '3 tags' => [3],
        ];
    }

    /**
     * Test page editing with page tags.
     *
     * @param int  $quantity
     * @param bool $withTags
     * @param bool $preserveExisting
     */
    #[DataProvider('postEditPageTagsProvider')]
    public function testPostEditPageWithPageTags($quantity, $withTags, $preserveExisting) {
        $page = Page::factory()->create();

        // Set up new tags; these will always be added
        $newTags = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $newTags[] = $this->faker->unique()->domainWord();
        }

        $oldTags = [];
        if ($withTags) {
            for ($i = 1; $i <= $quantity; $i++) {
                $oldTags[$i] = $this->faker->unique()->domainWord();
                PageTag::factory()->page($page->id)->create([
                    'type' => 'page_tag',
                    'tag'  => $oldTags[$i],
                ]);
            }
        }

        $tags = $newTags + ($preserveExisting ? $oldTags : []);

        $data = [
            'title'    => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
            'summary'  => null,
            'page_tag' => count($tags) ? implode(',', $tags) : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page->id.'/edit', $data);

        $response->assertSessionHasNoErrors();
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('page_tags', [
                'page_id' => $page->id,
                'type'    => 'page_tag',
                'tag'     => $tag,
            ]);
        }
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"parsed":{"description":null}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":'.(count($tags) ? json_encode($tags) : 'null').'}',
        ]);

        if ($withTags && !$preserveExisting) {
            foreach ($oldTags as $tag) {
                $this->assertDatabaseMissing('page_tags', [
                    'page_id' => $page->id,
                    'type'    => 'page_tag',
                    'tag'     => $tag,
                ]);
            }
        }
    }

    public static function postEditPageTagsProvider() {
        return [
            '1 tag'                   => [1, 0, 0],
            '2 tags'                  => [2, 0, 0],
            '3 tags'                  => [3, 0, 0],
            '1 tag, remove old tags'  => [1, 1, 0],
            '2 tags, remove old tags' => [2, 1, 0],
            '3 tags, remove old tags' => [3, 1, 0],
            '1 tag, preserve tags'    => [1, 1, 1],
            '2 tags, preserve tags'   => [2, 1, 1],
            '3 tags, preserve tags'   => [3, 1, 1],
        ];
    }
}
