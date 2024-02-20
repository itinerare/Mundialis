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

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
        $this->category = SubjectCategory::factory()->subject('time')->create();
    }

    /**
     * Test page creation with a date.
     */
    public function testPostCreatePageWithDate() {
        $division = TimeDivision::factory()->date()->create();

        $data = [
            'title'                     => $this->faker->unique()->domainWord(),
            'summary'                   => null,
            'category_id'               => $this->category->id,
            'date_start_'.$division->id => mt_rand(1, 50),
            'date_end_'.$division->id   => mt_rand(50, 100),
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/create', $data);

        $page = Page::where('title', $data['title'])->where('category_id', $this->category->id)->first();

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('page_versions', [
            'page_id' => $page->id,
            'data'    => '{"data":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}},"parsed":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}}}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
        ]);
    }

    /**
     * Test page editing with a date.
     */
    public function testPostEditPageWithDate() {
        $page = Page::factory()->category($this->category->id)->create();
        $division = TimeDivision::factory()->date()->create();

        $data = [
            'title'                     => $this->faker->unique()->domainWord(),
            'summary'                   => null,
            'category_id'               => $this->category->id,
            'date_start_'.$division->id => mt_rand(1, 50),
            'date_end_'.$division->id   => mt_rand(50, 100),
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$page->id.'/edit', $data);

        $response->assertSessionHasNoErrors();
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
     *
     * @dataProvider getTimelineProvider
     *
     * @param bool $withDivision
     * @param bool $withEvent
     * @param bool $status
     */
    public function testGetTimeline($withDivision, $withEvent, $status) {
        // Ensure no time divisions or versions remain from prior tests
        TimeDivision::query()->delete();
        PageVersion::query()->delete();

        if ($withDivision) {
            $division = TimeDivision::factory()->date()->create();
        }

        if ($withEvent) {
            $page = Page::factory()->category($this->category->id)->create();

            $data = [
                'title'       => $page->title,
                'summary'     => null,
                'category_id' => $this->category->id,
            ] + ($withDivision ? [
                'date_start_'.$division->id => mt_rand(1, 50),
                'date_end_'.$division->id   => mt_rand(50, 100),
            ] : []);

            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData($page->title, null, null, null, $withDivision ? $division->id : null)->create($withDivision ? [
                'data' => '{"data":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}},"parsed":{"description":null,"date":{"start":{"'.$division->id.'":'.$data['date_start_'.$division->id].'},"end":{"'.$division->id.'":'.$data['date_end_'.$division->id].'}}}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
            ] : []);
        }

        $response = $this->get('/time/timeline')
            ->assertStatus($status);

        if ($withDivision && $withEvent) {
            $response->assertSeeText($page->title);
        }
    }

    public static function getTimelineProvider() {
        return [
            'with division'           => [1, 0, 200],
            'with division and event' => [1, 1, 200],
            'with event'              => [0, 1, 404],
            'with neither'            => [0, 0, 404],
        ];
    }
}
