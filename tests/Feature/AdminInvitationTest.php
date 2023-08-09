<?php

namespace Tests\Feature;

use App\Models\User\InvitationCode;
use App\Models\User\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInvitationTest extends TestCase {
    use RefreshDatabase;

    /******************************************************************************
        INVITATIONS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->admin()->create();
    }

    /**
     * Test invitation code index access.
     */
    public function testGetInvitationIndex() {
        $this->actingAs($this->user)
            ->get('/admin/invitations')
            ->assertStatus(200);
    }

    /**
     * Test invitation code creation.
     */
    public function testPostCreateInvitation() {
        // Try to post data
        $response = $this
            ->actingAs($this->user)
            ->post('/admin/invitations/create');

        $response->assertSessionHasNoErrors();
        $this->assertTrue(InvitationCode::all()->count() == 1);
    }

    /**
     * Test invitation code deletion.
     *
     * @dataProvider deleteInvitationProvider
     *
     * @param bool $isUsed
     * @param bool $expected
     */
    public function testPostDeleteInvitation($isUsed, $expected) {
        // Since invitation code generation is fairly straightforward,
        // simply use the function rather than a factory
        $invitation = (new InvitationService)->generateInvitation($this->user);

        if ($isUsed) {
            $recipient = User::factory()->create();
            $invitation->update(['recipient_id' => $recipient->id]);
        }

        $response = $this
            ->actingAs($this->user)
            ->post('/admin/invitations/delete/'.$invitation->id);

        if ($expected) {
            $this->assertModelMissing($invitation);
            $response->assertSessionHasNoErrors();
        } else {
            $this->assertModelExists($invitation);
            $response->assertSessionHasErrors();
        }
    }

    public function deleteInvitationProvider() {
        return [
            'unused' => [0, 1],
            'used'   => [1, 0],
        ];
    }
}
