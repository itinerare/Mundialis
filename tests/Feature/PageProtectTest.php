<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageProtection;
use App\Models\Page\PageVersion;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageProtectTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->page = Page::factory()->create();
        $this->editor = User::factory()->editor()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();

        $this->admin = User::factory()->admin()->create();
    }

    /**
     * Test page protection access.
     *
     * @param bool $isValid
     */
    #[DataProvider('getProtectPageProvider')]
    public function testGetProtectPage($isValid) {
        $response = $this->actingAs($this->admin)
            ->get('/pages/'.($isValid ? $this->page->id : 9999).'/protect');

        $response->assertStatus($isValid ? 200 : 404);
    }

    public static function getProtectPageProvider() {
        return [
            'valid'   => [1],
            'invalid' => [0],
        ];
    }

    /**
     * Test page protection.
     *
     * @param bool $isProtected
     * @param bool $newState
     * @param bool $withReason
     */
    #[DataProvider('postProtectPageProvider')]
    public function testPostProtectPage($isProtected, $newState, $withReason) {
        if ($isProtected) {
            PageProtection::factory()->page($this->page->id)->user($this->admin->id)->create();
        }

        $data = [
            'is_protected' => $newState,
            'reason'       => $withReason ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/pages/'.$this->page->id.'/protect', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('page_protections', [
            'page_id'      => $this->page->id,
            'user_id'      => $this->admin->id,
            'is_protected' => $newState,
            'reason'       => $data['reason'],
        ]);
    }

    public static function postProtectPageProvider() {
        return [
            'protect unprotected page'            => [0, 1, 0],
            'protect page with reason'            => [0, 1, 1],
            'update protected page with reason'   => [1, 1, 1],
            'unprotect protected page'            => [1, 0, 0],
            'unprotect page with reason'          => [1, 0, 1],
            'update unprotected page with reason' => [0, 0, 1],
        ];
    }
}
