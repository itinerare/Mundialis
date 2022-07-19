<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectDataFieldTest extends TestCase {
    use RefreshDatabase, WithFaker;

    /**
     * Test subject template editing with an infobox text field.
     */
    public function testCanPostEditTemplateWithInfoboxTextField() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox text field with a validation rule.
     */
    public function testCanPostEditTemplateWithInfoboxTextFieldWithRule() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => 'required'],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":"required","choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox text field with a default value.
     */
    public function testCanPostEditTemplateWithInfoboxTextFieldWithValue() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":null,"choices":null,"value":"'.$data['infobox_value'][0].'","help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox text field with a tooltip.
     */
    public function testCanPostEditTemplateWithInfoboxTextFieldWithHelp() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'text'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => $this->faker->unique()->domainWord()],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":"'.$data['infobox_help'][0].'"}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox number field.
     */
    public function testCanPostEditTemplateWithInfoboxNumberField() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'number'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"number","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox checkbox field.
     */
    public function testCanPostEditTemplateWithInfoboxCheckboxField() {
        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'checkbox'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"checkbox","rules":null,"choices":null,"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox choose one field.
     */
    public function testCanPostEditTemplateWithInfoboxChooseOneField() {
        for ($i = 1; $i <= 2; $i++) {
            $option[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'choice'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => $option[1].','.$option[2]],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"choice","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with an infobox choose multiple field.
     */
    public function testCanPostEditTemplateWithInfoboxChooseMultipleField() {
        for ($i = 1; $i <= 2; $i++) {
            $option[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic template data
        $data = [
            'section_key'     => [0 => $this->faker->unique()->domainWord()],
            'section_name'    => [0 => $this->faker->unique()->domainWord()],
            'infobox_key'     => [0 => $this->faker->unique()->domainWord()],
            'infobox_type'    => [0 => 'multiple'],
            'infobox_label'   => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules'   => [0 => null],
            'infobox_choices' => [0 => $option[1].','.$option[2]],
            'infobox_value'   => [0 => null],
            'infobox_help'    => [0 => null],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"multiple","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null}}}',
        ]);
    }

    /**
     * Test subject template editing with a text field.
     */
    public function testCanPostEditTemplateWithTextField() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'text'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a text field with a validation rule.
     */
    public function testCanPostEditTemplateWithTextFieldWithRule() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'text'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => 'required'],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":"required","choices":null,"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a text field with a default value.
     */
    public function testCanPostEditTemplateWithTextFieldWithValue() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'text'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => $this->faker->unique()->domainWord()],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":null,"choices":null,"value":"'.$data['field_value'][0].'","help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a text field with a tooltip.
     */
    public function testCanPostEditTemplateWithTextFieldWithHelp() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'text'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => null],
            'field_help'          => [0 => $this->faker->unique()->domainWord()],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":"'.$data['field_help'][0].'","is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a number field.
     */
    public function testCanPostEditTemplateWithNumberField() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'number'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"number","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a checkbox field.
     */
    public function testCanPostEditTemplateWithCheckboxField() {
        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'checkbox'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => null],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"checkbox","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a choose one field.
     */
    public function testCanPostEditTemplateWithChooseOneField() {
        for ($i = 1; $i <= 2; $i++) {
            $option[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'choice'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => $option[1].','.$option[2]],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"choice","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }

    /**
     * Test subject template editing with a choose multiple field.
     */
    public function testCanPostEditTemplateWithChooseMultipleField() {
        for ($i = 1; $i <= 2; $i++) {
            $option[$i] = $this->faker->unique()->domainWord();
        }

        // Define some basic template data
        $data = [
            'section_key'         => [0 => 'test_section'],
            'section_name'        => [0 => $this->faker->unique()->domainWord()],
            'field_key'           => [0 => $this->faker->unique()->domainWord()],
            'field_type'          => [0 => 'multiple'],
            'field_label'         => [0 => $this->faker->unique()->domainWord()],
            'field_rules'         => [0 => null],
            'field_choices'       => [0 => $option[1].','.$option[2]],
            'field_value'         => [0 => null],
            'field_help'          => [0 => null],
            'field_section'       => [0 => 'test_section'],
            'field_is_subsection' => [0 => 0],
        ];

        // Make a temporary admin
        $user = User::factory()->admin()->make();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/data/misc/edit', $data);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('subject_templates', [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"multiple","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null,"is_subsection":0}}}}',
        ]);
    }
}
