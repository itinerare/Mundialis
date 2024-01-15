<?php

namespace Database\Factories\Page;

use App\Models\Page\PagePageImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagePageImageFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagePageImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'is_valid' => 1,
        ];
    }

    /**
     * Generate a page-image link for a specific page.
     * This is essentially required.
     *
     * @param int $page
     *
     * @return Factory
     */
    public function page($page) {
        return $this->state(function (array $attributes) use ($page) {
            return [
                'page_id' => $page,
            ];
        });
    }

    /**
     * Generate a page-image link for a specific image.
     * This is essentially required.
     *
     * @param int $image
     *
     * @return Factory
     */
    public function image($image) {
        return $this->state(function (array $attributes) use ($image) {
            return [
                'page_image_id' => $image,
            ];
        });
    }

    /**
     * Generate an invalid page-image link.
     *
     * @return Factory
     */
    public function invalid() {
        return $this->state(function (array $attributes) {
            return [
                'is_valid' => 0,
            ];
        });
    }
}
