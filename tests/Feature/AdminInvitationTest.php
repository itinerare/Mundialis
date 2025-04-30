<?php

namespace Tests\Feature;

use App\Models\User\User;
use App\Services\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminInvitationTest extends TestCase {
    use RefreshDatabase;

    /******************************************************************************
        ADMIN / INVITATIONS
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    /**
     * Test invitation code index access.
     */
    public function testGetInvitationIndex() {
        $this->actingAs($this->admin)
            ->get('/admin/invitations')
            ->assertStatus(200);
    }

    /**
     * Test invitation code creation.
     */
    public function testPostCreateInvitation() {
        // Try to post data
        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/invitations/create');

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('invitation_codes', 1);
    }

    /**
     * Test invitation code deletion.
     *
     * @param bool $isUsed
     * @param bool $expected
     */
    #[DataProvider('deleteInvitationProvider')]
    public function testPostDeleteInvitation($isUsed, $expected) {
        // Since invitation code generation is fairly straightforward,
        // simply use the function rather than a factory
        $invitation = (new InvitationService)->generateInvitation($this->admin);

        if ($isUsed) {
            $recipient = User::factory()->create();
            $invitation->update(['recipient_id' => $recipient->id]);
        }

        $response = $this
            ->actingAs($this->admin)
            ->post('/admin/invitations/delete/'.$invitation->id);

        if ($expected) {
            $this->assertModelMissing($invitation);
            $response->assertSessionHasNoErrors();
        } else {
            $this->assertModelExists($invitation);
            $response->assertSessionHasErrors();
        }
    }

    public static function deleteInvitationProvider() {
        return [
            'unused' => [0, 1],
            'used'   => [1, 0],
        ];
    }
}
