<?php

namespace Database\Factories\Page;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject\SubjectCategory;
use App\Models\Page\Page;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = SubjectCategory::factory()->create();

        return [
            'title' => $this->faker->unique()->domainWord() . $this->faker->unique()->domainWord(),
            'category_id' => $category->id,
            'is_visible' => 1,
        ];
    }

    /**
     * Generate a page in a specific category.
     *
     * @param  int                      $category
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function category($category)
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'category_id' => $category,
            ];
        });
    }

    /**
     * Generate a hidden page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hidden()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_visible' => 0,
            ];
        });
    }

    /**
     * Generate a deleted page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => Carbon::now(),
            ];
        });
    }
}
