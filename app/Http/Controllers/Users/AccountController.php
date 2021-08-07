<?php

namespace App\Http\Controllers\Users;

use Auth;
use File;
use Image;

use App\Models\User\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Models\Notification;

use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\RecoveryCode;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

use App\Services\UserService;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{
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
    public function getBanned()
    {
        if(Auth::user()->is_banned)
            return view('account.banned');
        else
            return redirect()->to('/');
    }

    /**
     * Shows the user settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getSettings()
    {
        return view('account.settings');
    }

    /**
     * Edits the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProfile(Request $request)
    {
        Auth::user()->update([
            'profile_text' => $request->get('profile_text'),
        ]);
        flash('Profile updated successfully.')->success();
        return redirect()->back();
    }

    /**
     * Edits the user's avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAvatar(Request $request, UserService $service)
    {
        if($service->updateAvatar($request->file('avatar'), Auth::user())) {
            flash('Avatar updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }


	/**
     * Changes the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\UserService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPassword(Request $request, UserService $service)
    {
        $request->validate( [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ]);
        if($service->updatePassword($request->only(['old_password', 'new_password', 'new_password_confirmation']), Auth::user())) {
            flash('Password updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Changes the user's email address and sends a verification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\UserService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEmail(Request $request, UserService $service)
    {
        $request->validate( [
            'email' => 'required|string|email|max:255|unique:users'
        ]);
        if($service->updateEmail($request->only(['email']), Auth::user())) {
            flash('Email updated successfully..')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }

    /**
     * Enables the user's two factor auth.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\UserService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEnableTwoFactor(Request $request, UserService $service)
    {
        if(!$request->session()->put([
            'two_factor_secret' => encrypt(app(TwoFactorAuthenticationProvider::class)->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])) {
            flash('2FA info generated. Please confirm to enable 2FA.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('account/two-factor/confirm');
    }

    /**
     * Shows the confirm two-factor auth page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getConfirmTwoFactor(Request $request)
    {
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
            'qrCode' => $qrCode,
            'recoveryCodes' => json_decode(decrypt($request->session()->get('two_factor_recovery_codes'))),
        ]);
    }

    /**
     * Confirms and fully enables the user's two factor auth.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\UserService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postConfirmTwoFactor(Request $request, UserService $service)
    {
        $request->validate( [
            'code' => 'required'
        ]);
        if($service->confirmTwoFactor($request->only(['code']), $request->session()->only(['two_factor_secret', 'two_factor_recovery_codes']), Auth::user())) {
            flash('2FA enabled succesfully.')->success();
            $request->session()->forget(['two_factor_secret', 'two_factor_recovery_codes']);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('account/settings');
    }

    /**
     * Confirms and disables the user's two factor auth.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\UserService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDisableTwoFactor(Request $request, UserService $service)
    {
        $request->validate( [
            'code' => 'required'
        ]);
        if($service->disableTwoFactor($request->only(['code']), Auth::user())) {
            flash('2FA disabled succesfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}