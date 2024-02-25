<?php

namespace Database\Factories\User;

use App\Models\User\WatchedPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchedPageFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WatchedPage::class;

    /**
     * Define the model's default state.
     * In this case, it has none and is entirely defined by states.
     *
     * @return array
     */
    public function definition() {
        return [
            //
        ];
    }

    /**
     * Generate a watch for a specific page.
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
     * Generate a watch by a specific user.
     * This is essentially required.
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
}
