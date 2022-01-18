<?php

namespace Database\Factories\Page;

use App\Models\Page\PageImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'description' => null,
            'is_visible'  => 1,
        ];
    }

    /**
     * Generate a hidden image.
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
     * Generate a deleted image.
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
