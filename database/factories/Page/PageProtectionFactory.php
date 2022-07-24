<?php

namespace Database\Factories\Page;

use App\Models\Page\PageProtection;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageProtectionFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageProtection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'is_protected' => 1,
            'reason'       => null,
        ];
    }

    /**
     * Generate a protection record for a specific page.
     * This is essentially required.
     *
     * @param int $page
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function page($page) {
        return $this->state(function (array $attributes) use ($page) {
            return [
                'page_id' => $page,
            ];
        });
    }

    /**
     * Generate a protection record by a specific user.
     * This is essentially required.
     *
     * @param int $user
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function user($user) {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user,
            ];
        });
    }

    /**
     * Generate an unprotection record instead.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unprotected() {
        return $this->state(function (array $attributes) {
            return [
                'is_protected' => 0,
            ];
        });
    }
}
