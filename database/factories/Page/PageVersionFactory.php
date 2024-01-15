<?php

namespace Database\Factories\Page;

use App\Models\Page\PageVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageVersionFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageVersion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            //
            'type'     => 'Page Created',
            'reason'   => null,
            'is_minor' => 0,
        ];
    }

    /**
     * Generate a version for a specific page.
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
     * Generate a version by a specific user.
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

    /**
     * Generate a version of a specific type.
     *
     * @param string $type
     *
     * @return Factory
     */
    public function type($type) {
        return $this->state(function (array $attributes) use ($type) {
            return [
                'type' => $type,
            ];
        });
    }

    /**
     * Generate a version with a specific reason.
     *
     * @param string $reason
     *
     * @return Factory
     */
    public function reason($reason) {
        return $this->state(function (array $attributes) use ($reason) {
            return [
                'reason' => $reason,
            ];
        });
    }

    /**
     * Mark a version as minor.
     *
     * @return Factory
     */
    public function minor() {
        return $this->state(function (array $attributes) {
            return [
                'is_minor' => 1,
            ];
        });
    }

    /**
     * Generate a version for a page deletion.
     *
     * @return Factory
     */
    public function deleted() {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Page Deleted',
            ];
        });
    }

    /**
     * Generate a page with some data to match category test data.
     *
     * @param string $title
     * @param string $summary
     * @param string $utilityTags
     * @param string $pageTags
     * @param string $division
     *
     * @return Factory
     */
    public function testData($title = 'Test', $summary = null, $utilityTags = null, $pageTags = null, $division = null) {
        return $this->state(function (array $attributes) use ($title, $summary, $utilityTags, $pageTags, $division) {
            return [
                'data' => '{"data":{"description":null,"test_category_field":"test field answer",'.($division ? '"date":{"start":{"'.$division.'":"'.mt_rand(1, 50).'"},"end":{"'.$division.'":"'.mt_rand(50, 100).'"}},' : '').'"parsed":{"description":null,'.($division ? '"date":{"start":{"'.$division.'":"'.mt_rand(1, 50).'"},"end":{"'.$division.'":"'.mt_rand(50, 100).'"}},' : '').'"test_category_field":"test field answer"}},"title":"'.$title.'","is_visible":"1","summary":'.($summary ? '"'.$summary.'"' : null).',"utility_tag":'.($utilityTags ? '"['.$utilityTags.']"' : null).',"page_tag":'.($pageTags ? '"['.$pageTags.']"' : null).'}',
            ];
        });
    }
}
