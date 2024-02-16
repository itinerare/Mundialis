<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\User\User;
use App\Models\User\WatchedPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageWatchTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        $this->page = Page::factory()->create();
        $this->editor = User::factory()->editor()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();

        $this->user = User::factory()->create();
    }

    /**
     * Test watched pages access.
     *
     * @dataProvider getWatchedPagesProvider
     *
     * @param bool $withPage
     */
    public function testGetWatchedPages($withPage) {
        if ($withPage) {
            WatchedPage::factory()->user($this->user->id)->page($this->page->id)->create();
        }

        $response = $this->actingAs($this->user)
            ->get('/account/watched-pages')
            ->assertStatus(200);

        if ($withPage) {
            $response->assertSee($this->page->title);
        }
    }

    public static function getWatchedPagesProvider() {
        return [
            'with page'    => [1],
            'without page' => [0],
        ];
    }

    /**
     * Test watching a page.
     *
     * @dataProvider postWatchPageProvider
     *
     * @param bool $withWatch
     * @param bool $withPage
     */
    public function testPostWatchPage($withWatch, $withPage) {
        if ($withWatch) {
            WatchedPage::factory()->user($this->user->id)->page($this->page->id)->create();
        }

        $response = $this->actingAs($this->user)
            ->post('/account/watched-pages/'.($withPage ? $this->page->id : mt_rand(500, 1000)));

        if ($withPage) {
            $response->assertSessionHasNoErrors();
        } else {
            $response->assertSessionHasErrors();
        }

        if ($withPage && !$withWatch) {
            $this->assertDatabaseHas('watched_pages', [
                'user_id' => $this->user->id,
                'page_id' => $this->page->id,
            ]);
        } else {
            $this->assertDatabaseMissing('watched_pages', [
                'user_id' => $this->user->id,
                'page_id' => $this->page->id,
            ]);
        }
    }

    public static function postWatchPageProvider() {
        return [
            'watch page'   => [0, 1],
            'unwatch page' => [1, 1],
            'without page' => [0, 0],
        ];
    }
}
