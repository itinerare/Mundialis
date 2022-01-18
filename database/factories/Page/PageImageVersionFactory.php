<?php

namespace Database\Factories\Page;

use App\Models\Page\PageImageVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageImageVersionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageImageVersion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'hash' => randomString(15),
            'extension' => 'png',
            'use_cropper' => 0,
            'x0' => 0, 'x1' => 0,
            'y0' => 0, 'y1' => 0,
            'type' => 'Image Created',
            'reason' => null,
            'is_minor' => 0,
        ];
    }

    /**
     * Generate a version for a specific image.
     * This is essentially required.
     *
     * @param  int                      $image
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function image($image)
    {
        return $this->state(function (array $attributes) use ($image) {
            return [
                'page_image_id' => $image,
            ];
        });
    }

    /**
     * Generate a version by a specific user.
     * This is essentially required.
     *
     * @param  int                      $user
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function user($user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user,
            ];
        });
    }

    /**
     * Generate a version of a specific type.
     *
     * @param  string                  $type
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function type($type)
    {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    /**
     * Generate a version with a specific reason.
     *
     * @param  string                  $reason
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function reason($reason)
    {
        return $this->state(function (array $attributes) use ($reason) {
            return [
                'reason' => $reason,
            ];
        });
    }

    /**
     * Mark a version as minor.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function minor()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_minor' => 1,
            ];
        });
    }

    /**
     * Generate a version for a page deletion.
     *
     * @param  string                  $reason
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Image Deleted',
            ];
        });
    }

    /**
     * Generate an image version with some data.
     *
     * @param  bool                        $isVisible
     * @param  string                      $description
     * @param  string                      $creators
     * @param  string                      $pages
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function testData($isVisible = 1, $description = null, $creators = '1', $pages = '1')
    {
        return $this->state(function (array $attributes) use ($isVisible, $description, $creators, $pages) {
            return [
                'data' => '{"is_visible":"' . $isVisible . '","description":' . ($description ? '"' . $description . '"' : null) . ',"creators":[' . $creators . '],"pages":[' . $pages . ']}',
            ];
        });
    }
}
