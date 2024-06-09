<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageRelationship;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageRelationshipTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->category = SubjectCategory::factory()->subject('people')->create();

        $this->editor = User::factory()->editor()->create();
        $this->page = Page::factory()->create();
        PageVersion::factory()->user($this->editor->id)->page($this->page->id)->create();

        $this->person = Page::factory()->category($this->category->id)->create();
        PageVersion::factory()->user($this->editor->id)->page($this->person->id)->create();
    }

    /**
     * Test page relationships access.
     *
     * @dataProvider getRelationshipsProvider
     *
     * @param bool $withPerson
     * @param bool $withRelationship
     * @param int  $status
     */
    public function testGetRelationships($withPerson, $withRelationship, $status) {
        if ($withRelationship) {
            $personTwo = Page::factory()->category($this->category->id)->create();
            PageRelationship::factory()->pageOne($withPerson ? $this->person->id : $this->page->id)->pageTwo($personTwo->id)->create();
        }

        $response = $this
            ->get('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships');

        $response->assertStatus($status);
    }

    public static function getRelationshipsProvider() {
        return [
            'with person'                   => [1, 0, 200],
            'with person with relationship' => [1, 1, 200],
            'with other page'               => [0, 0, 404],
        ];
    }

    /**
     * Test page relationship creation access.
     *
     * @dataProvider getCreateEditRelationshipProvider
     *
     * @param bool $withPerson
     * @param int  $status
     */
    public function testGetCreateRelationship($withPerson, $status) {
        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/create');

        $response->assertStatus($status);
    }

    /**
     * Test page relationship editing access.
     *
     * @dataProvider getCreateEditRelationshipProvider
     *
     * @param bool $withPerson
     * @param int  $status
     */
    public function testGetEditRelationship($withPerson, $status) {
        $personTwo = Page::factory()->category($this->category->id)->create();
        $relationship = PageRelationship::factory()->pageOne($withPerson ? $this->person->id : $this->page->id)->pageTwo($personTwo->id)->create();

        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/edit/'.$relationship->id);

        $response->assertStatus($status);
    }

    public static function getCreateEditRelationshipProvider() {
        return [
            'with person'     => [1, 200],
            'with other page' => [0, 404],
        ];
    }

    /**
     * Test relationship creation.
     *
     * @dataProvider postCreateEditRelationshipProvider
     *
     * @param bool  $withPerson
     * @param array $relationshipData
     * @param bool  $expected
     */
    public function testPostCreateRelationship($withPerson, $relationshipData, $expected) {
        $personTwo = Page::factory()->category($this->category->id)->create();

        $data = [
            'page_one_id'   => ($withPerson ? $this->person->id : $this->page->id),
            'page_two_id'   => $personTwo->id,
            'type_one'      => $relationshipData[0],
            'type_one_info' => $relationshipData[2] ? $this->faker->unique()->domainWord() : null,
            'details_one'   => $relationshipData[4] ? $this->faker->unique()->domainWord() : null,
            'type_two'      => $relationshipData[1],
            'type_two_info' => $relationshipData[3] ? $this->faker->unique()->domainWord() : null,
            'details_two'   => $relationshipData[5] ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('page_relationships', [
                'page_one_id'   => ($withPerson ? $this->person->id : $this->page->id),
                'page_two_id'   => $personTwo->id,
                'type_one'      => $relationshipData[0],
                'type_one_info' => $data['type_one_info'],
                'details_one'   => $data['details_one'],
                'type_two'      => $relationshipData[1],
                'type_two_info' => $data['type_two_info'],
                'details_two'   => $data['details_two'],
            ]);
        } else {
            if ($withPerson) {
                $response->assertSessionHasErrors();
            } else {
                $response->assertStatus(404);
            }

            $this->assertDatabaseMissing('page_relationships', [
                'page_one_id' => ($withPerson ? $this->person->id : $this->page->id),
                'page_two_id' => $personTwo->id,
            ]);
        }
    }

    /**
     * Test relationship editing.
     *
     * @dataProvider postCreateEditRelationshipProvider
     *
     * @param bool  $withPerson
     * @param array $relationshipData
     * @param bool  $expected
     */
    public function testPostEditRelationship($withPerson, $relationshipData, $expected) {
        $personTwo = Page::factory()->category($this->category->id)->create();
        $relationship = PageRelationship::factory()->pageOne($withPerson ? $this->person->id : $this->page->id)->pageTwo($personTwo->id)->familial()->create();

        $data = [
            'page_one_id'   => ($withPerson ? $this->person->id : $this->page->id),
            'page_two_id'   => $personTwo->id,
            'type_one'      => $relationshipData[0],
            'type_one_info' => $relationshipData[2] ? $this->faker->unique()->domainWord() : null,
            'details_one'   => $relationshipData[4] ? $this->faker->unique()->domainWord() : null,
            'type_two'      => $relationshipData[1],
            'type_two_info' => $relationshipData[3] ? $this->faker->unique()->domainWord() : null,
            'details_two'   => $relationshipData[5] ? $this->faker->unique()->domainWord() : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/edit/'.$relationship->id, $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('page_relationships', [
                'page_one_id'   => ($withPerson ? $this->person->id : $this->page->id),
                'page_two_id'   => $personTwo->id,
                'type_one'      => $relationshipData[0],
                'type_one_info' => $data['type_one_info'],
                'details_one'   => $data['details_one'],
                'type_two'      => $relationshipData[1],
                'type_two_info' => $data['type_two_info'],
                'details_two'   => $data['details_two'],
            ]);
        } else {
            if ($withPerson) {
                $response->assertSessionHasErrors();
            } else {
                $response->assertStatus(404);
            }

            $this->assertDatabaseMissing('page_relationships', [
                'page_one_id'   => ($withPerson ? $this->person->id : $this->page->id),
                'page_two_id'   => $personTwo->id,
                'type_one'      => $relationshipData[0],
                'type_one_info' => $data['type_one_info'],
                'details_one'   => $data['details_one'],
                'type_two'      => $relationshipData[1],
                'type_two_info' => $data['type_two_info'],
                'details_two'   => $data['details_two'],
            ]);
        }
    }

    public static function postCreateEditRelationshipProvider() {
        return [
            // $relationshipData = [$typeOne, $typeTwo, $withInfoOne, $withInfoTwo, $withDetailsOne, $withDetailsTwo]

            'with person'                  => [1, ['platonic_friend', 'platonic_friend', 0, 0, 0, 0], 1],
            'with info one'                => [1, ['platonic_friend', 'platonic_friend', 1, 0, 0, 0], 1],
            'with info two'                => [1, ['platonic_friend', 'platonic_friend', 0, 1, 0, 0], 1],
            'with both info'               => [1, ['platonic_friend', 'platonic_friend', 1, 1, 0, 0], 1],
            'with details one'             => [1, ['platonic_friend', 'platonic_friend', 0, 0, 1, 0], 1],
            'with details two'             => [1, ['platonic_friend', 'platonic_friend', 0, 0, 0, 1], 1],
            'with both details'            => [1, ['platonic_friend', 'platonic_friend', 0, 0, 1, 1], 1],
            'custom one without info'      => [1, ['custom', 'platonic_friend', 0, 0, 0, 0], 0],
            'custom one with two info'     => [1, ['custom', 'platonic_friend', 0, 1, 0, 0], 0],
            'custom one with info'         => [1, ['custom', 'platonic_friend', 1, 0, 0, 0], 1],
            'custom one with details one'  => [1, ['custom', 'platonic_friend', 1, 0, 1, 0], 1],
            'custom one with details two'  => [1, ['custom', 'platonic_friend', 1, 0, 0, 1], 1],
            'custom one with both details' => [1, ['custom', 'platonic_friend', 1, 0, 1, 1], 1],
            'custom two without info'      => [1, ['platonic_friend', 'custom', 0, 0, 0, 0], 0],
            'custom two with one info'     => [1, ['platonic_friend', 'custom', 1, 0, 0, 0], 0],
            'custom two with info'         => [1, ['platonic_friend', 'custom', 0, 1, 0, 0], 1],
            'custom two with details one'  => [1, ['platonic_friend', 'custom', 0, 1, 1, 0], 1],
            'custom two with details two'  => [1, ['platonic_friend', 'custom', 0, 1, 0, 1], 1],
            'custom two with both details' => [1, ['platonic_friend', 'custom', 0, 1, 1, 1], 1],
            'both custom without info'     => [1, ['custom', 'custom', 0, 0, 0, 0], 0],
            'both custom with one info'    => [1, ['custom', 'custom', 1, 0, 0, 0], 0],
            'both custom with two info'    => [1, ['custom', 'custom', 0, 1, 0, 0], 0],
            'both custom with info'        => [1, ['custom', 'custom', 1, 1, 0, 0], 1],
            'both custom with details one' => [1, ['custom', 'custom', 1, 1, 1, 0], 1],
            'both custom with details two' => [1, ['custom', 'custom', 1, 1, 0, 1], 1],
            'both custom with details'     => [1, ['custom', 'custom', 1, 1, 1, 1], 1],
            'with other page'              => [0, ['platonic_friend', 'platonic_friend', 0, 0, 0, 0], 0],
        ];
    }

    /**
     * Test page relationship deletion access.
     *
     * @dataProvider getCreateEditRelationshipProvider
     *
     * @param bool $withPerson
     * @param int  $status
     */
    public function testGetDeleteRelationship($withPerson, $status) {
        $personTwo = Page::factory()->category($this->category->id)->create();
        $relationship = PageRelationship::factory()->pageOne($withPerson ? $this->person->id : $this->page->id)->pageTwo($personTwo->id)->create();

        $response = $this->actingAs($this->editor)
            ->get('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/delete/'.$relationship->id);

        $response->assertStatus($status);
    }

    /**
     * Test relationship deletion.
     */
    public function testPostDeleteRelationship() {
        $personTwo = Page::factory()->category($this->category->id)->create();
        $relationship = PageRelationship::factory()->pageOne($this->person->id)->pageTwo($personTwo->id)->create();

        $response = $this
            ->actingAs($this->editor)
            ->post('/pages/'.$this->person->id.'/relationships/delete/'.$relationship->id);

        $response->assertSessionHasNoErrors();
        $this->assertModelMissing($relationship);
    }

    /**
     * Test page family tree access.
     *
     * @dataProvider getFamilyTreeProvider
     *
     * @param bool  $withPerson
     * @param array $relationshipData
     * @param bool  $status
     */
    public function testGetFamilyTree($withPerson, $relationshipData, $status) {
        $personTwo = Page::factory()->category($this->category->id)->create();

        if ($relationshipData[0]) {
            $relationship = PageRelationship::factory()->pageOne($withPerson ? $this->person->id : $this->page->id)->pageTwo($personTwo->id);

            if ($relationshipData[1]) {
                $relationship = $relationship->familial();
            }
            $relationship = $relationship->create();
        }

        $response = $this
            ->get('/pages/'.($withPerson ? $this->person->id : $this->page->id).'/relationships/tree');

        $response->assertStatus($status);
    }

    public static function getFamilyTreeProvider() {
        return [
            'with person with family'       => [1, [1, 1], 200],
            'with person with friend'       => [1, [1, 0], 404],
            'with person'                   => [1, [0, 0], 404],
            'with other page'               => [0, [0, 0], 404],
        ];
    }
}
