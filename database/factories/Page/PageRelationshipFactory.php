<?php

namespace Database\Factories\Page;

use App\Models\Page\PageRelationship;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageRelationshipFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageRelationship::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'type_one' => 'platonic_friend',
            'type_two' => 'platonic_friend',
        ];
    }

    /**
     * Generate a relationship for a specific page.
     * This is essentially required.
     *
     * @param int $page
     *
     * @return Factory
     */
    public function pageOne($page) {
        return $this->state(function (array $attributes) use ($page) {
            return [
                'page_one_id' => $page,
            ];
        });
    }

    /**
     * Generate a relationship for a specific page.
     * This is essentially required.
     *
     * @param int $page
     *
     * @return Factory
     */
    public function pageTwo($page) {
        return $this->state(function (array $attributes) use ($page) {
            return [
                'page_two_id' => $page,
            ];
        });
    }

    /**
     * Generate a familial relationship.
     *
     * @return Factory
     */
    public function familial() {
        return $this->state(function (array $attributes) {
            return [
                'type_one' => 'familial_parent',
                'type_two' => 'familial_child',
            ];
        });
    }
}
