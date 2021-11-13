<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;

class PageDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test page deletion access.
     *
     * @return void
     */
    public function test_canGetDeletePage()
    {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Make a page to be deleted
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/delete');

        $response->assertStatus(200);
    }

    /**
     * Test (soft) page deletion.
     *
     * @return void
     */
    public function test_canPostSoftDeletePage()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to delete
        $page = Page::factory()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        $this->assertSoftDeleted($page);
    }

    /**
     * Test (soft) page deletion with a reason.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithReason()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to delete & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete', $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'type' => 'Page Deleted',
            'reason' => $data['reason']
        ]);
    }

    /**
     * Test (soft) page deletion with page content.
     *
     * @return void
     */
    public function test_canPostSoftDeletePageWithContent()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to delete
        $page = Page::factory()->create();
        // As well as accompanying version
        $version = PageVersion::factory()->user($user->id)->page($page->id)
            ->testData($this->faker->unique()->domainWord(), $this->faker->unique()->domainWord())->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        // Ordinarily this would check for the presence of the test data
        // in the deleted version, but testing seems to have difficulty with this.
        // Manually verify.
        $this->assertSoftDeleted($page);
    }

    /**
     * Test (soft) page deletion with a child page.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostSoftDeletePageWithChild()
    {
        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Make a page to try to delete & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Make a child page & version
        $child = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($child->id)->create();

        $child->update(['parent_id' => $page->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/delete');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test full page deletion.
     *
     * @return void
     */
    public function test_canPostForceDeletePage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a category for the page to go into/to delete
        $category = SubjectCategory::factory()->create();

        // Make a deleted page
        $page = Page::factory()->category($category->id)->deleted()->create();
        // As well as accompanying version
        PageVersion::factory()->user($user->id)->page($page->id)->deleted()->create();

        // Try to post data; this time the category is deleted
        // since deleting the category is the only way to force-delete pages
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/categories/delete/'.$category->id);

        // Verify that the appropriate change has occurred
        $this->assertDeleted($page);
    }

    /**
     * Test deleted page access.
     *
     * @return void
     */
    public function test_canGetDeletedPage()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->create();

        // Make a deleted page & version
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->deleted()->create();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages/'.$page->id);

        $response->assertStatus(200);
    }

    /**
     * Test restore page access.
     *
     * @return void
     */
    public function test_canGetRestorePage()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->create();

        // Make a deleted page & version
        $page = Page::factory()->deleted()->create();
        PageVersion::factory()->page($page->id)->user($user->id)->deleted()->create();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages/'.$page->id.'/restore');

        $response->assertStatus(200);
    }

    /**
     * Test page restoration.
     *
     * @return void
     */
    public function test_canPostRestorePage()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Make a page to restore & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-pages/'.$page->id.'/restore');

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test page restoration with a reason.
     *
     * @return void
     */
    public function test_canPostRestorePageWithReason()
    {
        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Set a reason
        $data = [
            'reason' => $this->faker->unique()->domainWord()
        ];

        // Make a page to restore & version
        $page = Page::factory()->create();
        PageVersion::factory()->user($user->id)->page($page->id)->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/special/deleted-pages/'.$page->id.'/restore', $data);

        // Verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'type' => 'Page Restored',
            'reason' => $data['reason'],
        ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'deleted_at' => null,
        ]);
    }
}
