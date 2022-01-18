<?php

namespace Database\Factories\Subject;

use App\Models\Subject\TimeChronology;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeChronologyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeChronology::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->domainWord() . $this->faker->unique()->domainWord(),
        ];
    }
}
