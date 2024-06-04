<?php

namespace Tests\Feature;

use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRankTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        ADMIN / RANKS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->make();
    }

    /**
     * Test rank index access.
     */
    public function testGetRankIndex() {
        $this->actingAs($this->admin)
            ->get('/admin/ranks')
            ->assertStatus(200);
    }

    /**
     * Test rank edit access.
     */
    public function testGetEditRank() {
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Attempt page access
        $this->actingAs($this->admin)
            ->get('/admin/ranks/edit/'.$rank->id)
            ->assertStatus(200);
    }

    /**
     * Test rank editing.
     *
     * @dataProvider rankEditProvider
     *
     * @param bool $withName
     * @param bool $withDesc
     * @param bool $expected
     */
    public function testPostEditRank($withName, $withDesc, $expected) {
        // Get the the lowest/member rank
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // Generate some testing data
        $name = $this->faker->unique()->domainWord();
        $description = $this->faker->unique()->domainWord();

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/ranks/edit/'.($expected ? $rank->id : 5), [
                'name'        => $withName ? $name : $rank->name,
                'description' => $withDesc ? $description : $rank->description,
            ]);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('ranks', [
                'id'          => $rank->id,
                'name'        => $withName ? $name : 'Member',
                'description' => $withDesc ? $description : 'A regular member of the site.',
            ]);
        } else {
            $response->assertSessionHasErrors();
        }
    }

    public static function rankEditProvider() {
        return [
            'with name'        => [1, 0, 1],
            'with description' => [0, 1, 1],
            'with both'        => [1, 1, 1],
            'invalid rank'     => [1, 0, 0],
        ];
    }
}
