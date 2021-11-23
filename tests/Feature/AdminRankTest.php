<?php

namespace Tests\Feature;

use DB;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\User\Rank;

class AdminRankTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        RANKS
    *******************************************************************************/

    /**
     * Test rank index access.
     *
     * @return void
     */
    public function test_canGetRankIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/ranks')
            ->assertStatus(200);
    }

    /**
     * Test rank edit access.
     *
     * @return void
     */
    public function test_canGetEditRank()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/ranks/edit/'.$rank->id)
            ->assertStatus(200);
    }

    /**
     * Test rank editing.
     *
     * @return void
     */
    public function test_canPostEditRank()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();
        // Get the information for the lowest rank
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Make sure the setting is default so as to consistently test
        $rank->update(['description' => 'A regular member of the site.']);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/ranks/edit/'.$rank->id, [
                'name' => 'Member',
                'description' => 'TEST SUCCESS'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('ranks', [
            'name' => 'Member',
            'description' => 'TEST SUCCESS'
        ]);
    }
}
