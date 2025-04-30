<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Lexicon\LexiconEtymology;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LexiconEntryTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /******************************************************************************
        LANGUAGE / LEXICON
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->make();
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $this->class = LexiconSetting::all()->first();

        // Delete any entries/etymologies to ensure that counts are accurate
        LexiconEntry::query()->delete();
        LexiconEtymology::query()->delete();
    }

    /**
     * Test lexicon entry access.
     *
     * @param bool       $isValid
     * @param array|null $data
     * @param int        $status
     */
    #[DataProvider('getEntryProvider')]
    public function testGetLexiconEntry($isValid, $data, $status) {
        // Create an entry to view
        $entry = LexiconEntry::factory();
        if ($data && $data[3]) {
            $entry = $entry->conjugationData();
        }
        $entry = $entry->create([
            'definition' => $data && $data[0] ? '<p>'.$this->faker->unique()->domainWord().'</p>' : null,
        ]);

        if ($data && $data[1]) {
            $parent = LexiconEntry::factory()->create();
            LexiconEtymology::create([
                'entry_id'  => $entry->id,
                'parent_id' => $parent->id,
            ]);
        }

        if ($data && $data[2]) {
            $child = LexiconEntry::factory()->create();
            LexiconEtymology::create([
                'entry_id'  => $child->id,
                'parent_id' => $entry->id,
            ]);
        }

        $response = $this->actingAs($this->editor)
            ->get('/language/lexicon/entries/'.($isValid ? $entry->id : 9999));

        $response->assertStatus($status);
    }

    public static function getEntryProvider() {
        // $data = [$withDefinition, $withParent, $withChild, $withConjugation]

        return [
            'valid'                           => [1, null, 200],
            'with definition'                 => [1, [1, 0, 0, 0], 200],
            'with definition, parent'         => [1, [1, 1, 0, 0], 200],
            'with definition, child'          => [1, [1, 0, 1, 0], 200],
            'with definition, parent, child'  => [1, [1, 1, 1, 0], 200],
            'with definition, conjugation'    => [1, [1, 0, 0, 1], 200],
            'with parent'                     => [1, [0, 1, 0, 0], 200],
            'with parent, conjugation'        => [1, [0, 1, 0, 1], 200],
            'with child'                      => [1, [0, 0, 1, 0], 200],
            'with child, conjugation'         => [1, [0, 0, 1, 0], 200],
            'with parent, child'              => [1, [0, 1, 1, 0], 200],
            'with parent, child, conjugation' => [1, [0, 1, 1, 1], 200],
            'with all'                        => [1, [1, 1, 1, 1], 200],
            'invalid'                         => [0, null, 404],
        ];
    }

    /**
     * Test lexicon entry creation access.
     *
     * @param bool $withCategory
     */
    #[DataProvider('getCreateEntryProvider')]
    public function testGetCreateLexiconEntry($withCategory) {
        if ($withCategory) {
            // Create a category
            $category = LexiconCategory::factory()->create();
        }

        $response = $this->actingAs($this->editor)
            ->get('/language/lexicon/create'.($withCategory ? '?category_id='.$category->id : ''));

        $response->assertStatus(200);
    }

    public static function getCreateEntryProvider() {
        return [
            'without category' => [0],
            'with category'    => [1],
        ];
    }

    /**
     * Test lexicon entry edit access.
     *
     * @param bool $isValid
     * @param int  $status
     */
    #[DataProvider('getEditEntryProvider')]
    public function testGetEditLexiconEntry($isValid, $status) {
        if ($isValid) {
            // Make an entry to edit
            $entry = LexiconEntry::factory()->create();
        }

        $response = $this->actingAs($this->editor)
            ->get('/language/lexicon/edit/'.($isValid ? $entry->id : 9999));

        $response->assertStatus($status);
    }

    public static function getEditEntryProvider() {
        return [
            'valid entry'   => [1, 200],
            'invalid entry' => [0, 404],
        ];
    }

    /**
     * Test lexicon entry creation.
     *
     * @param bool       $withCategory
     * @param bool       $withDefinition
     * @param array|null $parent
     * @param array|null $conjData
     * @param bool       $expected
     */
    #[DataProvider('postCreateEditEntryProvider')]
    public function testPostCreateLexiconEntry($withCategory, $withDefinition, $parent, $conjData, $expected) {
        if ($withCategory) {
            // Create a category for the entry to go into
            $category = LexiconCategory::factory()->create();
        }

        if ($parent && $parent[0]) {
            // Create an entry to be the parent
            $parentEntry = LexiconEntry::factory()->create();
        }

        // Generate or otherwise set up some test data
        $data = [
            'word'        => $this->faker->unique()->domainWord(),
            'meaning'     => $expected ? $this->faker->unique()->domainWord() : null,
            'class'       => $this->class->name,
            'definition'  => $withDefinition ? '<p>'.$this->faker->unique()->domainWord().'</p>' : null,
            'category_id' => $withCategory ? $category->id : null,
            'parent_id'   => [] + ($parent && $parent[0] ? [$parentEntry->id] : []) + ($parent && $parent[1] ? [null] : []),
            'parent'      => [] + ($parent && $parent[0] ? [null] : []) + ($parent && $parent[1] ? [$this->faker->unique()->domainWord()] : []),
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/language/lexicon/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_entries', [
                'word'        => $data['word'],
                'meaning'     => $data['meaning'],
                'class'       => $data['class'],
                'definition'  => $data['definition'],
                'category_id' => $withCategory ? $category->id : null,
            ]);

            if ($parent) {
                // Locate the newly created entry
                $entry = LexiconEntry::where('word', $data['word'])->where('meaning', $data['meaning'])->first();

                if ($parent[0]) {
                    $this->assertDatabaseHas('lexicon_etymologies', [
                        'entry_id'  => $entry->id,
                        'parent_id' => $parentEntry->id,
                    ]);
                }

                if ($parent[1]) {
                    $this->assertDatabaseHas('lexicon_etymologies', [
                        'entry_id' => $entry->id,
                        'parent'   => $data['parent'][0],
                    ]);
                }
            }
        } else {
            $response->assertSessionHasErrors();
            $this->assertDatabaseCount('lexicon_entries', $parent[0] ?? 0);
            if ($parent) {
                $this->assertDatabaseEmpty('lexicon_etymologies');
            }
        }
    }

    /**
     * Test lexicon entry editing.
     *
     * @param bool       $withCategory
     * @param bool       $withDefinition
     * @param array|null $parent
     * @param array|null $conjData
     * @param bool       $expected
     */
    #[DataProvider('postCreateEditEntryProvider')]
    #[DataProvider('postConjugationProvider')]
    public function testPostEditLexiconEntry($withCategory, $withDefinition, $parent, $conjData, $expected) {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        if ($withCategory) {
            $category = LexiconCategory::factory();

            if (!$conjData) {
                // Create a category for the entry to go into
                $category = $category->create();
            } elseif ($conjData) {
                // Create a category with test data for the entry to go into
                if ($conjData[0]) {
                    $category = $category->extendedData($entry->lexicalClass->id)->create();
                } else {
                    $category = $category->testData($entry->lexicalClass->id)->create();
                }

                // Set the entry's category from the outset
                // This is necessary for adding conjugated/declined forms
                $entry->update(['category_id' => $category->id]);
            }
        }

        if ($parent && $parent[0]) {
            // Create an entry to be the parent
            $parentEntry = LexiconEntry::factory()->create();
        }

        // Generate or otherwise set up some test data
        $data = [
            'word'        => $this->faker->unique()->domainWord(),
            'meaning'     => $this->faker->unique()->domainWord(),
            'class'       => $this->class->name,
            'definition'  => $withDefinition ? '<p>'.$this->faker->unique()->domainWord().'</p>' : null,
            'category_id' => $withCategory ? $category->id : null,
            'parent_id'   => [] + ($parent && $parent[0] ? [$parentEntry->id] : []) + ($parent && $parent[1] ? [null] : []),
            'parent'      => [] + ($parent && $parent[0] ? [null] : []) + ($parent && $parent[1] ? [$this->faker->unique()->domainWord()] : []),
            'autoconj'    => $conjData[0] ?? 0,
            'conjdecl'    => $conjData ? ['Nominative Singular' => ($conjData[1] ? $this->faker->unique()->domainWord() : null)] : null,
        ];

        $response = $this
            ->actingAs($this->editor)
            ->post('/language/lexicon/edit/'.($expected ? $entry->id : 9999), $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('lexicon_entries', [
                'id'          => $entry->id,
                'word'        => $data['word'],
                'meaning'     => $data['meaning'],
                'class'       => $data['class'],
                'definition'  => $data['definition'],
                'category_id' => $withCategory ? $category->id : null,
                'data'        => $conjData ? '{"Nominative Singular":'.($conjData[1] ? '"'.($conjData[0] ? 'b'.$data['word'] : $data['conjdecl']['Nominative Singular']).'"' : 'null').'}' : null,
            ]);

            if ($parent) {
                if ($parent[0]) {
                    $this->assertDatabaseHas('lexicon_etymologies', [
                        'entry_id'  => $entry->id,
                        'parent_id' => $parentEntry->id,
                    ]);
                }

                if ($parent[1]) {
                    $this->assertDatabaseHas('lexicon_etymologies', [
                        'entry_id' => $entry->id,
                        'parent'   => $data['parent'][0],
                    ]);
                }
            }
        } else {
            $response->assertSessionHasErrors();
            if ($parent) {
                $this->assertDatabaseEmpty('lexicon_etymologies');
            }
        }
    }

    public static function postCreateEditEntryProvider() {
        // $parent = [$entry, $word]

        return [
            'basic'                                   => [0, 0, null, null, 1],
            'with definition'                         => [0, 1, null, null, 1],
            'invalid'                                 => [0, 0, null, null, 0],
            'with parent entry'                       => [0, 0, [1, 0], null, 1],
            'with parent word'                        => [0, 0, [0, 1], null, 1],
            'with parent entry and word'              => [0, 0, [1, 1], null, 1],
            'with definition and parent entry'        => [0, 1, [1, 0], null, 1],
            'with definition and parent word'         => [0, 1, [0, 1], null, 1],
            'with definition, parent entry, and word' => [0, 1, [1, 1], null, 1],
            'invalid with parent entry'               => [1, 0, [1, 0], null, 0],
            'invalid with parent word'                => [1, 0, [0, 1], null, 0],
            'invalid with parent entry and word'      => [1, 0, [1, 1], null, 0],
            'with category'                           => [1, 0, null, null, 1],
            'with category and definition'            => [1, 1, null, null, 1],
            'invalid with category'                   => [1, 0, null, null, 0],
        ];
    }

    public static function postConjugationProvider() {
        // $conjData = [$isAuto, $withConj]

        return [
            'without conjugation'  => [1, 0, null, [0, 0], 1],
            'with conjugation'     => [1, 0, null, [0, 1], 1],
            'with autoconjugation' => [1, 0, null, [1, 1], 1],
        ];
    }

    /**
     * Test lexicon entry deletion.
     *
     * @param bool $withEntry
     * @param int  $status
     */
    #[DataProvider('getDeleteEntryProvider')]
    public function testGetDeleteLexiconEntry($withEntry, $status) {
        if ($withEntry) {
            // Make an entry to delete
            $entry = LexiconEntry::factory()->create();
        }

        $response = $this
            ->actingAs($this->editor)
            ->get('/language/lexicon/delete/'.($withEntry ? $entry->id : 9999));

        $response->assertStatus($status);
    }

    public static function getDeleteEntryProvider() {
        return [
            'valid'   => [1, 200],
            'invalid' => [0, 404],
        ];
    }

    /**
     * Test lexicon entry deletion.
     *
     * @param bool $withParent
     * @param bool $withChild
     * @param bool $expected
     */
    #[DataProvider('postDeleteEntryProvider')]
    public function testPostDeleteLexiconEntry($withParent, $withChild, $expected) {
        // Make an entry to delete
        $entry = LexiconEntry::factory()->create();

        if ($withParent) {
            $parent = LexiconEntry::factory()->create();

            // This shouldn't prevent deletion of the entry
            // but should be deleted as a consequence
            $etymology = LexiconEtymology::create([
                'entry_id'  => $entry->id,
                'parent_id' => $parent->id,
            ]);
        }

        if ($withChild) {
            $child = LexiconEntry::factory()->create();

            // This, however, should prevent deletion of the entry
            LexiconEtymology::create([
                'entry_id'  => $child->id,
                'parent_id' => $entry->id,
            ]);
        }

        $response = $this
            ->actingAs($this->editor)
            ->post('/language/lexicon/delete/'.$entry->id);

        if ($expected) {
            $response->assertSessionHasNoErrors();
            $this->assertModelMissing($entry);

            if ($withParent) {
                $this->assertModelMissing($etymology);
            }
        } else {
            $response->assertSessionHasErrors();
            $this->assertModelExists($entry);

            if ($withParent) {
                $this->assertModelExists($etymology);
            }
        }
    }

    public static function postDeleteEntryProvider() {
        return [
            'basic'                 => [0, 0, 1],
            'with parent'           => [1, 0, 1],
            'with child'            => [0, 1, 0],
            'with parent and child' => [1, 1, 0],
        ];
    }
}
