<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageProtection;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageProtectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test page protection access.
     *
     * @return void
     */
    public function test_canGetProtectPage()
    {
        // Create a temporary admin
        $user = User::factory()->admin()->make();
        // Create a page to protect
        $page = Page::factory()->create();

        $response = $this->actingAs($user)
            ->get('/pages/'.$page->id.'/protect');

        $response->assertStatus(200);
    }

    /**
     * Test page protection.
     *
     * @return void
     */
    public function test_canPostProtectPage()
    {
        // Make a page to protect
        $page = Page::factory()->create();

        // Make a persistent admin
        $user = User::factory()->admin()->create();

        $data = [
            'is_protected' => 1,
            'reason'       => null,
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/protect', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_protections', [
            'page_id'      => $page->id,
            'user_id'      => $user->id,
            'is_protected' => 1,
        ]);
    }

    /**
     * Test page protection with a reason.
     *
     * @return void
     */
    public function test_canPostProtectPageWithReason()
    {
        // Make a page to protect
        $page = Page::factory()->create();

        // Make a persistent admin
        $user = User::factory()->admin()->create();

        $data = [
            'is_protected' => 1,
            'reason'       => $this->faker->unique()->domainWord(),
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/protect', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_protections', [
            'page_id'      => $page->id,
            'user_id'      => $user->id,
            'is_protected' => 1,
            'reason'       => $data['reason'],
        ]);
    }

    /**
     * Test page unprotection.
     *
     * @return void
     */
    public function test_canPostUnprotectPage()
    {
        // Make a page to protect
        $page = Page::factory()->create();

        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Create a protection record
        PageProtection::factory()->page($page->id)->user($user->id)->create();

        $data = [
            'is_protected' => 0,
            'reason'       => null,
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/protect', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_protections', [
            'page_id'      => $page->id,
            'user_id'      => $user->id,
            'is_protected' => 0,
        ]);
    }

    /**
     * Test page unprotection with a reason.
     *
     * @return void
     */
    public function test_canPostUnprotectPageWithReason()
    {
        // Make a page to protect
        $page = Page::factory()->create();

        // Make a persistent admin
        $user = User::factory()->admin()->create();

        // Create a protection record
        PageProtection::factory()->page($page->id)->user($user->id)->create();

        $data = [
            'is_protected' => 0,
            'reason'       => $this->faker->unique()->domainWord(),
        ];

        // Try to post
        $response = $this
            ->actingAs($user)
            ->post('/pages/'.$page->id.'/protect', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('page_protections', [
            'page_id'      => $page->id,
            'user_id'      => $user->id,
            'is_protected' => 0,
            'reason'       => $data['reason'],
        ]);
    }
}
