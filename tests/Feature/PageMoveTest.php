<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageMoveTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->page = Page::factory()->create();
        $this->editor = User::factory()->editor()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();
    }

    /**
     * Test page move access.
     *
     * @param bool $isValid
     */
    #[DataProvider('getMovePageProvider')]
    public function testGetMovePage($isValid) {
        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($isValid ? $this->page->id : 9999).'/move');

        $response->assertStatus($isValid ? 200 : 404);
    }

    public static function getMovePageProvider() {
        return [
            'valid'   => [1],
            'invalid' => [0],
        ];
    }

    /**
     * Test page moving.
     *
     * @param bool $withPage
     * @param bool $withReason
     * @param bool $withConflict
     * @param bool $expected
     */
    #[DataProvider('postMovePageProvider')]
    public function testPostMovePage($withPage, $withReason, $withConflict, $expected) {
        $category = SubjectCategory::factory()->create();
        $oldCategory = $this->page->category;

        if ($withConflict) {
            Page::factory()->category($category->id)->create([
                'title' => $this->page->title,
            ]);
        }

        $data = [
            'category_id' => $category->id,
            'reason'      => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.($withPage ? $this->page->id : 9999).'/move', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('pages', [
                'id'          => $this->page->id,
                'category_id' => $category->id,
            ]);

            $this->assertDatabaseHas('page_versions', [
                'page_id' => $this->page->id,
                'type'    => 'Page Moved from '.$oldCategory->name.' to '.$category->name,
                'reason'  => $data['reason'],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseHas('pages', [
                'id'          => $this->page->id,
                'category_id' => $oldCategory->id,
            ]);
        }
    }

    public static function postMovePageProvider() {
        return [
            'with page'     => [1, 0, 0, 1],
            'with reason'   => [1, 1, 0, 1],
            'with conflict' => [1, 0, 1, 0],
            'without page'  => [0, 0, 0, 0],
        ];
    }
}
