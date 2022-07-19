<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\InvitationCode;
use App\Services\InvitationService;
use Auth;

class InvitationController extends Controller {
    /**
     * Shows the invitation key index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.users.invitations', [
            'invitations' => InvitationCode::orderBy('id', 'DESC')->paginate(20),
        ]);
    }

    /**
     * Generates a new invitation key.
     *
     * @param App\Services\InvitationService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postGenerateKey(InvitationService $service) {
        if ($service->generateInvitation(Auth::user())) {
            flash('Generated invitation successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Generates a new invitation key.
     *
     * @param App\Services\InvitationService $service
     * @param int                            $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteKey(InvitationService $service, $id) {
        $invitation = InvitationCode::find($id);
        if ($invitation && $service->deleteInvitation($invitation)) {
            flash('Deleted invitation key successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }
}
