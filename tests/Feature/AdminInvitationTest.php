<?php

namespace Tests\Feature;

use App\Models\User\InvitationCode;
use App\Models\User\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInvitationTest extends TestCase
{
    use RefreshDatabase;

    /******************************************************************************
        INVITATIONS
    *******************************************************************************/

    /**
     * Test invitation code index access.
     */
    public function test_canGetInvitationIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/invitations')
            ->assertStatus(200);
    }

    /**
     * Test invitation code creation.
     */
    public function test_canPostCreateInvitation()
    {
        // Count currently extant invitation codes
        $oldCount = InvitationCode::all()->count();

        // Make a persistent user
        $user = User::factory()->admin()->create();

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/create');

        // Check that there are more invitation codes than before
        $this->assertTrue(InvitationCode::all()->count() > $oldCount);
    }

    /**
     * Test invitation code deletion.
     */
    public function test_canPostDeleteInvitation()
    {
        // Make a persistent user (in case a code needs to be generated)
        $user = User::factory()->admin()->create();

        // Count existing codes
        $oldCount = InvitationCode::all()->count();
        // Find or create a code to delete
        $code =
            InvitationCode::where('recipient_id', null)->first() ?
            InvitationCode::where('recipient_id', null)->first() :
            (new InvitationService)->generateInvitation($user);

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/delete/'.$code->id);

        // Check that there are fewer invitation codes than before
        $this->assertTrue(InvitationCode::all()->count() <= $oldCount);
    }

    /**
     * Ensure a used invitation code cannot be deleted.
     */
    public function test_cannotPostDeleteUsedInvitation()
    {
        // Make a persistent user (in case a code needs to be generated)
        $user = User::factory()->admin()->create();

        // Count existing codes
        $oldCount = InvitationCode::all()->count();
        // Find or create a code to attempt to delete
        $code =
            InvitationCode::where('recipient_id', '!=', null)->first() ?
            InvitationCode::where('recipient_id', '!=', null)->first() :
            (new InvitationService)->generateInvitation($user);

        // If necessary, simulate a "used" code
        if ($code->recipient_id == null) {
            // Create a persistent user and mark them as the code's recipient
            $recipient = User::factory()->create();
            $code->update(['recipient_id' => $recipient->id]);
        }

        // Try to post data
        $response = $this
            ->actingAs($user)
            ->post('/admin/invitations/delete/'.$code->id);

        // Check that there are the same number of invitation codes or greater
        $this->assertTrue(InvitationCode::all()->count() >= $oldCount);
    }
}
