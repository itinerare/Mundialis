<?php

namespace Database\Factories\Subject;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SubjectTemplateFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'subject' => 'misc',
            'data'    => '{"sections":{"test_subject_section":{"name":"Test Subject Section"}},"infobox":{"test_subject_field":{"label":"Test Subject Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
        ];
    }

    /**
     * Generate a template for a specific subject.
     *
     * @param string $subject
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function subject($subject) {
        return $this->state(function (array $attributes) use ($subject) {
            return [
                'subject' => $subject,
            ];
        });
    }
}
