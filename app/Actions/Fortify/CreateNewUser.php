<?php

namespace App\Actions\Fortify;

use App\Models\User\InvitationCode;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Services\InvitationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Settings;

class CreateNewUser implements CreatesNewUsers {
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @return \App\Models\User\User
     */
    public function create(array $input) {
        if (!Settings::get('is_registration_open')) {
            throw new \Exception('Registration is currently closed.');
        }

        Validator::make($input, [
            'name'      => ['required', 'string', 'min:3', 'max:25', 'alpha_dash', 'unique:users'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'agreement' => ['required', 'accepted'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'code'      => ['string', function ($attribute, $value, $fail) {
                $invitation = InvitationCode::where('code', $value)->whereNull('recipient_id')->first();
                if (!$invitation) {
                    $fail('Invalid code entered.');
                }
            },
            ],
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'rank_id'  => Rank::orderBy('sort', 'ASC')->first()->id,
        ]);

        if (!(new InvitationService)->useInvitation(InvitationCode::where('code', $input['code'])->whereNull('recipient_id')->first(), $user)) {
            throw new \Exception('An error occurred while using the invitation code.');
        }

        return $user;
    }
}
