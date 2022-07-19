<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\Rank;
use App\Services\RankService;
use Auth;
use Illuminate\Http\Request;

class RankController extends Controller {
    /**
     * Show the rank index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.users.ranks', [
            'ranks' => Rank::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Get the rank editing modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditRank($id) {
        $rank = Rank::find($id);

        return view('admin.users._edit_rank', [
            'rank' => $rank,
        ]);
    }

    public function postEditRank(Request $request, RankService $service, $id = null) {
        $request->validate(Rank::$rules);
        $data = $request->only(['name', 'description']);

        if ($service->updateRank(Rank::find($id), $data, Auth::user())) {
            flash('Rank updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }
}
