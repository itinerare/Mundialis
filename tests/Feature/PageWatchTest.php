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

    /**
     * Test watched pages access.
     */
    public function testCanGetWatchedPages() {
        // Make a temporary user
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/account/watched-pages')
            ->assertStatus(200);
    }

    /**
     * Test watched pages access with a watched page.
     */
    public function testCanGetWatchedPagesWithPage() {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        // Create a page watch record
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        $response = $this->actingAs($user)
            ->get('/account/watched-pages')
            ->assertStatus(200);
    }

    /**
     * Test watching a page.
     */
    public function testCanPostWatchPage() {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->post('/account/watched-pages/'.$page->id);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('watched_pages', [
            'user_id' => $user->id,
            'page_id' => $page->id,
        ]);
    }

    /**
     * Test unwatching a page.
     */
    public function testCanPostUnwatchPage() {
        // Make a persistent user
        $user = User::factory()->create();

        // Create a page to watch & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        // Create a page watch record to remove
        WatchedPage::factory()->user($user->id)->page($page->id)->create();

        $response = $this->actingAs($user)
            ->post('/account/watched-pages/'.$page->id);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseMissing('watched_pages', [
            'user_id' => $user->id,
            'page_id' => $page->id,
        ]);
    }
}
