<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;
use App\Models\Subject\LexiconSetting;

class PageLinkTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test page creation with a wiki-style link to a page.
     *
     * @return void
     */
    public function test_canPostCreatePageWithPageLink()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Create a page to link to
        $linkPage = Page::factory()->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            'description' => '<p>[['.$linkPage->title.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkPage->title.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/pages\/'.$linkPage->id.'.'.$linkPage->slug.'\" class=\"text-primary\">'.$linkPage->title.'<\/a><\/p>"},"links":[{"link_id":'.$linkPage->id.'}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => $linkPage->id,
        ]);
    }

    /**
     * Test page editing with a wiki-style link to a page.
     *
     * @return void
     */
    public function test_canPostEditPageWithPageLink()
    {
        $page = Page::factory()->create();

        // Create a page to link to
        $linkPage = Page::factory()->create();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'description' => '<p>[['.$linkPage->title.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkPage->title.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/pages\/'.$linkPage->id.'.'.$linkPage->slug.'\" class=\"text-primary\">'.$linkPage->title.'<\/a><\/p>"},"links":[{"link_id":'.$linkPage->id.'}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => $linkPage->id,
        ]);
    }

    /**
     * Test page creation with a wiki-style link to a page with a label.
     *
     * @return void
     */
    public function test_canPostCreatePageWithLabeledPageLink()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Generate a page to link to and word for label
        $linkPage = Page::factory()->create();
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            'description' => '<p>[['.$linkPage->title.'|'.$linkWord.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkPage->title.'|'.$linkWord.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/pages\/'.$linkPage->id.'.'.$linkPage->slug.'\" class=\"text-primary\">'.$linkWord.'<\/a><\/p>"},"links":[{"link_id":'.$linkPage->id.'}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => $linkPage->id,
        ]);
    }

    /**
     * Test page editing with a wiki-style link to a page with a label.
     *
     * @return void
     */
    public function test_canPostEditPageWithLabeledPageLink()
    {
        $page = Page::factory()->create();

        // Generate a page to link to and word for label
        $linkPage = Page::factory()->create();
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'description' => '<p>[['.$linkPage->title.'|'.$linkWord.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkPage->title.'|'.$linkWord.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/pages\/'.$linkPage->id.'.'.$linkPage->slug.'\" class=\"text-primary\">'.$linkWord.'<\/a><\/p>"},"links":[{"link_id":'.$linkPage->id.'}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => $linkPage->id,
        ]);
    }

    /**
     * Test page creation with a wiki-style link to a wanted page.
     *
     * @return void
     */
    public function test_canPostCreatePageWithWantedLink()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Generate a word to use
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            'description' => '<p>[['.$linkWord.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkWord.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/special\/create-wanted\/'.$linkWord.'\" class=\"text-danger\">'.$linkWord.'<\/a><\/p>"},"links":[{"title":"'.$linkWord.'"}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => null,
            'title' => $linkWord,
        ]);
    }

    /**
     * Test page editing with a wiki-style link to a wanted page.
     *
     * @return void
     */
    public function test_canPostEditPageWithWantedLink()
    {
        $page = Page::factory()->create();

        // Generate a word to use
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'description' => '<p>[['.$linkWord.']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkWord.']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/special\/create-wanted\/'.$linkWord.'\" class=\"text-danger\">'.$linkWord.'<\/a><\/p>"},"links":[{"title":"'.$linkWord.'"}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => null,
            'title' => $linkWord,
        ]);
    }

    /**
     * Test page creation with a wiki-style link to a wanted page with a label.
     *
     * @return void
     */
    public function test_canPostCreatePageWithLabeledWantedLink()
    {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->create();

        // Generate some words to use
        for ($i = 1; $i <= 2; $i++) {
            $linkWord[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'category_id' => $category->id,
            'description' => '<p>[['.$linkWord[1].'|'.$linkWord[2].']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkWord[1].'|'.$linkWord[2].']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/special\/create-wanted\/'.$linkWord[1].'\" class=\"text-danger\">'.$linkWord[2].'<\/a><\/p>"},"links":[{"title":"'.$linkWord[1].'"}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => null,
            'title' => $linkWord,
        ]);
    }

    /**
     * Test page editing with a wiki-style link to a wanted page with a label.
     *
     * @return void
     */
    public function test_canPostEditPageWithLabeledWantedLink()
    {
        $page = Page::factory()->create();

        // Generate some words to use
        for ($i = 1; $i <= 2; $i++) {
            $linkWord[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic data
        $data = [
            'title' => $this->faker->unique()->domainWord(),
            'summary' => null,
            'description' => '<p>[['.$linkWord[1].'|'.$linkWord[2].']]</p>',
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data' => '{"data":{"description":"<p>[['.$linkWord[1].'|'.$linkWord[2].']]<\/p>","parsed":{"description":"<p><a href=\"http:\/\/'.preg_replace("(^https?://)", "", env('APP_URL', 'localhost')).'\/special\/create-wanted\/'.$linkWord[1].'\" class=\"text-danger\">'.$linkWord[2].'<\/a><\/p>"},"links":[{"title":"'.$linkWord[1].'"}]},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}'
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $page->id,
            'link_id' => null,
            'title' => $linkWord,
        ]);
    }

    /**
     * Test lexicon entry creation with a link to a page.
     *
     * @return void
     */
    public function test_canPostCreateLexiconEntryWithPageLink()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Create a page to link to
        $linkPage = Page::factory()->create();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $this->faker->unique()->domainWord(),
            'class' => $class->name,
            'definition' => '<p>[['.$linkPage->title.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        $entry = LexiconEntry::where('word', $data['word'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p>'.$linkPage->displayName.'</p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'link_id' => $linkPage->id,
            'parent_type' => 'entry',
        ]);
    }

    /**
     * Test lexicon entry editing with a link to a page.
     *
     * @return void
     */
    public function test_canPostEditLexiconEntryWithPageLink()
    {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create a page to link to
        $linkPage = Page::factory()->create();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $entry->meaning,
            'class' => $entry->class,
            'definition' => '<p>[['.$linkPage->title.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p>'.$linkPage->displayName.'</p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'link_id' => $linkPage->id,
            'parent_type' => 'entry',
        ]);
    }

    /**
     * Test lexicon entry creation with a labeled link to a page.
     *
     * @return void
     */
    public function test_canPostCreateLexiconEntryWithLabeledPageLink()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Generate a page to link to and word for label
        $linkPage = Page::factory()->create();
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $this->faker->unique()->domainWord(),
            'class' => $class->name,
            'definition' => '<p>[['.$linkPage->title.'|'.$linkWord.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        $entry = LexiconEntry::where('word', $data['word'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.$linkPage->url.'" class="text-primary">'.$linkWord.'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'link_id' => $linkPage->id,
            'parent_type' => 'entry',
        ]);
    }

    /**
     * Test lexicon entry editing with a labeled link to a page.
     *
     * @return void
     */
    public function test_canPostEditLexiconEntryWithLabeledPageLink()
    {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Generate a page to link to and word for label
        $linkPage = Page::factory()->create();
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $entry->meaning,
            'class' => $entry->class,
            'definition' => '<p>[['.$linkPage->title.'|'.$linkWord.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.$linkPage->url.'" class="text-primary">'.$linkWord.'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'link_id' => $linkPage->id,
            'parent_type' => 'entry',
        ]);
    }

    /**
     * Test lexicon entry creation with a link to a wanted page.
     *
     * @return void
     */
    public function test_canPostCreateLexiconEntryWithWantedLink()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Generate a word to use
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $this->faker->unique()->domainWord(),
            'class' => $class->name,
            'definition' => '<p>[['.$linkWord.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        $entry = LexiconEntry::where('word', $data['word'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.url('special/create-wanted/'.$linkWord).'" class="text-danger">'.$linkWord.'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'parent_type' => 'entry',
            'link_id' => null,
            'title' => $linkWord
        ]);
    }

    /**
     * Test lexicon entry editing with a link to a wanted page.
     *
     * @return void
     */
    public function test_canPostEditLexiconEntryWithWantedLink()
    {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Generate a word to use
        $linkWord = $this->faker->unique()->domainWord();

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $entry->meaning,
            'class' => $entry->class,
            'definition' => '<p>[['.$linkWord.']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.url('special/create-wanted/'.$linkWord).'" class="text-danger">'.$linkWord.'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'parent_type' => 'entry',
            'link_id' => null,
            'title' => $linkWord
        ]);
    }

    /**
     * Test lexicon entry creation with a labeled link to a wanted page.
     *
     * @return void
     */
    public function test_canPostCreateLexiconEntryWithLabeledWantedLink()
    {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Generate some words to use
        for ($i = 1; $i <= 2; $i++) {
            $linkWord[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $this->faker->unique()->domainWord(),
            'class' => $class->name,
            'definition' => '<p>[['.$linkWord[1].'|'.$linkWord[2].']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        $entry = LexiconEntry::where('word', $data['word'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.url('special/create-wanted/'.$linkWord[1]).'" class="text-danger">'.$linkWord[2].'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'parent_type' => 'entry',
            'link_id' => null,
            'title' => $linkWord[1]
        ]);
    }

    /**
     * Test lexicon entry editing with a labeled link to a wanted page.
     *
     * @return void
     */
    public function test_canPostEditLexiconEntryWithLabeledWantedLink()
    {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Generate some words to use
        for ($i = 1; $i <= 2; $i++) {
            $linkWord[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic data
        $data = [
            'word' => $this->faker->unique()->domainWord(),
            'meaning' => $entry->meaning,
            'class' => $entry->class,
            'definition' => '<p>[['.$linkWord[1].'|'.$linkWord[2].']]</p>',
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id' => $entry->id,
            'definition' => $data['definition'],
            'parsed_definition' => '<p><a href="'.url('special/create-wanted/'.$linkWord[1]).'" class="text-danger">'.$linkWord[2].'</a></p>',
        ]);

        $this->assertDatabaseHas('page_links', [
            'parent_id' => $entry->id,
            'parent_type' => 'entry',
            'link_id' => null,
            'title' => $linkWord[1]
        ]);
    }
}
