<?php

namespace Database\Factories\Subject;

use App\Models\Subject\SubjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubjectCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => 'misc',
            'name'    => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord(),
        ];
    }

    /**
     * Generate a category in a specific subject.
     *
     * @param string $subject
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function subject($subject)
    {
        return $this->state(function (array $attributes) use ($subject) {
            return [
                'subject' => $subject,
            ];
        });
    }

    /**
     * Generate a category with some fields set.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function testData()
    {
        return $this->state(function (array $attributes) {
            return [
                'data' => '{"sections":{"test_category_section":{"name":"Test Category Section"}},"infobox":{"test_category_field":{"label":"Test Category Field","type":"text","rules":null,"choices":null,"value":null,"help":null}}}',
            ];
        });
    }

    /**
     * Generate a category with just a specific infobox field.
     *
     * @param string $key
     * @param string $type
     * @param string $rules
     * @param string $choices
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function infoboxField($key = 'test', $label = 'Test', $type = 'text', $rules = null, $choices = null, $value = null)
    {
        return $this->state(function (array $attributes) use ($key, $label, $type, $rules, $choices, $value) {
            return [
                'data' => '{"infobox":{"'.$key.'":{"label":"'.$label.'","type":"'.$type.'","rules":'.($rules ? '"'.$rules.'"' : 'null').',"choices":'.($choices ? $choices : 'null').',"value":'.($value ? '"'.$value.'"' : 'null').',"help":null}}}',
            ];
        });
    }

    /**
     * Generate a category with just a specific body field.
     *
     * @param string $key
     * @param string $type
     * @param string $rules
     * @param string $choices
     * @param string $value
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function bodyField($key = 'test', $label = 'Test', $type = 'text', $rules = null, $choices = null, $value = null)
    {
        return $this->state(function (array $attributes) use ($key, $label, $type, $rules, $choices, $value) {
            return [
                'data' => '{"sections":{"section":{"name":"Test Section"}},"fields":{"section":{"'.$key.'":{"label":"'.$label.'","type":"'.$type.'","rules":'.($rules ? '"'.$rules.'"' : 'null').',"choices":'.($choices ? $choices : 'null').',"value":'.($value ? '"'.$value.'"' : 'null').',"help":null,"is_subsection":"0"}}}}',
            ];
        });
    }
}
