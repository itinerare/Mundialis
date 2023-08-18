<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Page\Page;
use App\Models\Page\PageTag;
use App\Models\Page\PageVersion;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectViewTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        SUBJECT / VIEW
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test subject page access.
     *
     * @dataProvider getSubjectProvider
     *
     * @param string $subject
     * @param bool   $withCategory
     * @param bool   $withUnrelated
     * @param bool   $withExtra
     */
    public function testGetViewSubject($subject, $withCategory, $withUnrelated, $withExtra) {
        if ($withCategory) {
            $category = SubjectCategory::factory()->subject($subject)->create();
        }
        if ($withUnrelated) {
            $unrelated = SubjectCategory::factory()->subject($subject != 'misc' ? 'misc' : 'people')->create();
        }
        if ($withExtra) {
            switch ($subject) {
                case 'time':
                    $chronology = TimeChronology::factory()->create();
                    break;
                case 'language':
                    $lexiconCategory = LexiconCategory::factory()->create();
                    break;
            }
        }

        $response = $this->actingAs($this->user)
            ->get('/'.$subject);

        $response->assertStatus(200);
        if ($withCategory) {
            $response->assertSee($category->name);
        }
        if ($withUnrelated) {
            $response->assertDontSee($unrelated->name);
        }
        if ($withExtra) {
            switch ($subject) {
                case 'time':
                    $response->assertSee($chronology->name);
                    break;
                case 'language':
                    $response->assertSee($lexiconCategory->name);
                    break;
            }
        }
    }

    public function getSubjectProvider() {
        return [
            'people'                                   => ['people', 0, 0, 0],
            'people with category'                     => ['people', 1, 0, 0],
            'people with unrelated'                    => ['people', 0, 1, 0],
            'places'                                   => ['places', 0, 0, 0],
            'places with category'                     => ['places', 1, 0, 0],
            'places with unrelated'                    => ['places', 0, 1, 0],
            'species'                                  => ['species', 0, 0, 0],
            'species with category'                    => ['species', 1, 0, 0],
            'species with unrelated'                   => ['species', 0, 1, 0],
            'things'                                   => ['things', 0, 0, 0],
            'things with category'                     => ['things', 1, 0, 0],
            'things with unrelated'                    => ['things', 0, 1, 0],
            'concepts'                                 => ['concepts', 0, 0, 0],
            'concepts with category'                   => ['concepts', 1, 0, 0],
            'concepts with unrelated'                  => ['concepts', 0, 1, 0],
            'time'                                     => ['time', 0, 0, 0],
            'time with category'                       => ['time', 1, 0, 0],
            'time with unrelated'                      => ['time', 0, 1, 0],
            'time with chronology'                     => ['time', 0, 0, 1],
            'time with category, chronology'           => ['time', 1, 0, 1],
            'language'                                 => ['language', 0, 0, 0],
            'language with category'                   => ['language', 1, 0, 0],
            'language with unrelated'                  => ['language', 0, 1, 0],
            'language with lexicon category'           => ['language', 0, 0, 1],
            'language with category, lexicon category' => ['language', 1, 0, 1],
            'misc'                                     => ['misc', 0, 0, 0],
            'misc with category'                       => ['misc', 1, 0, 0],
            'misc with unrelated'                      => ['misc', 0, 1, 0],
        ];
    }

    /**
     * Test subject category access.
     *
     * @dataProvider getCategoryProvider
     * @dataProvider categorySearchProvider
     *
     * @param bool       $asEditor
     * @param bool       $withChild
     * @param array|null $pageData
     * @param array|null $searchData
     */
    public function testGetSubjectCategory($asEditor, $withChild, $pageData, $searchData) {
        $category = SubjectCategory::factory()->create();

        if ($withChild) {
            $child = SubjectCategory::factory()->create(['parent_id' => $category->id]);
        }

        if ($pageData && $pageData[0]) {
            $page = Page::factory()->category($category->id)->create(['is_visible' => $pageData[1]]);
            PageVersion::factory()->page($page->id)->user($this->editor)->create();
        }

        $url = '/misc/categories/'.$category->id;

        // Set up urls for different search criteria / intended success
        if ($searchData) {
            $url = $url.'?'.$searchData[0].'=';
            switch ($searchData[0]) {
                case 'title':
                    $url = $url.($searchData[1] ? $page->name : $this->faker->unique()->domainWord());
                    break;
                case 'category_id':
                    $url = $url.($searchData[1] ? $page->category_id : SubjectCategory::factory()->subject('time')->create()->id);
                    break;
                case 'tags%5B%5D':
                    $tag = PageTag::factory()->page($page->id)->create();
                    // The selectize input doesn't allow non-existent tags,
                    // but they are nonetheless "valid" input,
                    // since page tags are just arbitrary user-specified strings
                    $url = $url.($searchData[1] ? $tag->tag : $this->faker->unique()->domainWord());
            }
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get($url);

        $response->assertStatus(200);
        if ($withChild) {
            $response->assertSee($child->name);
        }
        if ($pageData && $pageData[0]) {
            $response->assertViewHas('pages', function ($pages) use ($asEditor, $pageData, $searchData, $page) {
                if (($asEditor || $pageData[1]) && (!$searchData || $searchData[1])) {
                    return $pages->contains($page);
                } else {
                    return !$pages->contains($page);
                }
            });
        }
    }

    public function getCategoryProvider() {
        // $pageData = [$withPage, $isVisible]

        return [
            'as user'                               => [0, 0, null, null],
            'as editor'                             => [1, 0, null, null],
            'with child (user)'                     => [0, 1, null, null],
            'with child (editor)'                   => [1, 1, null, null],
            'with page/entry (user)'                => [0, 0, [1, 1], null],
            'with page/entry (editor)'              => [1, 0, [1, 1], null],
            'with hidden page/entry (user)'         => [0, 0, [1, 0], null],
            'with hidden page/entry (editor)'       => [1, 0, [1, 0], null],
            'with both (user)'                      => [0, 1, [1, 1], null],
            'with both (editor)'                    => [1, 1, [1, 1], null],
            'with both, page/entry hidden (user)'   => [0, 1, [1, 0], null],
            'with both, page/entry hidden (editor)' => [1, 1, [1, 0], null],
        ];
    }

    public function categorySearchProvider() {
        // $searchData = [$searchType, $expected]

        return [
            'search by title (successful) (user)'     => [0, 0, [1, 1], ['title', 1]],
            'search by title (successful) (editor)'   => [1, 0, [1, 1], ['title', 1]],
            'search by title (unsuccessful) (user)'   => [0, 0, [1, 1], ['title', 0]],
            'search by title (unsuccessful) (editor)' => [1, 0, [1, 1], ['title', 0]],
            'search by tag (successful) (user)'       => [0, 0, [1, 1], ['tags%5B%5D', 1]],
            'search by tag (successful) (editor)'     => [1, 0, [1, 1], ['tags%5B%5D', 1]],
            'search by tag (unsuccessful) (user)'     => [0, 0, [1, 1], ['tags%5B%5D', 0]],
            'search by tag (unsuccessful) (editor)'   => [1, 0, [1, 1], ['tags%5B%5D', 0]],
        ];
    }

    /**
     * Test time chronology access.
     *
     * @dataProvider getCategoryProvider
     * @dataProvider categorySearchProvider
     * @dataProvider chronologySearchProvider
     *
     * @param bool       $asEditor
     * @param bool       $withChild
     * @param array|null $pageData
     * @param array|null $searchData
     */
    public function testGetTimeChronology($asEditor, $withChild, $pageData, $searchData) {
        $chronology = TimeChronology::factory()->create();

        if ($withChild) {
            $child = TimeChronology::factory()->create(['parent_id' => $chronology->id]);
        }

        if ($pageData && $pageData[0]) {
            // Create a category in the correct subject
            // This is necessary for the page to be perceived as in the chronology
            $category = SubjectCategory::factory()->subject('time')->create();

            // For "time" pages, chronology is the "parent"
            $page = Page::factory()->category($category->id)->create([
                'parent_id'  => $chronology,
                'is_visible' => $pageData[1],
            ]);
            PageVersion::factory()->page($page->id)->user($this->editor)->create();
        }

        $url = '/time/chronologies/'.$chronology->id;

        // Set up urls for different search criteria / intended success
        if ($searchData) {
            $url = $url.'?'.$searchData[0].'=';
            switch ($searchData[0]) {
                case 'title':
                    $url = $url.($searchData[1] ? $page->name : $this->faker->unique()->domainWord());
                    break;
                case 'category_id':
                    $url = $url.($searchData[1] ? $page->category_id : SubjectCategory::factory()->subject('time')->create()->id);
                    break;
                case 'tags%5B%5D':
                    $tag = PageTag::factory()->page($page->id)->create();
                    // The selectize input doesn't allow non-existent tags,
                    // but they are nonetheless "valid" input,
                    // since page tags are just arbitrary user-specified strings
                    $url = $url.($searchData[1] ? $tag->tag : $this->faker->unique()->domainWord());
            }
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get($url);

        $response->assertStatus(200);
        if ($withChild) {
            $response->assertSee($child->name);
        }
        if ($pageData && $pageData[0]) {
            $response->assertViewHas('pages', function ($pages) use ($asEditor, $pageData, $searchData, $page) {
                if (($asEditor || $pageData[1]) && (!$searchData || $searchData[1])) {
                    return $pages->contains($page);
                } else {
                    return !$pages->contains($page);
                }
            });
        }
    }

    public function chronologySearchProvider() {
        // $searchData = [$searchType, $expected]

        return [
            'search by category (successful) (user)'     => [0, 0, [1, 1], ['category_id', 1]],
            'search by category (successful) (editor)'   => [1, 0, [1, 1], ['category_id', 1]],
            'search by category (unsuccessful) (user)'   => [0, 0, [1, 1], ['category_id', 0]],
            'search by category (unsuccessful) (editor)' => [1, 0, [1, 1], ['category_id', 0]],
        ];
    }

    /**
     * Test lexicon entry display and search on the language page itself.
     *
     * @dataProvider getCategoryProvider
     * @dataProvider lexiconSearchProvider
     *
     * @param bool       $asEditor
     * @param bool       $withChild
     * @param array|null $entryData
     * @param array|null $searchData
     */
    public function testGetLanguageLexicon($asEditor, $withChild, $entryData, $searchData) {
        if ($entryData && $entryData[0]) {
            $entry = LexiconEntry::factory()->create(['is_visible' => $entryData[1]]);
        }

        if ($withChild) {
            $child = LexiconCategory::factory()->create();
        }

        $url = '/language';

        // Set up urls for different search criteria / intended success
        if ($searchData) {
            $url = $url.'?'.$searchData[0].'=';
            switch ($searchData[0]) {
                case 'word':
                    $url = $url.($searchData[1] ? $entry->word : $this->faker->unique()->domainWord());
                    break;
                case 'meaning':
                    $url = $url.($searchData[1] ? $entry->meaning : $this->faker->unique()->domainWord());
                    break;
                case 'pronunciation':
                    $url = $url.($searchData[1] ? $entry->pronunciation : $this->faker->unique()->domainWord());
                    break;
                case 'category_id':
                    $url = $url.($searchData[1] ? $entry->class : SubjectCategory::factory()->subject('time')->create()->id);
                    break;
            }
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get($url);

        $response->assertStatus(200);
        if ($withChild) {
            $response->assertSee($child->name);
        }
        if ($entryData && $entryData[0]) {
            $response->assertViewHas('entries', function ($entries) use ($asEditor, $entryData, $searchData, $entry) {
                if (($asEditor || $entryData[1]) && (!$searchData || $searchData[1])) {
                    return $entries->contains($entry);
                } else {
                    return !$entries->contains($entry);
                }
            });
        }
    }

    /**
     * Test lexicon category access.
     *
     * @dataProvider getCategoryProvider
     * @dataProvider lexiconSearchProvider
     *
     * @param bool       $asEditor
     * @param bool       $withChild
     * @param array|null $entryData
     * @param array|null $searchData
     */
    public function testGetLexiconCategory($asEditor, $withChild, $entryData, $searchData) {
        $category = LexiconCategory::factory()->create();

        if ($entryData && $entryData[0]) {
            $entry = LexiconEntry::factory()->category($category->id)->create([
                'is_visible' => $entryData[1],
            ]);
        }

        if ($withChild) {
            $child = LexiconCategory::factory()->create(['parent_id' => $category->id]);
        }

        $url = '/language/lexicon/'.$category->id;

        // Set up urls for different search criteria / intended success
        if ($searchData) {
            $url = $url.'?'.$searchData[0].'=';
            switch ($searchData[0]) {
                case 'word':
                    $url = $url.($searchData[1] ? $entry->word : $this->faker->unique()->domainWord());
                    break;
                case 'meaning':
                    $url = $url.($searchData[1] ? $entry->meaning : $this->faker->unique()->domainWord());
                    break;
                case 'pronunciation':
                    $url = $url.($searchData[1] ? $entry->pronunciation : $this->faker->unique()->domainWord());
                    break;
                case 'category_id':
                    $url = $url.($searchData[1] ? $entry->class : SubjectCategory::factory()->subject('time')->create()->id);
                    break;
            }
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get($url);

        $response->assertStatus(200);
        if ($withChild) {
            $response->assertSee($child->name);
        }
        if ($entryData && $entryData[0]) {
            $response->assertViewHas('entries', function ($entries) use ($asEditor, $entryData, $searchData, $entry) {
                if (($asEditor || $entryData[1]) && (!$searchData || $searchData[1])) {
                    return $entries->contains($entry);
                } else {
                    return !$entries->contains($entry);
                }
            });
        }
    }

    public function lexiconSearchProvider() {
        // $searchData = [$searchType, $expected]

        return [
            'search by word (successful) (user)'              => [0, 0, [1, 1], ['word', 1]],
            'search by word (successful) (editor)'            => [1, 0, [1, 1], ['word', 1]],
            'search by word (unsuccessful) (user)'            => [0, 0, [1, 1], ['word', 0]],
            'search by word (unsuccessful) (editor)'          => [1, 0, [1, 1], ['word', 0]],
            'search by word (successful) (user)'              => [0, 0, [1, 1], ['word', 1]],
            'search by meaning (successful) (user)'           => [0, 0, [1, 1], ['meaning', 1]],
            'search by meaning (successful) (editor)'         => [1, 0, [1, 1], ['meaning', 1]],
            'search by meaning (unsuccessful) (user)'         => [0, 0, [1, 1], ['meaning', 0]],
            'search by meaning (unsuccessful) (editor)'       => [1, 0, [1, 1], ['meaning', 0]],
            'search by meaning (successful) (user)'           => [0, 0, [1, 1], ['meaning', 1]],
            'search by pronunciation (successful) (user)'     => [0, 0, [1, 1], ['pronunciation', 1]],
            'search by pronunciation (successful) (editor)'   => [1, 0, [1, 1], ['pronunciation', 1]],
            'search by pronunciation (unsuccessful) (user)'   => [0, 0, [1, 1], ['pronunciation', 0]],
            'search by pronunciation (unsuccessful) (editor)' => [1, 0, [1, 1], ['pronunciation', 0]],
            'search by pronunciation (successful) (user)'     => [0, 0, [1, 1], ['pronunciation', 1]],
            'search by lexical class (successful) (editor)'   => [1, 0, [1, 1], ['category_id', 1]],
            'search by lexical class (unsuccessful) (user)'   => [0, 0, [1, 1], ['category_id', 0]],
            'search by lexical class (unsuccessful) (editor)' => [1, 0, [1, 1], ['category_id', 0]],
        ];
    }
}
