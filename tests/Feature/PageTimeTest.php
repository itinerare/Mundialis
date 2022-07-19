<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTimeTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /**
     * Test page creation with a date.
     */
    public function testCanPostCreatePageWithDate() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('time')->create();

        // Create a date-enabled time division
        $division = TimeDivision::factory()->date()->create();

        // Define some basic data
        $data = [
            'title'                                   => $this->faker->unique()->domainWord(),
            'summary'                                 => null,
            'category_id'                             => $category->id,
            'date_start_'.$division->id               => mt_rand(1, 50),
            'date_end_'.$division->id                 => mt_rand(50, 100),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}},"parsed":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}}}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a date.
     */
    public function testCanPostEditPageWithDate() {
        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('time')->create();

        // Create a page to edit
        $page = Page::factory()->category($category->id)->create();

        // Create a date-enabled time division
        $division = TimeDivision::factory()->date()->create();

        // Define some basic data
        $data = [
            'title'                                   => $this->faker->unique()->domainWord(),
            'summary'                                 => null,
            'category_id'                             => $category->id,
            'date_start_'.$division->id               => mt_rand(1, 50),
            'date_end_'.$division->id                 => mt_rand(50, 100),
        ];

        // Make a persistent editor
        $user = User::factory()->editor()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}},"parsed":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}}}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /******************************************************************************
        TIMELINE ACCESS
    *******************************************************************************/

    /**
     * Tests timeline access.
     */
    public function testCanGetTimeline() {
        $user = User::factory()->make();

        // Create a date-enabled time division
        TimeDivision::factory()->date()->create();

        $response = $this->actingAs($user)
            ->get('/time/timeline');

        $response->assertStatus(200);
    }

    /**
     * Tests timeline access with an event.
     */
    public function testCanGetTimelineWithEvent() {
        $user = User::factory()->make();

        // Create a category for the page to go into
        $category = SubjectCategory::factory()->subject('time')->create();

        // Create a date-enabled time division
        $division = TimeDivision::factory()->date()->create();

        // Make a persistent editor
        $editor = User::factory()->editor()->create();

        // Create an event
        $page = Page::factory()->category($category->id)->create();
        PageVersion::factory()->page($page->id)->user($editor->id)->testData($this->faker->unique()->domainWord(), null, null, null, $division->id)->create();

        $response = $this->actingAs($user)
            ->get('/time/timeline');

        $response->assertStatus(200);
    }

    /**
     * Tests timeline access.
     */
    public function testCannotGetTimelineWithNoDateDivisions() {
        $user = User::factory()->make();

        // Create a time division
        TimeDivision::factory()->create();

        $response = $this->actingAs($user)
            ->get('/time/timeline');

        $response->assertStatus(404);
    }
}
