<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\LexiconSetting;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageLinkTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        $this->page = Page::factory()->create();
        PageVersion::factory()->page($this->page->id)->user($this->editor->id)->create();

        $this->linkedPage = Page::factory()->create();
        PageVersion::factory()->page($this->linkedPage->id)->user($this->editor->id)->create();
    }

    /**
     * Test page creation with a wiki-style link.
     *
     * @param string $type
     * @param bool   $withLabel
     */
    #[DataProvider('postWithLinkProvider')]
    public function testPostCreatePageWithLink($type, $withLabel) {
        $category = SubjectCategory::factory()->create();

        for ($i = 1; $i <= 2; $i++) {
            $linkName[$i] = $this->faker->unique()->domainWord();
        }

        switch ($type) {
            case 'page':
                $target = [
                    'id'   => $this->linkedPage->id,
                    'name' => $this->linkedPage->title,
                    'url'  => $this->linkedPage->url,
                ];
                break;
            case 'wanted':
                $target = [
                    'name' => $linkName[1],
                    'url'  => url('/special/create-wanted/'.$linkName[1]),
                ];
                break;
        }

        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'category_id' => $category->id,
            'summary'     => null,
            'description' => '<p>[['.$target['name'].($withLabel ? '|'.$linkName[2] : '').']]</p>',
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":'.json_encode($data['description']).',"parsed":{"description":"<p><a href=\"'.str_replace('"', '', json_encode($target['url'])).'\" class=\"text-'.($type != 'wanted' ? 'primary' : 'danger').'\">'.($withLabel ? $linkName[2] : $target['name']).'<\/a><\/p>"},"links":['.(isset($target['id']) ? '{"link_id":'.$target['id'].'}' : '{"title":"'.$linkName[1].'"}').']},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
        $this->assertDatabaseHas('page_links', [
            'parent_id'   => $page->id,
            'linked_type' => 'page',
            'link_id'     => $target['id'] ?? null,
            'title'       => $type == 'wanted' ? $linkName[1] : null,
        ]);
    }

    public static function postWithLinkProvider() {
        return [
            'page'              => ['page', 0],
            'page with label'   => ['page', 1],
            'wanted'            => ['wanted', 0],
            'wanted with label' => ['wanted', 1],
        ];
    }

    /**
     * Test page editing with a wiki-style link.
     *
     * @param string $type
     * @param bool   $withLabel
     */
    #[DataProvider('postWithLinkProvider')]
    public function testPostEditPageWithLink($type, $withLabel) {
        for ($i = 1; $i <= 2; $i++) {
            $linkName[$i] = $this->faker->unique()->domainWord();
        }

        switch ($type) {
            case 'page':
                $target = [
                    'id'   => $this->linkedPage->id,
                    'name' => $this->linkedPage->title,
                    'url'  => $this->linkedPage->url,
                ];
                break;
            case 'wanted':
                $target = [
                    'name' => $linkName[1],
                    'url'  => url('/special/create-wanted/'.$linkName[1]),
                ];
                break;
        }

        $data = [
            'title'       => $this->faker->unique()->domainWord(),
            'summary'     => null,
            'description' => '<p>[['.$target['name'].($withLabel ? '|'.$linkName[2] : '').']]</p>',
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$this->page->id.'/edit', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $this->page->id,
            'data'    => '{"data":{"description":'.json_encode($data['description']).',"parsed":{"description":"<p><a href=\"'.str_replace('"', '', json_encode($target['url'])).'\" class=\"text-'.($type != 'wanted' ? 'primary' : 'danger').'\">'.($withLabel ? $linkName[2] : $target['name']).'<\/a><\/p>"},"links":['.(isset($target['id']) ? '{"link_id":'.$target['id'].'}' : '{"title":"'.$linkName[1].'"}').']},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
        $this->assertDatabaseHas('page_links', [
            'parent_id'   => $this->page->id,
            'linked_type' => 'page',
            'link_id'     => $target['id'] ?? null,
            'title'       => $type == 'wanted' ? $linkName[1] : null,
        ]);
    }

    /**
     * Test lexicon entry creation with a page link.
     *
     * @param string $type
     * @param bool   $withLabel
     */
    #[DataProvider('postWithLinkProvider')]
    public function testPostCreateLexiconEntryWithLink($type, $withLabel) {
        for ($i = 1; $i <= 2; $i++) {
            $linkName[$i] = $this->faker->unique()->domainWord();
        }

        switch ($type) {
            case 'page':
                $target = [
                    'id'   => $this->linkedPage->id,
                    'name' => $this->linkedPage->title,
                    'url'  => $this->linkedPage->url,
                ];
                break;
            case 'wanted':
                $target = [
                    'name' => $linkName[1],
                    'url'  => url('/special/create-wanted/'.$linkName[1]),
                ];
                break;
        }

        $this->artisan('app:add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        $data = [
            'word'       => $this->faker->unique()->domainWord(),
            'meaning'    => $this->faker->unique()->domainWord(),
            'class'      => $class->name,
            'definition' => '<p>[['.$target['name'].($withLabel ? '|'.$linkName[2] : '').']]</p>',
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/language/lexicon/create', $data);

        $entry = LexiconEntry::where('word', $data['word'])->first();

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lexicon_entries', [
            'id'                => $entry->id,
            'definition'        => $data['definition'],
            'parsed_definition' => '<p><a href="'.$target['url'].'" class="text-'.($type != 'wanted' ? 'primary' : 'danger').'">'.($withLabel ? $linkName[2] : $target['name']).'</a></p>',
        ]);
        $this->assertDatabaseHas('page_links', [
            'parent_id'   => $entry->id,
            'link_id'     => $target['id'] ?? null,
            'parent_type' => 'entry',
            'linked_type' => 'page',
            'title'       => $type == 'wanted' ? $linkName[1] : null,
        ]);
    }

    /**
     * Test lexicon entry editing with a page link.
     *
     * @param string $type
     * @param bool   $withLabel
     */
    #[DataProvider('postWithLinkProvider')]
    public function testPostEditLexiconEntryWithLink($type, $withLabel) {
        for ($i = 1; $i <= 2; $i++) {
            $linkName[$i] = $this->faker->unique()->domainWord();
        }

        switch ($type) {
            case 'page':
                $target = [
                    'id'   => $this->linkedPage->id,
                    'name' => $this->linkedPage->title,
                    'url'  => $this->linkedPage->url,
                ];
                break;
            case 'wanted':
                $target = [
                    'name' => $linkName[1],
                    'url'  => url('/special/create-wanted/'.$linkName[1]),
                ];
                break;
        }

        $entry = LexiconEntry::factory()->create();
        $data = [
            'word'       => $this->faker->unique()->domainWord(),
            'meaning'    => $entry->meaning,
            'class'      => $entry->class,
            'definition' => '<p>[['.$target['name'].($withLabel ? '|'.$linkName[2] : '').']]</p>',
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('lexicon_entries', [
            'id'                => $entry->id,
            'definition'        => $data['definition'],
            'parsed_definition' => '<p><a href="'.$target['url'].'" class="text-'.($type != 'wanted' ? 'primary' : 'danger').'">'.($withLabel ? $linkName[2] : $target['name']).'</a></p>',
        ]);
        $this->assertDatabaseHas('page_links', [
            'parent_id'   => $entry->id,
            'link_id'     => $target['id'] ?? null,
            'parent_type' => 'entry',
            'linked_type' => 'page',
            'title'       => $type == 'wanted' ? $linkName[1] : null,
        ]);
    }
}
