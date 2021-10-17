<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;

class subjectPageTest extends TestCase
{

    /******************************************************************************
        SUBJECTS
    *******************************************************************************/

    /**
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
     * Test subject access.
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
