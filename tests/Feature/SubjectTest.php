<?php

namespace Tests\Feature;

use App\Models\Subject\LexiconCategory;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test people access.
     */
    public function testCanGetPeople()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/people');

        $response->assertStatus(200);
    }

    /**
     * Test places access.
     */
    public function testCanGetPlaces()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/places');

        $response->assertStatus(200);
    }

    /**
     * Test flora & fauna access.
     */
    public function testCanGetFloraAndFauna()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/species');

        $response->assertStatus(200);
    }

    /**
     * Test things access.
     */
    public function testCanGetThings()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/things');

        $response->assertStatus(200);
    }

    /**
     * Test concepts access.
     */
    public function testCanGetConcepts()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/concepts');

        $response->assertStatus(200);
    }

    /**
     * Test time & events access.
     */
    public function testCanGetTime()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/time');

        $response->assertStatus(200);
    }

    /**
     * Test time & events access with a chronology.
     */
    public function testCanGetTimeWithChronology()
    {
        $user = User::factory()->make();

        TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/time');

        $response->assertStatus(200);
    }

    /**
     * Test time chronology access.
     */
    public function testCanGetTimeChronology()
    {
        $user = User::factory()->make();

        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/time/chronologies/'.$chronology->id);

        $response->assertStatus(200);
    }

    /**
     * Test time chronology access with a child.
     */
    public function testCanGetTimeChronologyWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $chronology[$i] = TimeChronology::factory()->create();
        }

        $chronology[2]->update(['parent_id', $chronology[1]->id]);

        $response = $this->actingAs($user)
            ->get('/time/chronologies/'.$chronology[1]->id);

        $response->assertStatus(200);
    }

    /**
     * Test language access.
     */
    public function testCanGetLanguage()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/language');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous access with a category.
     */
    public function testCanGetLanguageWithLexiconCategory()
    {
        $user = User::factory()->make();

        LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access.
     */
    public function testCanGetLexiconCategory()
    {
        $user = User::factory()->make();

        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/'.$category->id);

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access with a child.
     */
    public function testCanGetLexiconCategoryWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $category[$i] = LexiconCategory::factory()->create();
        }

        $category[2]->update(['parent_id', $category[1]->id]);

        $response = $this->actingAs($user)
            ->get('/language/lexicon/'.$category[1]->id);

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous access.
     */
    public function testCanGetMiscellaneous()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->get('/misc');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous access with a category.
     */
    public function testCanGetMiscellaneousWithCategory()
    {
        $user = User::factory()->make();

        SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/misc');

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access.
     */
    public function testCanGetMiscellaneousCategory()
    {
        $user = User::factory()->make();

        $category = SubjectCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/misc/categories/'.$category->id);

        $response->assertStatus(200);
    }

    /**
     * Test miscellaneous category access with a child.
     */
    public function testCanGetMiscellaneousCategoryWithChild()
    {
        $user = User::factory()->make();

        for ($i = 1; $i <= 2; $i++) {
            $category[$i] = SubjectCategory::factory()->create();
        }

        $category[2]->update(['parent_id', $category[1]->id]);

        $response = $this->actingAs($user)
            ->get('/misc/categories/'.$category[1]->id);

        $response->assertStatus(200);
    }
}
