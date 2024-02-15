<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageResetTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->page = Page::factory()->create();
        $this->editor = User::factory()->editor()->create();
        $this->oldVersion = PageVersion::factory()->user($this->editor->id)->page($this->page->id)->testData()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->type('Page Edited')->testData()->create();
    }

    /**
     * Test page reset access.
     *
     * @dataProvider getResetPageProvider
     *
     * @param bool $withPage
     */
    public function testGetResetPage($withPage) {
        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPage ? $this->page->id : mt_rand(500, 1000)).'/history/'.$this->oldVersion->id.'/reset');

        $response->assertStatus(200);

        if ($withPage) {
            $response->assertSee('You are about to reset the page');
        } else {
            $response->assertSee('Invalid page selected.');
        }
    }

    public static function getResetPageProvider() {
        return [
            'with page'    => [1],
            'without page' => [0],
        ];
    }

    /**
     * Test page resetting.
     *
     * @dataProvider postResetPageProvider
     *
     * @param bool $withPage
     * @param bool $withReason
     * @param bool $expected
     */
    public function testPostResetPage($withPage, $withReason, $expected) {
        $data = [
            'reason' => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.($withPage ? $this->page->id : mt_rand(500, 1000)).'/history/'.$this->oldVersion->id.'/reset', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('page_versions', [
                'page_id' => $this->page->id,
                'type'    => 'Page Reset to Ver. #'.$this->oldVersion->id,
                'reason'  => $data['reason'],
            ]);
        } else {
            if ($withPage) {
                $response->assertSessionHasErrors();
            } else {
                $response->assertStatus(404);
            }
        }
    }

    public static function postResetPageProvider() {
        return [
            'with page'     => [1, 0, 1],
            'with reason'   => [1, 1, 1],
            'without page'  => [0, 0, 0],
        ];
    }
}
