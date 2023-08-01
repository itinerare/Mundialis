<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageImage;
use App\Models\Page\PageImageVersion;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use App\Services\ImageManager;
use App\Services\PageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Admin/Special Controller
    |--------------------------------------------------------------------------
    |
    | Handles admin-facing special pages.
    |
    */

    /**
     * Shows list of unwatched pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getUnwatchedPages(Request $request) {
        $query = Page::visible(Auth::check() ? Auth::user() : null)->get()
            ->filter(function ($page) {
                return $page->watchers->count() == 0;
            })->sortBy('title');

        return view('pages.special.unwatched', [
            'pages' => $query->paginate(20)->appends($request->query()),
        ]);
    }

    /**
     * Shows the list of deleted pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletedPages(Request $request) {
        // Fetch deleted pages with their most recent version
        $query = Page::withTrashed()->whereNotNull('deleted_at');
        $sort = $request->only(['sort']);

        if ($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query->orderBy('deleted_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('deleted_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('deleted_at', 'DESC');
        }

        return view('admin.special.deleted_pages', [
            'pages' => $query->paginate(20)->appends($request->query()),
            'users' => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows a deleted page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletedPage($id) {
        $page = Page::withTrashed()->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        return view('admin.special.deleted_page', [
            'page' => $page,
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }

    /**
     * Gets the page restoration modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRestorePage($id) {
        $page = Page::withTrashed()->find($id);

        return view('admin.special._restore_page', [
            'page' => $page,
        ]);
    }

    /**
     * Restores a deleted page.
     *
     * @param App\Services\PageManager $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRestorePage(Request $request, PageManager $service, $id) {
        if ($id && $service->restorePage(Page::withTrashed()->find($id), Auth::user(), $request->get('reason'))) {
            flash('Page restored successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to('admin/special/deleted-pages');
    }

    /**
     * Shows the list of deleted images.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletedImages(Request $request) {
        // Fetch deleted images with their most recent version
        $query = PageImage::withTrashed()->whereNotNull('deleted_at');
        $sort = $request->only(['sort']);

        if ($request->get('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'newest':
                    $query->orderBy('deleted_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('deleted_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('deleted_at', 'DESC');
        }

        return view('admin.special.deleted_images', [
            'images' => $query->paginate(20)->appends($request->query()),
            'users'  => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows a deleted image.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletedImage(Request $request, $id) {
        $image = PageImage::withTrashed()->where('id', $id)->first();
        if (!$image) {
            abort(404);
        }

        $query = PageImageVersion::where('page_image_id', $image->id);
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

        return view('admin.special.deleted_image', [
            'image'    => $image,
            'versions' => $query->paginate(20)->appends($request->query()),
            'users'    => User::query()->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Gets the image restoration modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getRestoreImage($id) {
        $image = PageImage::withTrashed()->find($id);
        if (!$image->pages->count()) {
            abort(404);
        }

        return view('admin.special._restore_image', [
            'image' => $image,
        ]);
    }

    /**
     * Restores a deleted image.
     *
     * @param App\Services\PageManager $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRestoreImage(Request $request, ImageManager $service, $id) {
        $image = PageImage::withTrashed()->find($id);
        if (!$image->pages->count()) {
            abort(404);
        }

        if ($id && $service->restorePageImage($image, Auth::user(), $request->get('reason'))) {
            flash('Image restored successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                $service->addError($error);
            }

            return redirect()->back();
        }

        return redirect()->to('admin/special/deleted-images');
    }
}
