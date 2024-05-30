<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageEditFieldTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();
    }

    /**
     * Test page creation access with an infobox field.
     *
     * @dataProvider getPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    public function testGetCreatePageWithInfoboxField($fieldType, $withRule, $withValue, $withHelp) {
        $this->getPageWithField(0, 'infobox', $fieldType, $withRule, $withValue, $withHelp);
    }

    /**
     * Test page editing access with an infobox field.
     *
     * @dataProvider getPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    public function testGetEditPageWithInfoboxField($fieldType, $withRule, $withValue, $withHelp) {
        $this->getPageWithField(1, 'infobox', $fieldType, $withRule, $withValue, $withHelp);
    }

    /**
     * Test page creation access with a main body field.
     *
     * @dataProvider getPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    public function testGetCreatePageWithTemplateField($fieldType, $withRule, $withValue, $withHelp) {
        $this->getPageWithField(0, 'body', $fieldType, $withRule, $withValue, $withHelp);
    }

    /**
     * Test page editing access with a main body field.
     *
     * @dataProvider getPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    public function testGetEditPageWithTemplateField($fieldType, $withRule, $withValue, $withHelp) {
        $this->getPageWithField(1, 'body', $fieldType, $withRule, $withValue, $withHelp);
    }

    public static function getPageWithFieldProvider() {
        return [
            'text'                            => ['text', 0, 0, 0],
            'text with rule'                  => ['text', 1, 0, 0],
            'text with value'                 => ['text', 0, 1, 0],
            'text with help'                  => ['text', 0, 0, 1],
            'text with rule, value'           => ['text', 1, 1, 0],
            'text with rule, help'            => ['text', 1, 0, 1],
            'text with value, help'           => ['text', 0, 1, 1],
            'text with all'                   => ['text', 1, 1, 1],
            'number'                          => ['number', 0, 0, 0],
            'number with rule'                => ['number', 1, 0, 0],
            'number with value'               => ['number', 0, 1, 0],
            'number with help'                => ['number', 0, 0, 1],
            'number with rule, value'         => ['number', 1, 1, 0],
            'number with rule, help'          => ['number', 1, 0, 1],
            'number with value, help'         => ['number', 0, 1, 1],
            'number with all'                 => ['number', 1, 1, 1],
            'checkbox'                        => ['checkbox', 0, 0, 0],
            'checkbox with rule'              => ['checkbox', 1, 0, 0],
            'checkbox with value'             => ['checkbox', 0, 1, 0],
            'checkbox with help'              => ['checkbox', 0, 0, 1],
            'checkbox with rule, value'       => ['checkbox', 1, 1, 0],
            'checkbox with rule, help'        => ['checkbox', 1, 0, 1],
            'checkbox with value, help'       => ['checkbox', 0, 1, 1],
            'checkbox with all'               => ['checkbox', 1, 1, 1],
            'choose one'                      => ['choice', 0, 0, 0],
            'choose one with rule'            => ['choice', 1, 0, 0],
            'choose one with help'            => ['choice', 0, 0, 1],
            'choose one with rule, help'      => ['choice', 1, 0, 1],
            'choose multiple'                 => ['multiple', 0, 0, 0],
            'choose multiple with rule'       => ['multiple', 1, 0, 0],
            'choose multiple with help'       => ['multiple', 0, 0, 1],
            'choose multiple with rule, help' => ['multiple', 1, 0, 1],
        ];
    }

    /**
     * Test page creation with an infobox field.
     *
     * @dataProvider postPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withInput
     * @param bool   $expected
     */
    public function testPostCreatePageWithInfoboxField($fieldType, $withRule, $withValue, $withInput, $expected) {
        $this->postPageWithField(0, 'infobox', $fieldType, $withRule, $withValue, $withInput, $expected);
    }

    /**
     * Test page editing with an infobox field.
     *
     * @dataProvider postPageWithFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withInput
     * @param bool   $expected
     */
    public function testPostEditPageWithInfoboxField($fieldType, $withRule, $withValue, $withInput, $expected) {
        $this->postPageWithField(1, 'infobox', $fieldType, $withRule, $withValue, $withInput, $expected);
    }

    /**
     * Test page creation with a main body field.
     *
     * @dataProvider postPageWithFieldProvider
     * @dataProvider postPageWithTemplateFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withInput
     * @param bool   $expected
     */
    public function testPostCreatePageWithTemplateField($fieldType, $withRule, $withValue, $withInput, $expected) {
        $this->postPageWithField(0, 'body', $fieldType, $withRule, $withValue, $withInput, $expected);
    }

    /**
     * Test page editing with a main body field.
     *
     * @dataProvider postPageWithFieldProvider
     * @dataProvider postPageWithTemplateFieldProvider
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withInput
     * @param bool   $expected
     */
    public function testPostEditPageWithTemplateField($fieldType, $withRule, $withValue, $withInput, $expected) {
        $this->postPageWithField(1, 'body', $fieldType, $withRule, $withValue, $withInput, $expected);
    }

    public static function postPageWithFieldProvider() {
        return [
            'text'                             => ['text', 0, 0, 0, 1],
            'text with input'                  => ['text', 0, 0, 1, 1],
            'text with rule'                   => ['text', 1, 0, 0, 0],
            'text with rule, input'            => ['text', 1, 0, 1, 1],
            'text with value'                  => ['text', 0, 1, 0, 1],
            'text with value, rule'            => ['text', 1, 1, 0, 1],
            'text with value, input'           => ['text', 0, 1, 1, 1],
            'text with all'                    => ['text', 1, 1, 1, 1],
            'number'                           => ['number', 0, 0, 0, 1],
            'number with input'                => ['number', 0, 0, 1, 1],
            'number with rule'                 => ['number', 1, 0, 0, 0],
            'number with rule, input'          => ['number', 1, 0, 1, 1],
            'number with value'                => ['number', 0, 1, 0, 1],
            'number with value, rule'          => ['number', 1, 1, 0, 1],
            'number with value, input'         => ['number', 0, 1, 1, 1],
            'number with all'                  => ['number', 1, 1, 1, 1],
            'checkbox'                         => ['checkbox', 0, 0, 0, 1],
            'checkbox with input'              => ['checkbox', 0, 0, 1, 1],
            'checkbox with rule'               => ['checkbox', 1, 0, 0, 0],
            'checkbox with rule, input'        => ['checkbox', 1, 0, 1, 1],
            'checkbox with value'              => ['checkbox', 0, 1, 0, 1],
            'checkbox with value, rule'        => ['checkbox', 1, 1, 0, 1],
            'checkbox with value, input'       => ['checkbox', 0, 1, 1, 1],
            'checkbox with all'                => ['checkbox', 1, 1, 1, 1],
            'choose one'                       => ['choice', 0, 0, 0, 1],
            'choose one with input'            => ['choice', 0, 0, 1, 1],
            'choose one with rule'             => ['choice', 1, 0, 0, 0],
            'choose one with rule, input'      => ['choice', 1, 0, 1, 1],
            'choose multiple'                  => ['multiple', 0, 0, 0, 1],
            'choose multiple with input'       => ['multiple', 0, 0, 1, 1],
            'choose multiple with rule'        => ['multiple', 1, 0, 0, 0],
            'choose multiple with rule, input' => ['multiple', 1, 0, 1, 1],
        ];
    }

    public static function postPageWithTemplateFieldProvider() {
        return [
            'textbox'                   => ['textarea', 0, 0, 0, 1],
            'textbox with input'        => ['textarea', 0, 0, 1, 1],
            'textbox with rule'         => ['textarea', 1, 0, 0, 0],
            'textbox with rule, input'  => ['textarea', 1, 0, 1, 1],
            'textbox with value'        => ['textarea', 0, 1, 0, 1],
            'textbox with value, rule'  => ['textarea', 1, 1, 0, 1],
            'textbox with value, input' => ['textarea', 0, 1, 1, 1],
            'textbox with all'          => ['textarea', 1, 1, 1, 1],
        ];
    }

    /**
     * Unified function for testing page create/edit access with a template field.
     *
     * @param bool   $isEdit
     * @param string $fieldLocation
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    private function getPageWithField($isEdit, $fieldLocation, $fieldType, $withRule, $withValue, $withHelp) {
        // Set up some data for the field
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
            'help'  => $withHelp ? $this->faker->unique()->domainWord() : null,
        ];

        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 0; $i <= 1; $i++) {
                $fieldData['options'][$i] = $this->faker->unique()->domainWord();
            }
        }

        if ($withValue) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $fieldData['value'] = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $fieldData['value'] = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $fieldData['value'] = 1;
                    break;
            }
        }

        // Create a category with the relevant data directly
        if ($fieldLocation == 'infobox') {
            $category = SubjectCategory::factory()->infoboxField(
                $fieldData['key'],
                $fieldData['label'],
                $fieldType,
                $withRule ? 'required' : null,
                $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
                $withValue ? $fieldData['value'] : null,
                $withHelp ? $fieldData['help'] : null,
            )->create();
        } else {
            $category = SubjectCategory::factory()->bodyField(
                $fieldData['key'],
                $fieldData['label'],
                $fieldType,
                $withRule ? 'required' : null,
                $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
                $withValue ? $fieldData['value'] : null,
                $withHelp ? $fieldData['help'] : null,
            )->create();
        }

        if ($isEdit) {
            $page = Page::factory()->category($category->id)->create();
        }

        $response = $this->actingAs($this->editor)
            ->get($isEdit ? '/pages/'.$page->id.'/edit' : '/pages/create/'.$category->id)
            ->assertStatus(200);

        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            foreach ($fieldData['options'] as $option) {
                $response->assertSee($option);
            }
        }

        if ($withValue) {
            $response->assertSee($fieldData['value']);
        }

        if ($withHelp) {
            $response->assertSee($fieldData['help']);
        }
    }

    /**
     * Unified function for testing page creation/editing with a template field.
     *
     * @param bool   $isEdit
     * @param string $fieldLocation
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withInput
     * @param bool   $expected
     */
    private function postPageWithField($isEdit, $fieldLocation, $fieldType, $withRule, $withValue, $withInput, $expected) {
        // Set up some data for the field
        // While most of this isn't visible in the data saved for a page,
        // it impacts behavior around/during editing
        $fieldData = [
            'key'   => $this->faker->unique()->domainWord(),
            'label' => $this->faker->unique()->domainWord(),
        ];

        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 0; $i <= 1; $i++) {
                $fieldData['options'][$i] = $this->faker->unique()->domainWord();
            }
        }

        if ($withValue) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $fieldData['value'] = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $fieldData['value'] = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $fieldData['value'] = 1;
                    break;
            }
        }

        // Create a category with the relevant data directly
        if ($fieldLocation == 'infobox') {
            $category = SubjectCategory::factory()->infoboxField(
                $fieldData['key'],
                $fieldData['label'],
                $fieldType,
                $withRule ? 'required' : null,
                $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
                $withValue ? $fieldData['value'] : null
            )->create();
        } else {
            $category = SubjectCategory::factory()->bodyField(
                $fieldData['key'],
                $fieldData['label'],
                $fieldType,
                $withRule ? 'required' : null,
                $fieldType == 'choice' || $fieldType == 'multiple' ? json_encode($fieldData['options']) : null,
                $withValue ? $fieldData['value'] : null
            )->create();
        }

        if ($isEdit) {
            $page = Page::factory()->category($category->id)->create();
        }

        // Set up some data for the page itself
        $data = [
            'title'   => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
            'summary' => null,
        ] + (!$isEdit ? [
            'category_id' => $category->id,
        ] : []);

        if ($withInput) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $data[$fieldData['key']] = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $data[$fieldData['key']] = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'choice':
                    $data[$fieldData['key']] = 1;
                    break;
                case 'multiple':
                    $data[$fieldData['key']] = [0 => '1', 1 => '1'];
                    break;
            }
        } elseif ($withValue) {
            $data[$fieldData['key']] = $fieldData['value'];
        } else {
            $data[$fieldData['key']] = null;
        }

        if ($withInput || $withValue) {
            if (is_numeric($data[$fieldData['key']])) {
                $inputString = $data[$fieldData['key']];
            } elseif (is_array($data[$fieldData['key']])) {
                $inputString = json_encode($data[$fieldData['key']]);
            } else {
                $inputString = '"'.$data[$fieldData['key']].'"';
            }
        }

        $response = $this
            ->actingAs($this->editor)
            ->post($isEdit ? '/pages/'.$page->id.'/edit' : '/pages/create', $data);

        if ($expected) {
            $response->assertSessionHasNoErrors();

            if (!$isEdit) {
                $page = Page::where('title', $data['title'])->where('category_id', $category->id)->first();
            }

            $this->assertDatabaseHas('page_versions', [
                'page_id' => $page->id,
                'data'    => '{"data":{"description":null,"'.$fieldData['key'].'":'.($withInput || $withValue ? $inputString : 'null').',"parsed":{"description":null,"'.$fieldData['key'].'":'.($withInput || $withValue ? $inputString : 'null').'}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
            ]);
        } else {
            $response->assertSessionHasErrors();

            if ($isEdit) {
                $this->assertDatabaseMissing('page_versions', [
                    'page_id' => $page->id,
                    'data'    => '{"data":{"description":null,"'.$fieldData['key'].'":'.($withInput || $withValue ? $inputString : 'null').',"parsed":{"description":null,"'.$fieldData['key'].'":'.($withInput || $withValue ? $inputString : 'null').'}},"title":"'.$data['title'].'","is_visible":0,"summary":null,"utility_tag":null,"page_tag":null}',
                ]);
            } else {
                $this->assertDatabaseMissing('pages', [
                    'title'       => $data['title'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
