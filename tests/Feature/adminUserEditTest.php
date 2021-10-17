<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User\User;
use App\Models\User\Rank;

class adminUserEditTest extends TestCase
{
    // These tests center on user editing and ban handling

    /******************************************************************************
        EDITING
    *******************************************************************************/

    /**
     * Test user index access.
     *
     * @return void
     */
    public function test_canGetAdminUserIndex()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users')
            ->assertStatus(200);
    }

    /**
     * Test user edit page access.
     *
     * @return void
     */
    public function test_canGetEditUser()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users/'.$subject->name.'/edit')
            ->assertStatus(200);
    }

    /**
     * Test user editing, admin > regular user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditUserBasic()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Get the middle rank (editor)
        $rank = Rank::orderBy('sort', 'ASC')->skip(1)->first();

        // By default this user is the lowest rank (member),
        // so try changing them to an editor
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/basic', [
                'name' => $subject->name,
                'rank_id' => $rank->id
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'rank_id' => $rank->id
        ]);
    }

    /**
     * Test user editing, admin > editor.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditEditorBasic()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->editor()->create();

        // Get the last rank (member)
        $rank = Rank::orderBy('sort', 'ASC')->first();

        // By default this user is the lowest rank (member),
        // so try changing them to an editor
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/basic', [
                'name' => $subject->name,
                'rank_id' => $rank->id
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'rank_id' => $rank->id
        ]);
    }

    /**
     * Test user editing, admin > admin.
     * This should not work.
     *
     * @return void
     */
    public function test_cannotPostEditAdminBasic()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->admin()->create();

        // Get the existing rank ID for convenience
        $oldRank = $subject->rank_id;
        // Get the middle rank (editor)
        $rank = Rank::orderBy('sort', 'ASC')->skip(1)->first();

        // By default this user is the lowest rank (member),
        // so try changing them to an editor
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/basic', [
                'name' => $subject->name,
                'rank_id' => $rank->id
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'rank_id' => $oldRank
        ]);
    }

    /**
     * Test user updates access.
     *
     * @return void
     */
    public function test_canGetUserUpdates()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users/'.$subject->name.'/updates')
            ->assertStatus(200);
    }

    /******************************************************************************
        BANNING
    *******************************************************************************/

    /**
     * Test ban user page access.
     *
     * @return void
     */
    public function test_canGetBanUser()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users/'.$subject->name.'/ban')
            ->assertStatus(200);
    }

    /**
     * Test ban confirmation access.
     *
     * @return void
     */
    public function test_canGetConfirmBanUser()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users/'.$subject->name.'/ban-confirm')
            ->assertStatus(200);
    }

    /**
     * Test user banning, admin > regular user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostBanUser()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1,
            'ban_reason' => 'Ban test'
        ]);
    }

    /**
     * Test user banning, admin > editor.
     * This should work.
     *
     * @return void
     */
    public function test_canPostBanEditor()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->editor()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1,
            'ban_reason' => 'Ban test'
        ]);
    }

    /**
     * Test user banning, admin > admin.
     * This should not work.
     *
     * @return void
     */
    public function test_cannotPostBanAdmin()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->admin()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test'
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 0
        ]);
    }

    /**
     * Test ban message editing, admin > regular user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditUserBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1,
            'ban_reason' => 'Ban message edit test'
        ]);
    }

    /**
     * Test ban message editing, admin > editor.
     * This should work.
     *
     * @return void
     */
    public function test_canPostEditEditorBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->editor()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test'
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1,
            'ban_reason' => 'Ban message edit test'
        ]);
    }

    /**
     * Test ban message editing, admin > admin.
     * This should not work.
     *
     * @return void
     */
    public function test_cannotPostEditAdminBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->admin()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test'
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1,
            'ban_reason' => 'Generated as banned'
        ]);
    }

    /**
     * Test unban user access.
     *
     * @return void
     */
    public function test_canGetUnbanUser()
    {
        // Make a temporary user
        $user = User::factory()->admin()->make();

        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Attempt page access
        $response = $this->actingAs($user)
            ->get('/admin/users/'.$subject->name.'/unban-confirm')
            ->assertStatus(200);
    }

    /**
     * Test user unbanning, admin > regular user.
     * This should work.
     *
     * @return void
     */
    public function test_canPostUnbanUser()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/unban');

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 0
        ]);
    }

    /**
     * Test user unbanning, admin > editor.
     * This should work.
     *
     * @return void
     */
    public function test_canPostUnbanEditor()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->banned()->editor()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/unban');

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 0
        ]);
    }

    /**
     * Test user unbanning, admin > admin.
     * This shouldn't work.
     *
     * @return void
     */
    public function test_cannotPostUnbanAdmin()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->banned()->admin()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/unban');

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name' => $subject->name,
            'is_banned' => 1
        ]);
    }
}
