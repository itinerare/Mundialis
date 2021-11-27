<?php

namespace Tests\Feature;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectDataFieldTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test subject template editing with an infobox text field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxTextField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with an infobox text field with a validation rule.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxTextFieldWithRule()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'text'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => 'required'],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"text","rules":"required","choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with an infobox number field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxNumberField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'number'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"number","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with an infobox checkbox field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxCheckboxField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'checkbox'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => null],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"checkbox","rules":null,"choices":null,"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with an infobox choose one field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxChooseOneField()
    {
        for($i = 1; $i <= 2; $i++)
            $option[$i] = $this->faker->unique()->domainWord();

        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'choice'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => $option[1].','.$option[2]],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"choice","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with an infobox choose multiple field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithInfoboxChooseMultipleField()
    {
        for($i = 1; $i <= 2; $i++)
            $option[$i] = $this->faker->unique()->domainWord();

        // Define some basic template data
        $data = [
            'section_key' => [0 => $this->faker->unique()->domainWord()],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'infobox_key' => [0 => $this->faker->unique()->domainWord()],
            'infobox_type' => [0 => 'multiple'],
            'infobox_label' => [0 => $this->faker->unique()->domainWord()],
            'infobox_rules' => [0 => null],
            'infobox_choices' => [0 => $option[1].','.$option[2]],
            'infobox_value' => [0 => null],
            'infobox_help' => [0 => null]
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
            'data' => '{"sections":{"'.$data['section_key'][0].'":{"name":"'.$data['section_name'][0].'"}},"infobox":{"'.$data['infobox_key'][0].'":{"label":"'.$data['infobox_label'][0].'","type":"multiple","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null}}}'
        ]);
    }

    /**
     * Test subject template editing with a text field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithTextField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'text'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => null],
            'field_choices' => [0 => null],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }

    /**
     * Test subject template editing with a text field with a validation rule.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithTextFieldWithRule()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'text'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => 'required'],
            'field_choices' => [0 => null],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"text","rules":"required","choices":null,"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }

    /**
     * Test subject template editing with a number field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithNumberField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'number'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => null],
            'field_choices' => [0 => null],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"number","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }

    /**
     * Test subject template editing with a checkbox field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithCheckboxField()
    {
        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'checkbox'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => null],
            'field_choices' => [0 => null],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"checkbox","rules":null,"choices":null,"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }

    /**
     * Test subject template editing with a choose one field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithChooseOneField()
    {
        for($i = 1; $i <= 2; $i++)
            $option[$i] = $this->faker->unique()->domainWord();

        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'choice'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => null],
            'field_choices' => [0 => $option[1].','.$option[2]],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"choice","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }

    /**
     * Test subject template editing with a choose multiple field.
     *
     * @return void
     */
    public function test_canPostEditTemplateWithChooseMultipleField()
    {
        for($i = 1; $i <= 2; $i++)
            $option[$i] = $this->faker->unique()->domainWord();

        // Define some basic template data
        $data = [
            'section_key' => [0 => 'test_section'],
            'section_name' => [0 => $this->faker->unique()->domainWord()],
            'field_key' => [0 => $this->faker->unique()->domainWord()],
            'field_type' => [0 => 'multiple'],
            'field_label' => [0 => $this->faker->unique()->domainWord()],
            'field_rules' => [0 => null],
            'field_choices' => [0 => $option[1].','.$option[2]],
            'field_value' => [0 => null],
            'field_help' => [0 => null],
            'field_section' => [0 => 'test_section'],
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
            'data' => '{"sections":{"test_section":{"name":"'.$data['section_name'][0].'"}},"fields":{"test_section":{"'.$data['field_key'][0].'":{"label":"'.$data['field_label'][0].'","type":"multiple","rules":null,"choices":["'.$option[1].'","'.$option[2].'"],"value":null,"help":null,"is_subsection":0}}}}'
        ]);
    }
}
