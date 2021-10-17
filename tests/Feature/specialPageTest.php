<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class specialPageTest extends TestCase
{
    /******************************************************************************
        MAINTENANCE REPORTS
    *******************************************************************************/

    /**
     * Tests all special pages access.
     *
     * @return void
     */
    public function test_canGetSpecialPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special');

        $response->assertStatus(200);
    }

    /**
     * Tests untagged pages access.
     *
     * @return void
     */
    public function test_canGetUntaggedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/untagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most tagged pages access.
     *
     * @return void
     */
    public function test_canGetMostTaggedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/tagged-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests least revised pages access.
     *
     * @return void
     */
    public function test_canGetLeastRevisedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/least-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most revised pages access.
     *
     * @return void
     */
    public function test_canGetMostRevisedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/most-revised-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests most linked pages access.
     *
     * @return void
     */
    public function test_canGetMostLinkedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/linked-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited pages access.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/recent-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests recently edited images access.
     *
     * @return void
     */
    public function test_canGetRecentlyEditedImages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/recent-images');

        $response->assertStatus(200);
    }

    /**
     * Tests wanted pages access.
     *
     * @return void
     */
    public function test_canGetWantedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/wanted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests protected pages access.
     *
     * @return void
     */
    public function test_canGetProtectedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/protected-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests WIP pages access.
     *
     * @return void
     */
    public function test_canGetWipPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/wip-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests stub pages access.
     *
     * @return void
     */
    public function test_canGetStubPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/stub-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests outdated pages access.
     *
     * @return void
     */
    public function test_canGetOutdatedPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/outdated-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests pages needing cleanup access.
     *
     * @return void
     */
    public function test_canGetCleanupPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests unwatched pages access.
     *
     * @return void
     */
    public function test_canGetUnwatchedPages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/special/cleanup-pages');

        $response->assertStatus(200);
    }

    /******************************************************************************
        LISTS OF PAGES
    *******************************************************************************/

    /**
     * Tests all pages access.
     *
     * @return void
     */
    public function test_canGetAllPages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests all tags access.
     *
     * @return void
     */
    public function test_canGetAllTags()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-tags');

        $response->assertStatus(200);
    }

    /**
     * Tests all images access.
     *
     * @return void
     */
    public function test_canGetAllImages()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/all-images');

        $response->assertStatus(200);
    }

    /**
     * Tests deleted pages access.
     *
     * @return void
     */
    public function test_canGetDeletedPages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-pages');

        $response->assertStatus(200);
    }

    /**
     * Tests deleted images access.
     *
     * @return void
     */
    public function test_canGetDeletedImages()
    {
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/special/deleted-images');

        $response->assertStatus(200);
    }

    /******************************************************************************
        USERS
    *******************************************************************************/

    /**
     * Tests user list access.
     *
     * @return void
     */
    public function test_canGetUserList()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/user-list');

        $response->assertStatus(200);
    }

    /******************************************************************************
        OTHER
    *******************************************************************************/

    /**
     * Tests random page access.
     *
     * @return void
     */
    public function test_canGetRandomPage()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/special/random-page');

        $response->assertStatus(302);
    }
}
