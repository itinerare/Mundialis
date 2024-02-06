<?php

namespace Database\Factories\Page;

use App\Models\Page\PageImageCreator;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageImageCreatorFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageImageCreator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
        ];
    }

    /**
     * Generate a creator for a specific image.
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
     * Generate a creator record for a specific user.
     * This is semi-required.
     *
     * @param int $user
     *
     * @return Factory
     */
    public function user($user) {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user,
            ];
        });
    }

    /**
     * Generate a creator record for a specific url.
     * This is semi-required.
     *
     * @param int $url
     *
     * @return Factory
     */
    public function url($url) {
        return $this->state(function (array $attributes) use ($url) {
            return [
                'url' => $url,
            ];
        });
    }
}
