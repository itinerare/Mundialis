<?php

namespace Tests\Feature;

use App\Models\User\Rank;
use App\Models\User\User;
use Tests\TestCase;

class AdminUserEditTest extends TestCase
{
    // These tests center on user editing and ban handling

    /******************************************************************************
        EDITING
    *******************************************************************************/

    /**
     * Test user index access.
     */
    public function testCanGetAdminUserIndex()
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
     */
    public function testCanGetEditUser()
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
     */
    public function testCanPostEditUserBasic()
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
                'name'    => $subject->name,
                'rank_id' => $rank->id,
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'    => $subject->name,
            'rank_id' => $rank->id,
        ]);
    }

    /**
     * Test user editing, admin > editor.
     * This should work.
     */
    public function testCanPostEditEditorBasic()
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
                'name'    => $subject->name,
                'rank_id' => $rank->id,
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'    => $subject->name,
            'rank_id' => $rank->id,
        ]);
    }

    /**
     * Test user editing, admin > admin.
     * This should not work.
     */
    public function testCannotPostEditAdminBasic()
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
                'name'    => $subject->name,
                'rank_id' => $rank->id,
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name'    => $subject->name,
            'rank_id' => $oldRank,
        ]);
    }

    /**
     * Test user updates access.
     */
    public function testCanGetUserUpdates()
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
     */
    public function testCanGetBanUser()
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
     */
    public function testCanGetConfirmBanUser()
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
     */
    public function testCanPostBanUser()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'       => $subject->name,
            'is_banned'  => 1,
            'ban_reason' => 'Ban test',
        ]);
    }

    /**
     * Test user banning, admin > editor.
     * This should work.
     */
    public function testCanPostBanEditor()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->editor()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'       => $subject->name,
            'is_banned'  => 1,
            'ban_reason' => 'Ban test',
        ]);
    }

    /**
     * Test user banning, admin > admin.
     * This should not work.
     */
    public function testCannotPostBanAdmin()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent user to be the subject
        $subject = User::factory()->admin()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban test',
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name'      => $subject->name,
            'is_banned' => 0,
        ]);
    }

    /**
     * Test ban message editing, admin > regular user.
     * This should work.
     */
    public function testCanPostEditUserBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'       => $subject->name,
            'is_banned'  => 1,
            'ban_reason' => 'Ban message edit test',
        ]);
    }

    /**
     * Test ban message editing, admin > editor.
     * This should work.
     */
    public function testCanPostEditEditorBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->editor()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test',
            ]);

        // Directly verify that the appropriate change has occurred
        $this->assertDatabaseHas('users', [
            'name'       => $subject->name,
            'is_banned'  => 1,
            'ban_reason' => 'Ban message edit test',
        ]);
    }

    /**
     * Test ban message editing, admin > admin.
     * This should not work.
     */
    public function testCannotPostEditAdminBan()
    {
        // Make a persistent user
        $user = User::factory()->admin()->create();
        // Make a persistent, pre-banned user to be the subject
        $subject = User::factory()->admin()->banned()->create();

        // Try to post data
        $response = $this->actingAs($user)
            ->post('/admin/users/'.$subject->name.'/ban', [
                'ban_reason' => 'Ban message edit test',
            ]);

        // Directly verify that nothing has changed
        $this->assertDatabaseHas('users', [
            'name'       => $subject->name,
            'is_banned'  => 1,
            'ban_reason' => 'Generated as banned',
        ]);
    }

    /**
     * Test unban user access.
     */
    public function testCanGetUnbanUser()
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
     */
    public function testCanPostUnbanUser()
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
            'name'      => $subject->name,
            'is_banned' => 0,
        ]);
    }

    /**
     * Test user unbanning, admin > editor.
     * This should work.
     */
    public function testCanPostUnbanEditor()
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
            'name'      => $subject->name,
            'is_banned' => 0,
        ]);
    }

    /**
     * Test user unbanning, admin > admin.
     * This shouldn't work.
     */
    public function testCannotPostUnbanAdmin()
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
            'name'      => $subject->name,
            'is_banned' => 1,
        ]);
    }
}
