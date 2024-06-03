<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageViewTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test page access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param bool   $asEditor
     * @param bool   $isVisible
     * @param int    $status
     */
    public function testGetPage($subject, $withPage, $asEditor, $isVisible, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('/pages/'.($withPage ? $page->id.'.'.$page->slug : '9999.'.$this->faker->unique()->domainWord()));

        $response->assertStatus($status);
    }

    public static function getPageProvider() {
        return [
            'person page'                      => ['people', 1, 0, 1, 200],
            'person page, hidden'              => ['people', 1, 0, 0, 404],
            'person page, hidden, as editor'   => ['people', 1, 1, 0, 200],
            'place page'                       => ['places', 1, 0, 1, 200],
            'place page, hidden'               => ['places', 1, 0, 0, 404],
            'place page, hidden, as editor'    => ['places', 1, 1, 0, 200],
            'species page'                     => ['species', 1, 0, 1, 200],
            'species page, hidden'             => ['species', 1, 0, 0, 404],
            'species page, hidden, as editor'  => ['species', 1, 1, 0, 200],
            'thing page'                       => ['things', 1, 0, 1, 200],
            'thing page, hidden'               => ['things', 1, 0, 0, 404],
            'thing page, hidden, as editor'    => ['things', 1, 1, 0, 200],
            'concept page'                     => ['concepts', 1, 0, 1, 200],
            'concept page, hidden'             => ['concepts', 1, 0, 0, 404],
            'concept page, hidden, as editor'  => ['concepts', 1, 1, 0, 200],
            'event page'                       => ['time', 1, 0, 1, 200],
            'event page, hidden'               => ['time', 1, 0, 0, 404],
            'event page, hidden, as editor'    => ['time', 1, 1, 0, 200],
            'language page'                    => ['language', 1, 0, 1, 200],
            'language page, hidden'            => ['language', 1, 0, 0, 404],
            'language page, hidden, as editor' => ['language', 1, 1, 0, 200],
            'misc with page'                   => ['misc', 1, 0, 1, 200],
            'misc page, hidden'                => ['misc', 1, 0, 0, 404],
            'misc page, hidden, as editor'     => ['misc', 1, 1, 0, 200],
            'misc without page'                => ['misc', 0, 0, 1, 404],
        ];
    }

    /**
     * Test page history access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param bool   $asEditor
     * @param bool   $isVisible
     * @param int    $status
     */
    public function testGetPageHistory($subject, $withPage, $asEditor, $isVisible, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/history');

        $response->assertStatus($status);
    }

    /**
     * Test page gallery access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param bool   $asEditor
     * @param bool   $isVisible
     * @param int    $status
     */
    public function testGetPageGallery($subject, $withPage, $asEditor, $isVisible, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/gallery');

        $response->assertStatus($status);
    }

    /**
     * Test page "what links here" access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param bool   $asEditor
     * @param bool   $isVisible
     * @param int    $status
     */
    public function testCanGetPageLinks($subject, $withPage, $asEditor, $isVisible, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($asEditor ? $this->editor : $this->user)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/links-here');

        $response->assertStatus($status);
    }
}
