<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageVersion;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use App\Services\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Image Controller
    |--------------------------------------------------------------------------
    |
    | Handles page images.
    |
    */

    /**
     * Shows a page's gallery.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageGallery(Request $request, $id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $id)->with('category', 'parent')->first();
        if (!$page) {
            abort(404);
        }

        $query = $page->images()->visible(Auth::user() ?? null)->orderBy('is_valid', 'DESC');
        $sort = $request->only(['sort']);

        if ($request->get('creator_url')) {
            $creatorUrl = $request->get('creator_url');
            $query->whereHas('creators', function ($query) use ($creatorUrl) {
                $query->where('url', 'LIKE', '%'.$creatorUrl.'%');
            });
        }
        if ($request->get('creator_id')) {
            $creator = User::find($request->get('creator_id'));
            $query->whereHas('creators', function ($query) use ($creator) {
                $query->where('user_id', $creator->id);
            });
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return view('pages.images.gallery', [
            'page'   => $page,
            'images' => $query->paginate(20)->appends($request->query()),
            'users'  => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows the page for a given image.
     *
     * @param int $pageId
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageImage(Request $request, $pageId, $id) {
        $page = Page::visible(Auth::user() ?? null)->where('id', $pageId)->first();
        if (!$page) {
            abort(404);
        }
        $image = $page->images()->visible(Auth::user() ?? null)->where('page_images.id', $id)->first();
        if (!$image) {
            abort(404);
        }

        $query = PageImageVersion::where('page_image_id', $image->id)->with('user', 'image');
        $sort = $request->only(['sort']);

        if ($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        return view('pages.images.image', [
            'page'     => $page,
            'image'    => $image,
            'versions' => $query->paginate(20)->appends($request->query()),
            'users'    => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the popup for a given image.
     *
     * @param int $id
     * @param int $imageId
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getPageImagePopup($id, $imageId = null) {
        if (isset($id) && isset($imageId)) {
            $page = Page::visible(Auth::user() ?? null)->where('id', $id)->first();
            if (!$page) {
                abort(404);
            }
            $image = $page->images()->visible(Auth::user() ?? null)->where('page_images.id', $imageId)->first();
        } else {
            $image = PageImage::where('id', $id)->visible(Auth::user() ?? null)->first();
        }
        if (!$image) {
            abort(404);
        }

        return view('pages.images._info_popup', [
            'page'  => $page ?? null,
            'image' => $image,
        ]);
    }

    /**
     * Shows the create image page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateImage($id) {
        $page = Page::where('id', $id)->with('category', 'parent')->first();
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }

        // Collect pages and information and group them
        $groupedPages = Page::with('category')->orderBy('title')->where('id', '!=', $page->id)->get()->keyBy('id')->groupBy(function ($page) {
            return $page->category->subject['name'];
        }, $preserveKeys = true)->toArray();

        // Collect subjects and information
        $orderedSubjects = collect(config('mundialis.subjects'))->filter(function ($subject) use ($groupedPages) {
            if (isset($groupedPages[$subject['name']])) {
                return 1;
            } else {
                return 0;
            }
        })->pluck('name', 'name');

        foreach ($groupedPages as $subject=> $pages) {
            foreach ($pages as $id=>$groupPage) {
                $groupedPages[$subject][$id] = $groupPage['title'];
            }
        }

        // Organize them according to standard subject listing
        $sortedPages = $orderedSubjects->map(function ($subject, $key) use ($groupedPages) {
            return $groupedPages[$subject];
        });

        return view('pages.images.create_edit_image', [
            'image'       => new PageImage,
            'page'        => $page,
            'pageOptions' => $sortedPages,
            'users'       => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Shows the edit image page.
     *
     * @param int $id
     * @param int $pageId
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditImage($pageId, $id) {
        $page = Page::where('id', $pageId)->first();
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }
        $image = $page->images()->visible(Auth::user() ?? null)->where('page_images.id', $id)->first();
        if (!$image) {
            abort(404);
        }

        // Collect pages and information and group them
        $groupedPages = Page::orderBy('title')->where('id', '!=', $page->id)->get()->keyBy('id')->groupBy(function ($page) {
            return $page->category->subject['name'];
        }, $preserveKeys = true)->toArray();

        // Collect subjects and information
        $orderedSubjects = collect(config('mundialis.subjects'))->filter(function ($subject) use ($groupedPages) {
            if (isset($groupedPages[$subject['name']])) {
                return 1;
            } else {
                return 0;
            }
        })->pluck('name', 'name');

        foreach ($groupedPages as $subject=> $pages) {
            foreach ($pages as $id=>$groupPage) {
                $groupedPages[$subject][$id] = $groupPage['title'];
            }
        }

        // Organize them according to standard subject listing
        $sortedPages = $orderedSubjects->map(function ($subject, $key) use ($groupedPages) {
            return $groupedPages[$subject];
        });

        return view('pages.images.create_edit_image', [
            'image'       => $image,
            'page'        => $page,
            'pageOptions' => $sortedPages,
            'users'       => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Creates or edits a page image.
     *
     * @param App\Services\ImageManager $service
     * @param int                       $pageId
     * @param int                       $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditImage(Request $request, ImageManager $service, $pageId, $id = null) {
        $id ? $request->validate(PageImage::$updateRules) : $request->validate(PageImage::$createRules);
        $data = $request->only([
            'image', 'thumbnail', 'x0', 'x1', 'y0', 'y1', 'use_cropper',
            'creator_id', 'creator_url', 'description', 'page_id',
            'is_valid', 'is_visible', 'mark_invalid', 'mark_active',
            'is_minor', 'reason',
        ]);

        $page = Page::where('id', $pageId)->first();
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }

        if ($id && $service->updatePageImage($page, PageImage::find($id), $data, Auth::user())) {
            flash('Image updated successfully.')->success();
        } elseif (!$id && $image = $service->createPageImage($data, $page, Auth::user())) {
            flash('Image created successfully.')->success();

            return redirect()->to('pages/'.$page->id.'/gallery');
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the image deletion modal.
     *
     * @param int $pageId
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteImage($pageId, $id) {
        $page = Page::where('id', $pageId)->first();
        if (!$page || !Auth::user()->canEdit($page)) {
            abort(404);
        }
        $image = $page->images()->visible(Auth::user() ?? null)->where('page_images.id', $id)->first();

        return view('pages.images._delete_image', [
            'image' => $image,
            'page'  => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param App\Services\ImageManager $service
     * @param int                       $pageId
     * @param int                       $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteImage(Request $request, ImageManager $service, $pageId, $id) {
        if ($id && $service->deletePageImage(PageImage::find($id), Auth::user(), $request->get('reason'))) {
            flash('Image deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }
        }

        return redirect()->to('pages/'.$pageId.'/gallery');
    }
}
