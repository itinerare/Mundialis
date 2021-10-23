<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeDivision;
use App\Models\Subject\TimeChronology;
use App\Models\Page\Page;

class SubjectDataTimeTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /******************************************************************************
        TIME
    *******************************************************************************/

    /**
     * Test time divisions access.
     *
     * @return void
     */
    public function test_canGetEditTimeDivisions()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/divisions')
            ->assertStatus(200);
    }

    /**
     * Test time division creation.
     *
     * @return void
     */
    public function test_canPostCreateTimeDivision()
    {
        // Define some basic template data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
            'unit' => [0 => mt_rand(1,100)]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/divisions', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_divisions', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0]
        ]);
    }

    /**
     * Test time division editing.
     *
     * @return void
     */
    public function test_canPostEditTimeDivisions()
    {
        // Define some basic template data
        $data = [
            'name' => [0 => $this->faker->unique()->domainWord()],
            'abbreviation' => [0 => $this->faker->unique()->domainWord()],
            'unit' => [0 => mt_rand(1,100)]
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $division = TimeDivision::create([
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0],
        ]);

        // Define some more basic template data
        $data['name'][1] = $this->faker->unique()->domainWord();
        $data['abbreviation'][1] = $this->faker->unique()->domainWord();
        $data['unit'][1] = mt_rand(1,100);
        $data['id'][0] = $division->id;

        // Try to post data again
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/divisions', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_divisions', [
            'name' => $data['name'][0],
            'abbreviation' => $data['abbreviation'][0],
            'unit' => $data['unit'][0],
            'id' => $division->id
        ]);
    }

    /**
     * Test time chronologies access.
     *
     * @return void
     */
    public function test_canGetTimeChronologies()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology')
            ->assertStatus(200);
    }

    /**
     * Test time chronology create access.
     *
     * @return void
     */
    public function test_canGetCreateTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology/create')
            ->assertStatus(200);
    }

    /**
     * Test time chronology edit access.
     *
     * @return void
     */
    public function test_canGetEditTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology/edit/'.$chronology->id)
            ->assertStatus(200);
    }

    /**
     * Test time chronology creation.
     *
     * @return void
     */
    public function test_canPostCreateTimeChronology()
    {
        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'description' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    /**
     * Test time chronology editing.
     *
     * @return void
     */
    public function test_canPostEditTimeChronology()
    {
        $chronology = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord()
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/edit/'.$chronology->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'id' => $chronology->id,
            'name' => $data['name']
        ]);
    }

    /**
     * Test time chronology creation with a parent.
     *
     * @return void
     */
    public function test_canPostCreateTimeChronologyWithParent()
    {
        $parent = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test time chronology editing with a parent.
     *
     * @return void
     */
    public function test_canPostEditTimeChronologyWithParent()
    {
        $chronology = TimeChronology::factory()->create();
        $parent = TimeChronology::factory()->create();

        // Define some basic template data
        $data = [
            'name' => $this->faker->unique()->domainWord(),
            'parent_id' => $parent->id
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/edit/'.$chronology->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('time_chronology', [
            'id' => $chronology->id,
            'name' => $data['name'],
            'parent_id' => $parent->id
        ]);
    }

    /**
     * Test chronology delete access.
     *
     * @return void
     */
    public function test_canGetDeleteTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();
        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($user)
            ->get('/admin/data/time/chronology/delete/'.$chronology->id)
            ->assertStatus(200);
    }

    /**
     * Test chronology deletion.
     * This should work.
     *
     * @return void
     */
    public function test_canPostDeleteTimeChronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Create a chronology to delete
        $chronology = TimeChronology::factory()->create();

        // Count existing chronologies
        $oldCount = TimeChronology::all()->count();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/delete/'.$chronology->id);

        // Check that there are fewer chronologies than before
        $this->assertTrue(TimeChronology::all()->count() < $oldCount);
    }

    /**
     * Test chronology deletion with a page.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostDeleteTimeChronologyWithPage()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing chronologies
        $oldCount = TimeChronology::all()->count();
        // Create a chronology to delete
        $chronology = TimeChronology::factory()->create();
        // Create a page with the chronology
        $category = SubjectCategory::factory()->subject('time')->create();
        $page = Page::factory()->category($category->id)->create();
        $page->update(['parent_id' => $chronology->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/delete/'.$chronology->id);

        // Check that there are the same number of chronologies or more
        $this->assertTrue(TimeChronology::all()->count() >= $oldCount);
    }

    /**
     * Test chronology deletion with a sub-category.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostDeleteTimeChronologyWithSubchronology()
    {
        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Count existing chronologies
        $oldCount = TimeChronology::all()->count();
        // Create a chronology to delete
        $chronology = TimeChronology::factory()->create();
        // Create a subcategory of the chronology, and set its parent ID
        $subchronology = TimeChronology::factory()->create();
        $subchronology->update(['parent_id' => $chronology->id]);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/time/chronology/delete/'.$chronology->id);

        // Check that there are the same number of chronologies or more
        $this->assertTrue(TimeChronology::all()->count() >= $oldCount);
    }
}
