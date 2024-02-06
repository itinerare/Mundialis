<?php

namespace Database\Factories\Page;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PageTagFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            //
            'type' => 'page_tag',
            'tag'  => $this->faker->unique()->domainWord(),
        ];
    }

    /**
     * Generate a tag for a specific page.
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
     * Generate a specific kind of tag.
     *
     * @param string $type
     *
     * @return Factory
     */
    public function type($type) {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
                'tag'  => $type == 'page_tag' ? $this->faker->unique()->domainWord() : 'wip',
            ];
        });
    }
}
