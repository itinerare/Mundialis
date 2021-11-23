<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Page\Page;
use App\Models\Page\PageVersion;

class PageViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test page access.
     *
     * @return void
     */
    public function test_canGetPage()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page history access.
     *
     * @return void
     */
    public function test_canGetPageHistory()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/history');

        $response->assertStatus(200);
    }

    /**
     * Test page gallery access.
     *
     * @return void
     */
    public function test_canGetPageGallery()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery');

        $response->assertStatus(200);
    }

    /**
     * Test page "what links here" access.
     *
     * @return void
     */
    public function test_canGetPageLinks()
    {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id);

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/links-here');

        $response->assertStatus(200);
    }
}
