<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Page\Page;
use App\Models\Page\PageTag;
use App\Models\Subject\SubjectCategory;
use App\Models\User\User;
use App\Services\UserService;
use Auth;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\RecoveryCode;

class AccountController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Account Controller
    |--------------------------------------------------------------------------
    |
    | Handles the user's account management.
    |
    */

    /**
     * Shows the banned page, or redirects the user to the home page if they aren't banned.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function getBanned() {
        if (Auth::user()->is_banned) {
            return view('account.banned');
        } else {
            return redirect()->to('/');
        }
    }

    /**
     * Shows the user settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSettings() {
        return view('account.settings');
    }

    /**
     * Edits the user's profile.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProfile(Request $request) {
        Auth::user()->update([
            'profile_text' => $request->get('profile_text'),
        ]);
        flash('Profile updated successfully.')->success();

        return redirect()->back();
    }

    /**
     * Edits the user's avatar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAvatar(Request $request, UserService $service) {
        if ($service->updateAvatar($request->file('avatar'), Auth::user())) {
            flash('Avatar updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Changes the user's password.
     *
     * @param App\Services\UserService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPassword(Request $request, UserService $service) {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        if ($service->updatePassword($request->only(['old_password', 'new_password', 'new_password_confirmation']), Auth::user())) {
            flash('Password updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Changes the user's email address and sends a verification email.
     *
     * @param App\Services\UserService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request, UserService $service) {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        if ($service->updateEmail($request->only(['email']), Auth::user())) {
            flash('Email updated successfully..')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Enables the user's two factor auth.
     *
     * @param App\Services\UserService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnableTwoFactor(Request $request, UserService $service) {
        if (!$request->session()->put([
            'two_factor_secret'         => encrypt(app(TwoFactorAuthenticationProvider::class)->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])) {
            flash('2FA info generated. Please confirm to enable 2FA.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('account/two-factor/confirm');
    }

    /**
     * Shows the confirm two-factor auth page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getConfirmTwoFactor(Request $request) {
        // Assemble URL and QR Code svg from session information
        $qrUrl = app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(config('app.name'), Auth::user()->email, decrypt($request->session()->get('two_factor_secret')));
        $qrCode = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($qrUrl);
        $qrCode = trim(substr($qrCode, strpos($qrCode, "\n") + 1));

        return view('auth.confirm_two_factor', [
            'qrCode'        => $qrCode,
            'recoveryCodes' => json_decode(decrypt($request->session()->get('two_factor_recovery_codes'))),
        ]);
    }

    /**
     * Confirms and fully enables the user's two factor auth.
     *
     * @param App\Services\UserService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postConfirmTwoFactor(Request $request, UserService $service) {
        $request->validate([
            'code' => 'required',
        ]);
        if ($service->confirmTwoFactor($request->only(['code']), $request->session()->only(['two_factor_secret', 'two_factor_recovery_codes']), Auth::user())) {
            flash('2FA enabled succesfully.')->success();
            $request->session()->forget(['two_factor_secret', 'two_factor_recovery_codes']);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('account/settings');
    }

    /**
     * Confirms and disables the user's two factor auth.
     *
     * @param App\Services\UserService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDisableTwoFactor(Request $request, UserService $service) {
        $request->validate([
            'code' => 'required',
        ]);
        if ($service->disableTwoFactor($request->only(['code']), Auth::user())) {
            flash('2FA disabled succesfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Shows the watched pages page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getWatchedPages(Request $request) {
        $query = Auth::user()->watched()->visible(Auth::user());
        $sort = $request->only(['sort']);

        if ($request->get('title')) {
            $query->where(function ($query) use ($request) {
                $query->where('pages.title', 'LIKE', '%'.$request->get('title').'%');
            });
        }
        if ($request->get('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }
        if ($request->get('tags')) {
            foreach ($request->get('tags') as $tag) {
                $query->whereIn('pages.id', PageTag::tagSearch($tag)->tag()->pluck('page_id')->toArray());
            }
        }

        if (isset($sort['sort'])) {
            switch ($sort['sort']) {
                case 'alpha':
                    $query->orderBy('title');
                    break;
                case 'alpha-reverse':
                    $query->orderBy('title', 'DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'ASC');
                    break;
            }
        } else {
            $query->orderBy('title');
        }

        return view('account.watched_pages', [
            'pages'           => $query->paginate(20)->appends($request->query()),
            'categoryOptions' => SubjectCategory::pluck('name', 'id'),
            'tags'            => (new PageTag)->listTags(),
        ]);
    }

    /**
     * Watches/unwatches a page.
     *
     * @param App\Services\UserService $service
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postWatchPage(Request $request, UserService $service, $id) {
        if ($service->watchPage(Page::find($id), Auth::user())) {
            flash('Page watch status updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Shows the notifications page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getNotifications() {
        $notifications = Auth::user()->notifications()->orderBy('id', 'DESC')->paginate(30);
        Auth::user()->notifications()->update(['is_unread' => 0]);
        Auth::user()->notifications_unread = 0;
        Auth::user()->save();

        return view('account.notifications', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Deletes a notification and returns a response.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeleteNotification($id) {
        $notification = Notification::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if ($notification) {
            $notification->delete();
        }

        return response(200);
    }

    /**
     * Deletes all of the user's notifications.
     *
     * @param int|null $type
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postClearNotifications($type = null) {
        if (isset($type)) {
            Auth::user()->notifications()->where('notification_type_id', $type)->delete();
        } else {
            Auth::user()->notifications()->delete();
        }
        flash('Notifications cleared successfully.')->success();

        return redirect()->back();
    }
}
