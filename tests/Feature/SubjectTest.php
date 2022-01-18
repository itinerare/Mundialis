<?php

namespace Tests\Feature;

use App\Models\Subject\LexiconCategory;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User\User;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

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
     * Test time & events access with a chronology.
     *
     * @return void
     */
    public function test_canGetTimeWithChronology()
    {
        $user = User::factory()->make();

        TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/time');

        $response->assertStatus(200);
    }

    /**
     * Test time chronology access.
     *
     * @return void
     */
    public function test_canGetTimeChronology()
    {
        $user = User::factory()->make();

        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/time/chronologies/' . $chronology->id);

        $response->assertStatus(200);
    }

    /**
     * Test time chronology access with a child.
     *
     * @return void
     */
    public function test_canGetTimeChronologyWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $chronology[$i] = TimeChronology::factory()->create();
        }

        $chronology[2]->update(['parent_id', $chronology[1]->id]);

        $response = $this->actingAs($user)
            ->get('/time/chronologies/' . $chronology[1]->id);

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
     * Test miscellaneous access with a category.
     *
     * @return void
     */
    public function test_canGetLanguageWithLexiconCategory()
    {
        $user = User::factory()->make();

        LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access.
     *
     * @return void
     */
    public function test_canGetLexiconCategory()
    {
        $user = User::factory()->make();

        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/' . $category->id);

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access with a child.
     *
     * @return void
     */
    public function test_canGetLexiconCategoryWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $category[$i] = LexiconCategory::factory()->create();
        }

        $category[2]->update(['parent_id', $category[1]->id]);

        $response = $this->actingAs($user)
            ->get('/language/lexicon/' . $category[1]->id);

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

    /**
     * Test miscellaneous access with a category.
     *
     * @return void
     */
    public function test_canGetMiscellaneousWithCategory()
    {
        $user = User::factory()->make();

        SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/misc');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access.
     *
     * @return void
     */
    public function test_canGetMiscellaneousCategory()
    {
        $user = User::factory()->make();

        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/misc/categories/' . $category->id);

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access with a child.
     *
     * @return void
     */
    public function test_canGetMiscellaneousCategoryWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $category[$i] = SubjectCategory::factory()->create();
        }

        $category[2]->update(['parent_id', $category[1]->id]);

        $response = $this->actingAs($user)
            ->get('/misc/categories/' . $category[1]->id);

        $response->assertStatus(200);
    }
}
