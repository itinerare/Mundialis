<?php

namespace Database\Factories\Lexicon;

use App\Models\Lexicon\LexiconEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Subject\LexiconSetting;

class LexiconEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LexiconEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if(LexiconSetting::all()->first())
            $class = LexiconSetting::all()->first();
        else
            $class = LexiconSetting::create([
                'name' => $this->faker->unique()->domainWord(),
                'abbreviation' => $this->faker->unique()->domainWord()
            ]);

        return [
            'word' => $this->faker->unique()->domainWord(),
            'class' => $class->name,
            'meaning' => $this->faker->unique()->domainWord()
        ];
    }

    /**
     * Generate an entry in a specific category.
     *
     * @param  int                      $category
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function category($category)
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'category_id' => $category
            ];
        });
    }
}
