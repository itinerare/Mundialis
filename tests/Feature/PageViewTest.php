<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageViewTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        $this->markTestIncomplete();
    }

    /**
     * Test page access.
     */
    public function testCanGetPage() {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page history access.
     */
    public function testCanGetPageHistory() {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/history');

        $response->assertStatus(200);
    }

    /**
     * Test page gallery access.
     */
    public function testCanGetPageGallery() {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/gallery');

        $response->assertStatus(200);
    }

    /**
     * Test page "what links here" access.
     */
    public function testCanGetPageLinks() {
        // Create a temporary user
        $user = User::factory()->make();

        // Create a page to view & version
        $page = Page::factory()->create();
        PageVersion::factory()->page($page->id)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/links-here');

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "people" subject.
     */
    public function testCanGetPeoplePage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('people')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "places" subject.
     */
    public function testCanGetPlacesPage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('places')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "flora and fauna" subject.
     */
    public function testCanGetSpeciesPage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('species')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "things" subject.
     */
    public function testCanGetThingsPage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('things')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "concepts" subject.
     */
    public function testCanGetConceptsPage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('concepts')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "time" subject.
     */
    public function testCanGetTimePage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('time')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }

    /**
     * Test page access in the "language" subject.
     */
    public function testCanGetLanguagePage() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('language')->create();

        // Create a page to view & version
        $page = Page::factory()->category($category->id)->create();
        $version = PageVersion::factory()->page($page->id)
            ->testData($page->title, $page->summary, null, null, null)
            ->user(User::factory()->editor()->create()->id)->create();

        $response = $this->actingAs(User::factory()->make())
            ->get('/pages/'.$page->id.'.'.$page->slug);

        $response->assertStatus(200);
    }
}
