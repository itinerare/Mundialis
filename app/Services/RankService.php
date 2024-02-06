<?php

namespace App\Services;

use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class RankService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Rank Service
    |--------------------------------------------------------------------------
    |
    | Handles creation and modification of user ranks.
    |
    */

    /**
     * Updates a user rank.
     *
     * @param \App\Models\Rank\Rank $rank
     * @param array                 $data
     * @param User                  $user
     *
     * @return bool
     */
    public function updateRank($rank, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (Rank::where('name', $data['name'])->where('id', '!=', $rank->id)->exists()) {
                throw new \Exception('A rank with the given name already exists.');
            }

            $rank->update($data);

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }
}
