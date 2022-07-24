<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'is_unread' => 1,
        ];
    }

    /**
     * Generate a notification for a specific user.
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
     * Generate a watched page update notification.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function watchedPageUpdated($page = null, $user = null) {
        return $this->state(function (array $attributes) use ($page, $user) {
            return [
                'notification_type_id' => 0,
                'data'                 => json_encode([
                    'page_url'  => 'pages/'.($page ? $page->id : '1').'.'.($page ? $page->title : $this->faker->unique()->domainWord()),
                    'page_tile' => $page ? $page->title : $this->faker->unique()->domainWord(),
                    'user_url'  => 'user/'.($user ? $user->name : $this->faker->unique()->domainWord()),
                    'user_name' => $user ? $user->name : $this->faker->unique()->domainWord(),
                ]),
            ];
        });
    }

    /**
     * Generate a watched page image update notification.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function watchedPageImageUpdated($page = null, $user = null) {
        return $this->state(function (array $attributes) use ($page, $user) {
            return [
                'notification_type_id' => 1,
                'data'                 => json_encode([
                    'page_url'  => 'pages/'.($page ? $page->id : '1').'.'.($page ? $page->title : $this->faker->unique()->domainWord()),
                    'page_tile' => $page ? $page->title : $this->faker->unique()->domainWord(),
                    'user_url'  => 'user/'.($user ? $user->name : $this->faker->unique()->domainWord()),
                    'user_name' => $user ? $user->name : $this->faker->unique()->domainWord(),
                ]),
            ];
        });
    }

    /**
     * Generate a watched page delete notification.
     *
     * @param \App\Models\Page\Page $page
     * @param \App\Models\User\User $user
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function watchedPageDeleted($page = null, $user = null) {
        return $this->state(function (array $attributes) use ($page, $user) {
            return [
                'notification_type_id' => 2,
                'data'                 => json_encode([
                    'page_tile' => $page ? $page->title : $this->faker->unique()->domainWord(),
                    'user_url'  => 'user/'.($user ? $user->name : $this->faker->unique()->domainWord()),
                    'user_name' => $user ? $user->name : $this->faker->unique()->domainWord(),
                ]),
            ];
        });
    }
}
