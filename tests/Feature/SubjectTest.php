<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        SUBJECTS
    *******************************************************************************/

    /**
     * Test people access.
     *
     * @return void
     */
    public function test_canGetPeople()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/people');

        $response->assertStatus(200);
    }

    /**
     * Test places access.
     *
     * @return void
     */
    public function test_canGetPlaces()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/places');

        $response->assertStatus(200);
    }

    /**
     * Test flora & fauna access.
     *
     * @return void
     */
    public function test_canGetFloraAndFauna()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/species');

        $response->assertStatus(200);
    }

    /**
     * Test things access.
     *
     * @return void
     */
    public function test_canGetThings()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/things');

        $response->assertStatus(200);
    }

    /**
     * Test concepts access.
     *
     * @return void
     */
    public function test_canGetConcepts()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/concepts');

        $response->assertStatus(200);
    }

    /**
     * Test time & events access.
     *
     * @return void
     */
    public function test_canGetTime()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/time');

        $response->assertStatus(200);
    }

    /**
     * Test language access.
     *
     * @return void
     */
    public function test_canGetLanguage()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/language');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous access.
     *
     * @return void
     */
    public function test_canGetMiscellaneous()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/misc');

        $response->assertStatus(200);
    }
}
