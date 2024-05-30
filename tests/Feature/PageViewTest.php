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

        $this->user = User::factory()->make();
    }

    /**
     * Test page access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param int    $status
     */
    public function testGetPage($subject, $withPage, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create();
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/pages/'.($withPage ? $page->id.'.'.$page->slug : '9999.'.$this->faker->unique()->domainWord()));

        $response->assertStatus($status);
    }

    public static function getPageProvider() {
        return [
            'person page'       => ['people', 1, 200],
            'place page'        => ['places', 1, 200],
            'species page'      => ['species', 1, 200],
            'thing page'        => ['things', 1, 200],
            'concept page'      => ['concepts', 1, 200],
            'event page'        => ['time', 1, 200],
            'language page'     => ['language', 1, 200],
            'misc with page'    => ['misc', 1, 200],
            'misc without page' => ['misc', 0, 404],
        ];
    }

    /**
     * Test page history access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param int    $status
     */
    public function testGetPageHistory($subject, $withPage, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create();
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/history');

        $response->assertStatus($status);
    }

    /**
     * Test page "what links here" access.
     *
     * @dataProvider getPageProvider
     *
     * @param string $subject
     * @param bool   $withPage
     * @param int    $status
     */
    public function testCanGetPageLinks($subject, $withPage, $status) {
        if ($withPage) {
            $category = SubjectCategory::factory()->subject($subject)->create();

            $page = Page::factory()->category($category->id)->create();
            PageVersion::factory()->page($page->id)
                ->user(User::factory()->editor()->create()->id)->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/pages/'.($withPage ? $page->id : 9999).'/links-here');

        $response->assertStatus($status);
    }
}
