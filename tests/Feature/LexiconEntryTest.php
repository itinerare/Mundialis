<?php

namespace Tests\Feature;

use App\Models\Lexicon\LexiconEntry;
use App\Models\Lexicon\LexiconEtymology;
use App\Models\Subject\LexiconCategory;
use App\Models\Subject\LexiconSetting;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LexiconEntryTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /**
     * Test lexicon entry creation access.
     */
    public function testCanGetCreateLexiconEntry() {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/create');

        $response->assertStatus(200);
    }

    /**
     * Test lexicon entry creation access with a category.
     */
    public function testCanGetCreateLexiconEntryWithCategory() {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Create a persistent category for the entry to go into
        $category = LexiconCategory::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/create?category_id='.$category->id);

        $response->assertStatus(200);
    }

    /**
     * Test lexicon entry editing access.
     */
    public function testCanGetEditLexiconEntry() {
        // Create a temporary editor
        $user = User::factory()->editor()->make();

        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        $response = $this->actingAs($user)
            ->get('/language/lexicon/edit/'.$entry->id);

        $response->assertStatus(200);
    }

    /**
     * Test lexicon entry creation with minimal data.
     */
    public function testCanPostCreateEmptyLexiconEntry() {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Define some basic data
        $data = [
            'word'    => $this->faker->unique()->domainWord(),
            'meaning' => $this->faker->unique()->domainWord(),
            'class'   => $class->name,
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'word'    => $data['word'],
            'meaning' => $data['meaning'],
            'class'   => $data['class'],
        ]);
    }

    /**
     * Test lexicon entry editing with minimal data.
     */
    public function testCanPostEditEmptyLexiconEntry() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Define some basic data
        $data = [
            'word'    => $this->faker->unique()->domainWord(),
            'meaning' => $entry->meaning,
            'class'   => $entry->class,
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'   => $entry->id,
            'word' => $data['word'],
        ]);
    }

    /**
     * Test lexicon entry creation with a category.
     */
    public function testCanPostCreateLexiconEntryWithCategory() {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Create a category for the entry to go into
        $category = LexiconCategory::factory()->create();

        // Define some basic data
        $data = [
            'word'        => $this->faker->unique()->domainWord(),
            'meaning'     => $this->faker->unique()->domainWord(),
            'class'       => $class->name,
            'category_id' => $category->id,
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'word'        => $data['word'],
            'meaning'     => $data['meaning'],
            'class'       => $data['class'],
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test lexicon entry editing with a category.
     */
    public function testCanPostEditLexiconEntryWithCategory() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create a category for the entry to go into
        $category = LexiconCategory::factory()->create();

        // Define some basic data
        $data = [
            'word'        => $this->faker->unique()->domainWord(),
            'meaning'     => $entry->meaning,
            'class'       => $entry->class,
            'category_id' => $category->id,
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'          => $entry->id,
            'word'        => $data['word'],
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test lexicon entry creation with a parent entry.
     */
    public function testCanPostCreateLexiconEntryWithParentEntry() {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Create an entry to be the parent
        $parent = LexiconEntry::factory()->create();

        // Define some basic data
        $data = [
            'word'      => $this->faker->unique()->domainWord(),
            'meaning'   => $this->faker->unique()->domainWord(),
            'class'     => $class->name,
            'parent_id' => [0 => $parent->id],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        // Locate the newly created entry
        $entry = LexiconEntry::where('word', $data['word'])->where('meaning', $data['meaning'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_etymologies', [
            'entry_id'  => $entry->id,
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Test lexicon entry editing with a parent entry.
     */
    public function testCanPostEditLexiconEntryWithParentEntry() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create an entry to be the parent
        $parent = LexiconEntry::factory()->create();

        // Create a category for the entry to go into
        $category = LexiconCategory::factory()->create();

        // Define some basic data
        $data = [
            'word'      => $entry->word,
            'meaning'   => $entry->meaning,
            'class'     => $entry->class,
            'parent_id' => [0 => $parent->id],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_etymologies', [
            'entry_id'  => $entry->id,
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Test lexicon entry creation with a parent off-site word.
     */
    public function testCanPostCreateLexiconEntryWithParentWord() {
        // Ensure lexical classes are present to utilize
        $this->artisan('add-lexicon-settings');
        $class = LexiconSetting::all()->first();

        // Define some basic data
        $data = [
            'word'      => $this->faker->unique()->domainWord(),
            'meaning'   => $this->faker->unique()->domainWord(),
            'class'     => $class->name,
            'parent_id' => [0 => null],
            'parent'    => [0 => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/create', $data);

        // Locate the newly created entry
        $entry = LexiconEntry::where('word', $data['word'])->where('meaning', $data['meaning'])->first();

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_etymologies', [
            'entry_id' => $entry->id,
            'parent'   => $data['parent'][0],
        ]);
    }

    /**
     * Test lexicon entry editing with a parent off-site word.
     */
    public function testCanPostEditLexiconEntryWithParentWord() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create a category for the entry to go into
        $category = LexiconCategory::factory()->create();

        // Define some basic data
        $data = [
            'word'      => $entry->word,
            'meaning'   => $entry->meaning,
            'class'     => $entry->class,
            'parent_id' => [0 => null],
            'parent'    => [0 => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_etymologies', [
            'entry_id' => $entry->id,
            'parent'   => $data['parent'][0],
        ]);
    }

    /**
     * Test lexicon entry editing with conjugation/declension data.
     */
    public function testCanPostEditLexiconEntryWithConjugation() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create a category with test data for the entry to go into
        $category = LexiconCategory::factory()->testData($entry->lexicalClass->id)->create();

        // Set the entry's category
        $entry->update(['category_id' => $category->id]);

        // Define some basic data
        $data = [
            'word'     => $entry->word,
            'meaning'  => $entry->meaning,
            'class'    => $entry->class,
            'conjdecl' => ['Singular Nominative' => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'   => $entry->id,
            'word' => $data['word'],
            'data' => '{"Singular Nominative":"'.$data['conjdecl']['Singular Nominative'].'"}',
        ]);
    }

    /**
     * Test lexicon entry editing with empty conjugation/declension data.
     */
    public function testCanPostEditLexiconEntryWithEmptyConjugation() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->conjugationData()->create();

        // Create a category for the entry to go into
        $category = LexiconCategory::factory()->testData($entry->lexicalClass->id)->create();

        // Set the entry's category
        $entry->update(['category_id' => $category->id]);

        // Define some basic data
        $data = [
            'word'     => $entry->word,
            'meaning'  => $entry->meaning,
            'class'    => $entry->class,
            'conjdecl' => ['Singular Nominative' => null],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'   => $entry->id,
            'word' => $data['word'],
            'data' => '{"Singular Nominative":null}',
        ]);
    }

    /**
     * Test lexicon entry editing with conjugation/declension data.
     */
    public function testCanPostEditLexiconEntryWithAutoConjugation() {
        // Make an entry to edit
        $entry = LexiconEntry::factory()->create();

        // Create a category with test data for the entry to go into
        $category = LexiconCategory::factory()->extendedData($entry->lexicalClass->id)->create();

        // Set the entry's category, and make sure the word is set appropriately;
        // regex being fiddly, it's good to test with known values
        $entry->update([
            'word'        => 'test',
            'category_id' => $category->id,
        ]);

        // Define some basic data
        $data = [
            'word'     => 'test',
            'meaning'  => $entry->meaning,
            'class'    => $entry->class,
            'autoconj' => true,
            'conjdecl' => ['Singular Nominative' => 'test'],
        ];

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/edit/'.$entry->id, $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'   => $entry->id,
            'word' => $data['word'],
            'data' => '{"Singular Nominative":"btest"}',
        ]);
    }

    /**
     * Test lexicon entry deletion.
     * This should work.
     */
    public function testCanGetDeleteLexiconEntry() {
        // Make an entry to delete
        $entry = LexiconEntry::factory()->create();

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->get('/language/lexicon/delete/'.$entry->id);

        $response->assertStatus(200);
    }

    /**
     * Test lexicon entry deletion.
     * This should work.
     */
    public function testCanPostDeleteLexiconEntry() {
        // Make an entry to delete
        $entry = LexiconEntry::factory()->create();

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/delete/'.$entry->id);

        // Verify that the appropriate change has occurred
        $this->assertModelMissing($entry);
    }

    /**
     * Test lexicon entry deletion with a child entry.
     * This shouldn't work.
     */
    public function testCannotPostDeleteLexiconEntryWithChildEntry() {
        // Make a parent to attempt to delete
        $parent = LexiconEntry::factory()->create();

        // Make an entry to be the child
        $entry = LexiconEntry::factory()->create();

        // Create an etymology record
        LexiconEtymology::create([
            'entry_id'  => $entry->id,
            'parent_id' => $parent->id,
        ]);

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/delete/'.$parent->id);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('lexicon_entries', [
            'id'   => $parent->id,
            'word' => $parent->word,
        ]);
    }

    /**
     * Test lexicon entry deletion with a parent entry.
     * This should work.
     */
    public function testCanPostDeleteLexiconEntryWithParentEntry() {
        // Make a parent to attempt to delete
        $parent = LexiconEntry::factory()->create();

        // Make an entry to be the child
        $entry = LexiconEntry::factory()->create();

        // Create an etymology record
        $etymology = LexiconEtymology::create([
            'entry_id'  => $entry->id,
            'parent_id' => $parent->id,
        ]);

        // Make a temporary editor
        $user = User::factory()->editor()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/language/lexicon/delete/'.$entry->id);

        // Verify that the appropriate change has occurred
        $this->assertModelMissing($etymology);
        $this->assertModelMissing($entry);
    }
}
