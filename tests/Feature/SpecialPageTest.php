<?php

namespace Tests\Feature;

use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageCreator;
use App\Models\Page\PageImageVersion;
use App\Models\Page\PageLink;
use App\Models\Page\PagePageImage;
use App\Models\Page\PageProtection;
use App\Models\Page\PageTag;
use App\Models\Page\PageVersion;
use App\Models\User\User;
use App\Services\ImageManager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecialPageTest extends TestCase {
    use RefreshDatabase, WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->editor = User::factory()->editor()->create();

        // Delete any pages/page links present due to other tests
        if (Page::query()->count()) {
            Page::query()->delete();
        }
        if (PageLink::query()->count()) {
            PageLink::query()->delete();
        }
    }

    /******************************************************************************
        MAINTENANCE REPORTS
    *******************************************************************************/

    /**
     * Tests all special pages access.
     */
    public function testGetSpecialPages() {
        $this->get('/special')
            ->assertStatus(200);
    }

    /**
     * Tests untagged pages access.
     *
     * @dataProvider getTaggedPagesProvider
     *
     * @param int  $pages
     * @param int  $tagged
     * @param bool $isVisible
     */
    public function testGetUntaggedPages($pages, $tagged, $isVisible) {
        if ($pages) {
            for ($i = 1; $i <= $pages; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $tagged && $tagged >= $i ? 1 : $isVisible,
                ]);
                $version[$i] = PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                if ($tagged && $tagged >= $i) {
                    PageTag::factory()->page($page[$i]->id)->create();
                }
            }
        }

        $response = $this->get('/special/untagged-pages')
            ->assertStatus(200);

        if ($pages) {
            if ($pages > $tagged && $isVisible) {
                // At least one page should be listed
                $response->assertSeeText($page[$pages]->title);

                if ($tagged) {
                    $response->assertDontSeeText($page[$tagged]->title);
                }
            } else {
                foreach ($page as $taggedPage) {
                    $response->assertDontSeeText($taggedPage->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    /**
     * Tests most tagged pages access.
     *
     * @dataProvider getTaggedPagesProvider
     *
     * @param int  $pages
     * @param int  $tagged
     * @param bool $isVisible
     */
    public function testGetMostTaggedPages($pages, $tagged, $isVisible) {
        if ($pages) {
            for ($i = 1; $i <= $pages; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $tagged && $tagged >= $i ? $isVisible : 1,
                ]);
                $version[$i] = PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                if ($tagged && $tagged >= $i) {
                    PageTag::factory()->page($page[$i]->id)->create();
                }
            }
        }

        $response = $this->get('/special/tagged-pages')
            ->assertStatus(200);

        if ($pages) {
            if ($isVisible) {
                foreach ($page as $key=>$taggedPage) {
                    if ($tagged && $key <= $tagged) {
                        // At least one page should be listed
                        $response->assertSeeText($taggedPage->title);
                    }
                }

                if ($pages > $tagged) {
                    $response->assertDontSeeText($page[$pages]->title);
                }
            } else {
                foreach ($page as $taggedPage) {
                    $response->assertDontSeeText($taggedPage->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getTaggedPagesProvider() {
        return [
            'basic'                => [0, 0, 0],
            'untagged page'        => [1, 0, 1],
            'hidden untagged page' => [1, 0, 0],
            'tagged page'          => [1, 1, 1],
            'hidden tagged page'   => [1, 1, 0],
            'both'                 => [2, 1, 1],
            'both hidden'          => [2, 1, 1],
        ];
    }

    /**
     * Tests least revised pages access.
     *
     * @dataProvider getRevisedPagesProvider
     *
     * @param bool $withPages
     * @param bool $isVisible
     */
    public function testGetLeastRevisedPages($withPages, $isVisible) {
        if ($withPages) {
            // By default, the pagination is set to 20 results per page
            // so create 21 pages so that one will by hidden due to ordering
            for ($i = 1; $i <= 21; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $isVisible,
                ]);
                PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                for ($i2 = 21; $i2 >= $i; $i2--) {
                    if ($i > 1) {
                        PageVersion::factory()->user($this->editor->id)->page($page[$i - 1]->id)->create();
                    }
                }
            }
        }

        $response = $this->get('/special/least-revised-pages')
            ->assertStatus(200);

        if ($withPages) {
            for ($i = 21; $i >= 1; $i--) {
                if ($i > 1 && $isVisible) {
                    $response->assertSeeText($page[$i]->title);
                } else {
                    $response->assertDontSeeText($page[$i]->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    /**
     * Tests most revised pages access.
     *
     * @dataProvider getRevisedPagesProvider
     *
     * @param bool $withPages
     * @param bool $isVisible
     */
    public function testGetMostRevisedPages($withPages, $isVisible) {
        if ($withPages) {
            // By default, the pagination is set to 20 results per page
            // so create 21 pages so that one will by hidden due to ordering
            for ($i = 1; $i <= 21; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $isVisible,
                ]);
                PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                for ($i2 = 21; $i2 >= $i; $i2--) {
                    if ($i > 1) {
                        PageVersion::factory()->user($this->editor->id)->page($page[$i - 1]->id)->create();
                    }
                }
            }
        }

        $response = $this->get('/special/most-revised-pages')
            ->assertStatus(200);

        if ($withPages) {
            for ($i = 1; $i <= 21; $i++) {
                if ($i <= 20 && $isVisible) {
                    $response->assertSeeText($page[$i]->title);
                } else {
                    $response->assertDontSeeText($page[$i]->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getRevisedPagesProvider() {
        return [
            'without pages'     => [0, 0],
            'with pages'        => [1, 1],
            'with hidden pages' => [1, 0],
        ];
    }

    /**
     * Tests unlinked pages access.
     *
     * @dataProvider getLinkedPagesProvider
     *
     * @param int  $pages
     * @param int  $linked
     * @param bool $isVisible
     */
    public function testGetUnlinkedPages($pages, $linked, $isVisible) {
        if ($pages) {
            for ($i = 1; $i <= $pages; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $linked && $linked >= $i ? 1 : $isVisible,
                ]);
                $version[$i] = PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                if ($linked && $linked >= $i) {
                    $parent[$i] = Page::factory()->create();
                    PageLink::factory()->parent($parent[$i]->id)->link($page[$i]->id)->create();
                }
            }
        }

        $response = $this->get('/special/unlinked-pages')
            ->assertStatus(200);

        if ($pages) {
            if ($pages > $linked && $isVisible) {
                // At least one page should be listed
                $response->assertSeeText($page[$pages]->title);

                if ($linked) {
                    $response->assertDontSeeText($page[$linked]->title);
                }
            } else {
                foreach ($page as $taggedPage) {
                    $response->assertDontSeeText($taggedPage->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    /**
     * Tests most linked pages access.
     *
     * @dataProvider getLinkedPagesProvider
     *
     * @param int  $pages
     * @param int  $linked
     * @param bool $isVisible
     */
    public function testGetMostLinkedPages($pages, $linked, $isVisible) {
        if ($pages) {
            for ($i = 1; $i <= $pages; $i++) {
                $page[$i] = Page::factory()->create([
                    'is_visible' => $linked && $linked >= $i ? $isVisible : 1,
                ]);
                $version[$i] = PageVersion::factory()->user($this->editor->id)->page($page[$i]->id)->create();

                if ($linked && $linked >= $i) {
                    $parent[$i] = Page::factory()->create();
                    PageLink::factory()->parent($parent[$i]->id)->link($page[$i]->id)->create();
                }
            }
        }

        $response = $this->get('/special/linked-pages')
            ->assertStatus(200);

        if ($pages) {
            if ($isVisible) {
                foreach ($page as $key=>$taggedPage) {
                    if ($linked && $key <= $linked) {
                        // At least one page should be listed
                        $response->assertSeeText($taggedPage->title);
                    }
                }

                if ($pages > $linked) {
                    $response->assertDontSeeText($page[$pages]->title);
                }
            } else {
                foreach ($page as $taggedPage) {
                    $response->assertDontSeeText($taggedPage->title);
                }
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getLinkedPagesProvider() {
        return [
            'basic'                => [0, 0, 0],
            'unlinked page'        => [1, 0, 1],
            'hidden unlinked page' => [1, 0, 0],
            'linked page'          => [1, 1, 1],
            'hidden linked page'   => [1, 1, 0],
            'both'                 => [2, 1, 1],
            'both hidden'          => [2, 1, 1],
        ];
    }

    /**
     * Tests recently edited pages access.
     *
     * @dataProvider getRecentlyEditedProvider
     *
     * @param array|null $pageData
     * @param mixed|null $mode
     */
    public function testGetRecentlyEditedPages($pageData, $mode) {
        if ($pageData) {
            $page = Page::factory()->create([
                'is_visible' => $pageData[0],
            ]);
            $version = PageVersion::factory()->page($page->id)->user($this->editor->id)
                ->testData()->create([
                    'created_at' => $pageData[1] ? Carbon::now() : Carbon::now()->subDays($mode && is_numeric($mode) ? $mode + 1 : 0),
                ]);
        }

        $response = $this->get('/special/recent-pages'.($mode ? '?mode='.$mode : ''))
            ->assertStatus(200);

        if ($pageData) {
            if ($pageData[0] && ($pageData[1] || ($mode && $mode == 'all'))) {
                $response->assertSeeText($page->title);
                $response->assertSeeText('#'.$version->id);
            } else {
                $response->assertDontSeeText($page->title);
                $response->assertDontSeeText('#'.$version->id);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    /**
     * Tests recently edited images access.
     *
     * @dataProvider getRecentlyEditedProvider
     * @dataProvider getRecentlyEditedImagesProvider
     *
     * @param array|null $imageData
     * @param mixed|null $mode
     */
    public function testGetRecentlyEditedImages($imageData, $mode) {
        if ($imageData) {
            $service = new ImageManager;
            $page = Page::factory()->create([
                'is_visible' => $imageData[2] ?? 1,
            ]);
            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData()->create();

            $image = PageImage::factory()->create([
                'is_visible' => $imageData[0],
            ]);
            $version = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->create([
                'created_at' => $imageData[1] ? Carbon::now() : Carbon::now()->subDays($mode && is_numeric($mode) ? $mode + 1 : 0),
            ]);
            PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
            PagePageImage::factory()->page($page->id)->image($image->id)->create();
            $service->testImages($image, $version);
        }

        $response = $this->get('/special/recent-images'.($mode ? '?mode='.$mode : ''))
            ->assertStatus(200);

        if ($imageData) {
            if ($imageData[0] && ($imageData[2] ?? 1) && ($imageData[1] || ($mode && $mode == 'all'))) {
                $response->assertSeeText('#'.$version->id);
                $response->assertSee($image->thumbnailUrl);
            } else {
                $response->assertDontSeeText('#'.$version->id);
                $response->assertDontSee($image->thumbnailUrl);
            }

            $service->testImages($image, $version, false);
        } else {
            $response->assertViewHas('images', function ($images) {
                return $images->count() == 0;
            });
        }
    }

    public static function getRecentlyEditedProvider() {
        return [
            // $data = [$isVisible, $isCurrent]

            'basic'                    => [null, null],
            'with version'             => [[1, 1], null],
            'with hidden version'      => [[0, 1], null],
            '1 day, current'           => [[1, 1], 1],
            '1 day, current, hidden'   => [[0, 1], 1],
            '1 day, old'               => [[1, 0], 1],
            '1 day, old, hidden'       => [[0, 0], 1],
            '3 days, current'          => [[1, 1], 3],
            '3 days, current, hidden'  => [[0, 1], 3],
            '3 days, old'              => [[1, 0], 3],
            '3 days, old, hidden'      => [[0, 0], 3],
            '7 days, current'          => [[1, 1], 7],
            '7 days, current, hidden'  => [[0, 1], 7],
            '7 days, old'              => [[1, 0], 7],
            '7 days, old, hidden'      => [[0, 0], 7],
            '30 days, current'         => [[1, 1], 30],
            '30 days, current, hidden' => [[0, 1], 30],
            '30 days, old'             => [[1, 0], 30],
            '30 days, old, hidden'     => [[0, 0], 30],
            '50 days, current'         => [[1, 1], 50],
            '50 days, current, hidden' => [[0, 1], 50],
            '50 days, old'             => [[1, 0], 50],
            '50 days, old, hidden'     => [[0, 0], 50],
            'all, current'             => [[1, 1], 'all'],
            'all, current, hidden'     => [[0, 1], 'all'],
            'all, old'                 => [[1, 0], 'all'],
            'all, old, hidden'         => [[0, 0], 'all'],
        ];
    }

    public static function getRecentlyEditedImagesProvider() {
        return [
            // $data = [$isVisible, $isCurrent, $pageVisible]

            'with hidden page' => [[1, 1, 0], null],
        ];
    }

    /**
     * Tests wanted pages access.
     *
     * @dataProvider getWantedPagesProvider
     *
     * @param bool $withLink
     * @param bool $isVisible
     */
    public function testGetWantedPages($withLink, $isVisible) {
        if ($withLink) {
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
            $link = PageLink::factory()->parent($page->id)->wanted()->create();
        }

        $response = $this->get('/special/wanted-pages')
            ->assertStatus(200);

        if ($withLink) {
            if ($isVisible) {
                $response->assertSeeText($link->title);
                $response->assertSeeText($page->title);
            } else {
                $response->assertDontSeeText($link->title);
                $response->assertDontSeeText($page->title);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getWantedPagesProvider() {
        return [
            'without link'     => [0, 0],
            'with link'        => [1, 1],
            'with hidden link' => [1, 0],
        ];
    }

    /**
     * Tests create wanted page access.
     */
    public function testGetCreateWantedPage() {
        $page = Page::factory()->create();
        $link = PageLink::factory()->parent($page->id)->wanted()->create();

        $response = $this->actingAs($this->editor)
            ->get('/special/create-wanted/'.$link->title);

        $response->assertStatus(200);
    }

    /**
     * Tests create wanted page access.
     *
     * @dataProvider postCreateWantedPageProvider
     *
     * @param bool $withCategory
     */
    public function testPostCreateWantedPage($withCategory) {
        $page = Page::factory()->create();
        $link = PageLink::factory()->parent($page->id)->wanted()->create();

        $data = [
            'category_id' => $withCategory ? $page->category->id : mt_rand(500, 1000),
            'title'       => $link->title,
        ];

        $response = $this->actingAs($this->editor)
            ->post('/special/create-wanted/', $data);

        if ($withCategory) {
            $response->assertSessionHasNoErrors();
        } else {
            $response->assertSessionHasErrors();
        }
    }

    public static function postCreateWantedPageProvider() {
        return [
            'with category'    => [1],
            'without category' => [0],
        ];
    }

    /**
     * Tests protected pages access.
     *
     * @dataProvider getProtectedPagesProvider
     *
     * @param bool $withPage
     * @param bool $isVisible
     */
    public function testGetProtectedPages($withPage, $isVisible) {
        if ($withPage) {
            $admin = User::factory()->admin()->create();
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)->user($admin->id)->testData()->create();
            PageProtection::factory()->page($page->id)->user($admin->id)->create();
        }

        $response = $this->get('/special/protected-pages')
            ->assertStatus(200);

        if ($withPage) {
            if ($isVisible) {
                $response->assertSeeText($page->title);
            } else {
                $response->assertDontSeeText($page->title);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getProtectedPagesProvider() {
        return [
            'basic'       => [0, 0],
            'with page'   => [1, 1],
            'hidden page' => [1, 0],
        ];
    }

    /**
     * Tests utility tag page access.
     *
     * @dataProvider getUtilityTagProvider
     *
     * @param string $tag
     * @param bool   $withPage
     * @param bool   $isVisible
     */
    public function testGetUtilityTagPage($tag, $withPage, $isVisible) {
        if ($withPage) {
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData($page->title, null, '"wip"')->create();
            PageTag::factory()->page($page->id)->create([
                'type' => 'utility',
                'tag'  => $tag,
            ]);
        }

        $response = $this->get('/special/'.$tag.'-pages')
            ->assertStatus(200);

        if ($withPage) {
            if ($isVisible) {
                $response->assertSeeText($page->title);
            } else {
                $response->assertDontSeeText($page->title);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getUtilityTagProvider() {
        return [
            'wip'                  => ['wip', 0, 0],
            'wip with page'        => ['wip', 1, 1],
            'wip with hidden'      => ['wip', 1, 0],
            'stub'                 => ['stub', 0, 0],
            'stub with page'       => ['stub', 1, 1],
            'stub with hidden'     => ['stub', 1, 0],
            'outdated'             => ['outdated', 0, 0],
            'outdated with page'   => ['outdated', 1, 1],
            'outdated with hidden' => ['outdated', 1, 0],
            'cleanup'              => ['cleanup', 0, 0],
            'cleanup with page'    => ['cleanup', 1, 1],
            'cleanup with hidden'  => ['cleanup', 1, 0],
        ];
    }

    /**
     * Tests unwatched pages access.
     *
     * @dataProvider getUnwatchedPagesProvider
     *
     * @param bool $withPage
     */
    public function testGetUnwatchedPages($withPage) {
        $admin = User::factory()->admin()->make();

        if ($withPage) {
            $page = Page::factory()->create();
            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData()->create();
        }

        $response = $this->actingAs($admin)
            ->get('/admin/special/unwatched-pages')
            ->assertStatus(200);

        if ($withPage) {
            $response->assertSeeText($page->title);
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getUnwatchedPagesProvider() {
        return [
            'basic'     => [0],
            'with page' => [1],
        ];
    }

    /******************************************************************************
        LISTS OF PAGES
    *******************************************************************************/

    /**
     * Tests all pages access.
     *
     * @dataProvider getAllPagesProvider
     *
     * @param bool $withPage
     * @param bool $isVisible
     */
    public function testGetAllPages($withPage, $isVisible) {
        if ($withPage) {
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData()->create();
        }

        $response = $this->get('/special/all-pages')
            ->assertStatus(200);

        if ($withPage) {
            if ($isVisible) {
                $response->assertSeeText($page->title);
            } else {
                $response->assertDontSeeText($page->title);
            }
        } else {
            $response->assertViewHas('pages', function ($pages) {
                return $pages->count() == 0;
            });
        }
    }

    public static function getAllPagesProvider() {
        return [
            'without page'     => [0, 0],
            'with page'        => [1, 1],
            'with hidden page' => [1, 0],
        ];
    }

    /**
     * Tests all tags access.
     *
     * @dataProvider getAllTagsProvider
     *
     * @param bool $withTag
     * @param bool $isVisible
     */
    public function testGetAllTags($withTag, $isVisible) {
        if ($withTag) {
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
            PageVersion::factory()->page($page->id)->user($this->editor->id)->testData($page->title, null, null, '"'.$this->faker->unique()->domainWord().'"')->create();

            $tag = PageTag::factory()->page($page->id)->create();
        }

        $response = $this->get('/special/all-tags')
            ->assertStatus(200);

        if ($withTag) {
            if ($isVisible) {
                $response->assertSeeText($tag->tag);
            } else {
                $response->assertDontSeeText($tag->tag);
            }
        } else {
            $response->assertViewHas('tags', function ($tags) {
                return $tags->count() == 0;
            });
        }
    }

    public static function getAllTagsProvider() {
        return [
            'without tag'     => [0, 0],
            'with tag'        => [1, 1],
            'with hidden tag' => [1, 0],
        ];
    }

    /**
     * Tests all images access.
     *
     * @dataProvider getAllImagesProvider
     *
     * @param bool $withImage
     * @param bool $isVisible
     * @param bool $pageVisible
     */
    public function testGetAllImages($withImage, $isVisible, $pageVisible) {
        if ($withImage) {
            $service = new ImageManager;

            $page = Page::factory()->create([
                'is_visible' => $pageVisible,
            ]);

            $image = PageImage::factory()->create([
                'is_visible' => $isVisible,
            ]);
            $version = PageImageVersion::factory()->image($image->id)->user($this->editor->id)->create();
            PageImageCreator::factory()->image($image->id)->user($this->editor->id)->create();
            PagePageImage::factory()->page($page->id)->image($image->id)->create();
            $service->testImages($image, $version);
        }

        $response = $this->get('/special/all-images')
            ->assertStatus(200);

        if ($withImage) {
            if ($isVisible && $pageVisible) {
                $response->assertSee($image->thumbnailUrl);
            } else {
                $response->assertDontSee($image->thumbnailUrl);
            }

            $service->testImages($image, $version, false);
        } else {
            $response->assertViewHas('images', function ($images) {
                return $images->count() == 0;
            });
        }
    }

    public static function getAllImagesProvider() {
        return [
            'without image'     => [0, 0, 1],
            'with image'        => [1, 1, 1],
            'with hidden image' => [1, 0, 1],
            'with hidden page'  => [1, 1, 0],
        ];
    }

    /******************************************************************************
        USERS
    *******************************************************************************/

    /**
     * Tests user list access.
     */
    public function testGetUserList() {
        $response = $this->get('/special/user-list')
            ->assertStatus(200);

        $response->assertSeeText($this->editor->name);
        $response->assertViewHas('users', function ($users) {
            return $users->contains($this->editor);
        });
    }

    /******************************************************************************
        OTHER
    *******************************************************************************/

    /**
     * Tests random page access.
     *
     * @dataProvider getRandomPageProvider
     *
     * @param bool $withPage
     * @param bool $isVisible
     */
    public function testGetRandomPage($withPage, $isVisible) {
        if ($withPage) {
            $page = Page::factory()->create([
                'is_visible' => $isVisible,
            ]);
        }

        $response = $this->get('/special/random-page')
            ->assertStatus(302);

        if ($withPage && $isVisible) {
            $response->assertRedirect('/pages/'.$page->id.'.'.$page->slug);
        } else {
            $response->assertRedirect('/');
        }
    }

    public static function getRandomPageProvider() {
        return [
            'with page'        => [1, 1],
            'with hidden page' => [1, 0],
            'without page'     => [0, 0],
        ];
    }
}
