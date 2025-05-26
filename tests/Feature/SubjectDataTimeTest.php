<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubjectDataTimeTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->category = SubjectCategory::factory()->subject('time')->create();
    }

    /******************************************************************************
        DIVISIONS
    *******************************************************************************/

    /**
     * Test time divisions access.
     *
     * @param bool $withDivision
     */
    #[DataProvider('getTimeDivisionsProvider')]
    public function testGetEditTimeDivisions($withDivision) {
        if ($withDivision) {
            $division = TimeDivision::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/time/divisions')
            ->assertStatus(200);

        if ($withDivision) {
            $response->assertSee($division->name);
        } else {
            $response->assertViewHas('divisions', function ($divisions) {
                return $divisions->count() == 0;
            });
        }
    }

    public static function getTimeDivisionsProvider() {
        return [
            'basic'         => [0],
            'with division' => [1],
        ];
    }

    /**
     * Test time division creation.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $withUnit
     * @param bool $dateEnabled
     * @param bool $expected
     */
    #[DataProvider('postTimeDivisionsProvider')]
    public function testPostCreateTimeDivision($withName, $withAbbreviation, $withUnit, $dateEnabled, $expected) {
        $data = [
            'name'         => [0 => $withName ? $this->faker->unique()->domainWord() : null],
            'abbreviation' => [0 => $withAbbreviation ? $this->faker->unique()->domainWord() : null],
            'unit'         => [0 => $withUnit ? mt_rand(1, 100) : null],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/time/divisions', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('time_divisions', [
                'name'         => $data['name'][0],
                'abbreviation' => $data['abbreviation'][0],
                'unit'         => $data['unit'][0],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('time_divisions', [
                'name'         => $data['name'][0],
                'abbreviation' => $data['abbreviation'][0],
                'unit'         => $data['unit'][0],
            ]);
        }
    }

    /**
     * Test time division editing.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $withUnit
     * @param bool $dateEnabled
     * @param bool $expected
     */
    #[DataProvider('postTimeDivisionsProvider')]
    #[DataProvider('postEditTimeDivisionsProvider')]
    public function testPostEditTimeDivisions($withName, $withAbbreviation, $withUnit, $dateEnabled, $expected) {
        for ($i = 0; $i <= 1; $i++) {
            $division[$i] = TimeDivision::factory()->create();
        }

        $data = [
            'id' => [
                0 => $division[0]->id,
                1 => $division[1]->id,
            ],
            'name' => [
                0 => $withName ? $this->faker->unique()->domainWord() : null,
                1 => $division[1]->name,
            ],
            'abbreviation' => [
                0 => $withAbbreviation ? $this->faker->unique()->domainWord() : null,
                1 => $division[1]->abbreviation,
            ],
            'unit' => [
                0 => $withUnit ? mt_rand(1, 100) : null,
                1 => $division[1]->unit,
            ],
            'use_for_dates' => [
                $division[0]->id => $dateEnabled ?? null,
                $division[1]->id => $division[1]->use_for_dates ?? null,
            ],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/time/divisions', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('time_divisions', [
                'id'            => $division[0]->id,
                'name'          => $data['name'][0],
                'abbreviation'  => $data['abbreviation'][0],
                'unit'          => $data['unit'][0],
                'use_for_dates' => $data['use_for_dates'][$division[0]->id] ?? 0,
            ]);

            $this->assertDatabaseHas('time_divisions', [
                'id'            => $division[1]->id,
                'name'          => $data['name'][1],
                'abbreviation'  => $data['abbreviation'][1],
                'unit'          => $data['unit'][1],
                'use_for_dates' => $data['use_for_dates'][$division[1]->id] ?? 0,
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('time_divisions', [
                'id'            => $division[0]->id,
                'name'          => $data['name'][0],
                'abbreviation'  => $data['abbreviation'][0],
                'unit'          => $data['unit'][0],
                'use_for_dates' => $data['use_for_dates'][$division[0]->id] ?? 0,
            ]);
        }
    }

    public static function postTimeDivisionsProvider() {
        return [
            'basic'                   => [1, 0, 0, 0, 1],
            'with abbreviation'       => [1, 1, 0, 0, 1],
            'with unit'               => [1, 0, 1, 0, 1],
            'with abbreviation, unit' => [1, 0, 1, 0, 1],
            'without name'            => [0, 0, 0, 0, 0],
        ];
    }

    public static function postEditTimeDivisionsProvider() {
        return [
            'date enabled'                   => [1, 0, 0, 1, 1],
            'date enabled with abbreviation' => [1, 1, 0, 1, 1],
            'date enabled with unit'         => [1, 0, 1, 1, 1],
            'with everything'                => [1, 1, 1, 1, 1],
        ];
    }

    /******************************************************************************
        CHRONOLOGIES
    *******************************************************************************/

    /**
     * Test time chronologies access.
     *
     * @param bool $withChronology
     */
    #[DataProvider('getTimeChronologiesProvider')]
    public function testGetTimeChronologies($withChronology) {
        if ($withChronology) {
            $chronology = TimeChronology::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/time/chronology')
            ->assertStatus(200);

        if ($withChronology) {
            $response->assertSeeText($chronology->name);
        } else {
            $response->assertViewHas('chronologies', function ($chronologies) {
                return $chronologies->count() == 0;
            });
        }
    }

    public static function getTimeChronologiesProvider() {
        return [
            'basic'           => [0],
            'with chronology' => [1],
        ];
    }

    /**
     * Test time chronology create access.
     *
     * @param bool $withChronology
     */
    #[DataProvider('getTimeChronologiesProvider')]
    public function testGetCreateTimeChronology($withChronology) {
        if ($withChronology) {
            $chronology = TimeChronology::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/time/chronology/create')
            ->assertStatus(200);

        if ($withChronology) {
            $response->assertSee($chronology->name);
        } else {
            $response->assertViewHas('chronologyOptions', function ($chronologies) {
                return count($chronologies) == 0;
            });
        }
    }

    /**
     * Test time chronology edit access.
     *
     * @param bool $withChronology
     */
    #[DataProvider('getTimeChronologiesProvider')]
    public function testGetEditTimeChronology($withChronology) {
        $chronology = TimeChronology::factory()->create();

        if ($withChronology) {
            $chronologyOption = TimeChronology::factory()->create();
        }

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/time/chronology/edit/'.$chronology->id)
            ->assertStatus(200);

        if ($withChronology) {
            $response->assertSee($chronologyOption->name);
        } else {
            $response->assertViewHas('chronologyOptions', function ($chronologies) {
                return count($chronologies) == 0;
            });
        }
    }

    /**
     * Test time chronology creation.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $expected
     */
    #[DataProvider('postTimeChronologyProvider')]
    public function testPostCreateTimeChronology($withName, $withAbbreviation, $withParent, $withDescription, $expected) {
        if ($withParent) {
            $parent = TimeChronology::factory()->create();
        }

        $data = [
            'name'         => $withName ? $this->faker->unique()->domainWord() : null,
            'abbreviation' => $withAbbreviation ? $this->faker->unique()->domainWord() : null,
            'parent_id'    => $withParent ? $parent->id : null,
            'description'  => $withDescription ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/time/chronology/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('time_chronology', [
                'name'         => $data['name'],
                'abbreviation' => $data['abbreviation'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('time_chronology', [
                'name'         => $data['name'],
                'abbreviation' => $data['abbreviation'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
            ]);
        }
    }

    /**
     * Test time chronology editing.
     *
     * @param bool $withName
     * @param bool $withAbbreviation
     * @param bool $withParent
     * @param bool $withDescription
     * @param bool $expected
     */
    #[DataProvider('postTimeChronologyProvider')]
    public function testPostEditTimeChronology($withName, $withAbbreviation, $withParent, $withDescription, $expected) {
        $chronology = TimeChronology::factory()->create();

        if ($withParent) {
            $parent = TimeChronology::factory()->create();
        }

        $data = [
            'name'         => $withName ? $this->faker->unique()->domainWord() : null,
            'abbreviation' => $withAbbreviation ? $this->faker->unique()->domainWord() : null,
            'parent_id'    => $withParent ? $parent->id : null,
            'description'  => $withDescription ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/time/chronology/edit/'.$chronology->id, $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('time_chronology', [
                'id'           => $chronology->id,
                'name'         => $data['name'],
                'abbreviation' => $data['abbreviation'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
            ]);
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseMissing('time_chronology', [
                'id'           => $chronology->id,
                'name'         => $data['name'],
                'abbreviation' => $data['abbreviation'],
                'parent_id'    => $data['parent_id'],
                'description'  => $data['description'],
            ]);
        }
    }

    public static function postTimeChronologyProvider() {
        return [
            'with name'                            => [1, 0, 0, 0, 1],
            'with name, abbreviation'              => [1, 1, 0, 0, 1],
            'with name, parent'                    => [1, 0, 1, 0, 1],
            'with name, description'               => [1, 0, 0, 1, 1],
            'with name, abbreviation, parent'      => [1, 1, 1, 0, 1],
            'with name, abbreviation, description' => [1, 1, 0, 1, 1],
            'with name, parent, description'       => [1, 0, 1, 1, 1],
            'with everything'                      => [1, 1, 1, 1, 1],
            'without name'                         => [0, 0, 0, 0, 0],
        ];
    }

    /**
     * Test chronology delete access.
     *
     * @param bool $withChronology
     */
    #[DataProvider('getTimeChronologiesProvider')]
    public function testGetDeleteTimeChronology($withChronology) {
        $chronology = TimeChronology::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get('/admin/data/time/chronology/delete/'.($withChronology ? $chronology->id : 9999))
            ->assertStatus(200);

        if ($withChronology) {
            $response->assertSeeText('You are about to delete the chronology '.$chronology->name);
        } else {
            $response->assertSeeText('Invalid chronology selected');
        }
    }

    /**
     * Test chronology deletion.
     *
     * @param bool $withChronology
     * @param bool $withChild
     * @param bool $withPage
     * @param bool $expected
     */
    #[DataProvider('postDeleteTimeChronologyProvider')]
    public function testPostDeleteTimeChronology($withChronology, $withChild, $withPage, $expected) {
        $chronology = TimeChronology::factory()->create();

        if ($withChild) {
            TimeChronology::factory()->create([
                'parent_id' => $chronology->id,
            ]);
        }

        if ($withPage) {
            $category = SubjectCategory::factory()->subject('time')->create();
            Page::factory()->category($category->id)->create([
                'parent_id' => $chronology->id,
            ]);
        }

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/time/chronology/delete/'.($withChronology ? $chronology->id : 9999));

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertModelMissing($chronology);
        } else {
            $response->assertSessionHasErrors();
            $this->assertModelExists($chronology);
        }
    }

    public static function postDeleteTimeChronologyProvider() {
        return [
            'with chronology'        => [1, 0, 0, 1],
            'with chronology, child' => [1, 1, 0, 0],
            'with chronology, page'  => [1, 0, 1, 0],
            'with everything'        => [1, 1, 1, 0],
            'without chronology'     => [0, 0, 0, 0],
        ];
    }
}
