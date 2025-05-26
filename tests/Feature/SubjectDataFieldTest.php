<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubjectDataFieldTest extends TestCase {
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->make();
    }

    /******************************************************************************
        SUBJECT / TEMPLATE FIELD EDITING
    *******************************************************************************/

    /**
     * Test subject template editing with an infobox field.
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    #[DataProvider('postEditFieldProvider')]
    public function testPostEditInfoboxField($fieldType, $withRule, $withValue, $withHelp) {
        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 1; $i <= 2; $i++) {
                $option[$i] = $this->faker->unique()->domainWord();
            }
        }

        if ($withValue) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $value = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $value = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $value = 1;
                    break;
                case 'choice': case 'multiple':
                    $value = 2;
                    break;
            }
        }

        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => $fieldType],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => $withRule ? 'required' : null],
            'infobox_choices' => [0 => $fieldType == 'choice' || $fieldType == 'multiple' ? $option[1].','.$option[2] : null],
            'infobox_value'   => [0 => $value ?? null],
            'infobox_help'    => [0 => $withHelp ? $this->faker->unique()->domainWord() : null],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/misc/edit', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"'.$fieldType.'","rules":'.($withRule ? '"'.$data['infobox_rules'][0].'"' : 'null').',"choices":'.($fieldType == 'choice' || $fieldType == 'multiple' ? '["'.$option[1].'","'.$option[2].'"]' : 'null').',"value":'.($withValue ? (is_numeric($value) ? $data['infobox_value'][0] : '"'.$data['infobox_value'][0].'"') : 'null').',"help":'.($withHelp ? '"'.$data['infobox_help'][0].'"' : 'null').'}}}',
        ]);
    }

    /**
     * Test subject template editing with a main body field.
     *
     * @param string $fieldType
     * @param bool   $withRule
     * @param bool   $withValue
     * @param bool   $withHelp
     */
    #[DataProvider('postEditFieldProvider')]
    #[DataProvider('postEditTemplateFieldProvider')]
    public function testPostEditTemplateField($fieldType, $withRule, $withValue, $withHelp) {
        if ($fieldType == 'choice' || $fieldType == 'multiple') {
            for ($i = 1; $i <= 2; $i++) {
                $option[$i] = $this->faker->unique()->domainWord();
            }
        }

        if ($withValue) {
            switch ($fieldType) {
                case 'text': case 'textarea':
                    $value = $this->faker->unique()->domainWord();
                    break;
                case 'number':
                    $value = mt_rand(1, 50);
                    break;
                case 'checkbox':
                    $value = 1;
                    break;
                case 'choice': case 'multiple':
                    $value = 2;
                    break;
            }
        }

        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => $fieldType],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => $withRule ? 'required' : null],
            'field_choices'       => [0 => $fieldType == 'choice' || $fieldType == 'multiple' ? $option[1].','.$option[2] : null],
            'field_value'         => [0 => $value ?? null],
            'field_help'          => [0 => $withHelp ? $this->faker->unique()->domainWord() : null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/data/misc/edit', $data);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"'.$fieldType.'","rules":'.($withRule ? '"'.$data['field_rules'][0].'"' : 'null').',"choices":'.($fieldType == 'choice' || $fieldType == 'multiple' ? '["'.$option[1].'","'.$option[2].'"]' : 'null').',"value":'.($withValue ? (is_numeric($value) ? $data['field_value'][0] : '"'.$data['field_value'][0].'"') : 'null').',"help":'.($withHelp ? '"'.$data['field_help'][0].'"' : 'null').',"is_subsection":0}}}}',
        ]);
    }

    public static function postEditFieldProvider() {
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

    public static function postEditTemplateFieldProvider() {
        return [
            'textbox'                  => ['textarea', 0, 0, 0],
            'textbox with rule'        => ['textarea', 1, 0, 0],
            'textbox with value'       => ['textarea', 0, 1, 0],
            'textbox with help'        => ['textarea', 0, 0, 1],
            'textbox with rule, value' => ['textarea', 1, 1, 0],
            'textbox with rule, help'  => ['textarea', 1, 0, 1],
            'textbox with value, help' => ['textarea', 0, 1, 1],
            'textbox with all'         => ['textarea', 1, 1, 1],
        ];
    }
}
