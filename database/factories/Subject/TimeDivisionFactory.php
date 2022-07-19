<?php

namespace Database\Factories\Subject;

use App\Models\Subject\TimeDivision;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeDivisionFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeDivision::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'name'         => $this->faker->unique()->domainWord(),
            'abbreviation' => $this->faker->unique()->domainWord(),
            'unit'         => mt_rand(1, 100),
        ];
    }

    /**
     * Generate a division for use in dates.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function date() {
        return $this->state(function (array $attributes) {
            return [
                'use_for_dates' => 1,
            ];
        });
    }
}
