<?php

namespace Database\Factories\Subject;

use App\Models\Subject\LexiconCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class LexiconCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LexiconCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->domainWord().$this->faker->unique()->domainWord()
        ];
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
                'data' => '{"1":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}}}}'
            ];
        });
    }

    /**
     * Generate a category with some fields set.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function extendedData()
    {
        return $this->state(function (array $attributes) {
            return [
                'data' => '{"1":{"properties":{"number":{"name":"Number","non_dimensional":0,"dimensions":["Singular","Plural"]},"case":{"name":"Case","non_dimensional":0,"dimensions":["Nominative","Accusative","Dative"]}},"conjugation":[{"criteria":["a"],"regex":["^"],"replacement":["b"]}]}}'
            ];
        });
    }
}
