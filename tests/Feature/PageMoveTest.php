<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User\User;
use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;

class PageMoveTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test page move access.
     *
     * @return void
     */
    public function test_canGetMovePage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();
        // Create a page to move
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/' . $page->id . '/move');

        $response->assertStatus(200);
    }

    /**
     * Test page moving.
     *
     * @return void
     */
    public function test_canPostMovePage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to move & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->create();

        // Make a category to move the page to
        $category = SubjectCategory::factory()->create();

        $data = [
            'category_id' => $category->id,
            'reason' => null,
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/move', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test page moving with a reason.
     *
     * @return void
     */
    public function test_canPostMovePageWithReason()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to move & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->create();

        // Make a category to move the page to
        $category = SubjectCategory::factory()->create();

        // Note the old category (though this won't update regardless)
        $oldCategory = $page->category;

        $data = [
            'category_id' => $category->id,
            'reason' => $this->faker->unique()->domainWord(),
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/' . $page->id . '/move', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'type' => 'Page Moved from ' . $oldCategory->name . ' to ' . $category->name,
            'reason' => $data['reason'],
        ]);
    }
}
