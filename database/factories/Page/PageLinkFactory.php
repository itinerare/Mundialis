<?php

namespace Database\Factories\Page;

use App\Models\Page\PageLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageLink::class;

    /**
     * Define the model's default state.
     * As-is, only pages can be linked to, so this assumes as much.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'parent_type' => 'page',
            'linked_type' => 'page',
        ];
    }

    /**
     * Generate a link for a specific parent.
     * This is essentially required.
     *
     * @param  int                      $parent
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function parent($parent)
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'parent_id' => $parent,
            ];
        });
    }

    /**
     * Generate a link for a specific target.
     *
     * @param  int                      $link
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function link($link)
    {
        return $this->state(function (array $attributes) use ($link) {
            return [
                'link_id' => $link,
            ];
        });
    }

    /**
     * Generate a link from a lexicon entry.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function entry()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_type' => 'entry',
            ];
        });
    }

    /**
     * Generate a link for a wanted page.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function wanted()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => $this->faker->unique()->domainWord(),
            ];
        });
    }
}
