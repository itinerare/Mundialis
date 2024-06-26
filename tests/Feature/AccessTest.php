<?php

namespace Tests\Feature;

use App\Models\User\Rank;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccessTest extends TestCase {
    use RefreshDatabase;

    /******************************************************************************
        ACCESS/MIDDLEWARE
    *******************************************************************************/

    protected function setUp(): void {
        parent::setUp();
    }

    /**
     * Test getting the main page.
     *
     * @dataProvider accessProvider
     *
     * @param bool $user
     * @param int  $status
     */
    public function testGetIndex($user, $status) {
        if ($user) {
            $response = $this->actingAs($this->user)->get('/');
        } else {
            $response = $this->get('/');
        }

        $response->assertStatus($status);
    }

    public static function accessProvider() {
        return [
            'visitor' => [0, 200],
            'user'    => [1, 200],
        ];
    }

    /**
     * Test site read access as per site settings.
     *
     * @dataProvider readAccessProvider
     *
     * @param bool $user
     * @param bool $isOpen
     * @param int  $status
     */
    public function testReadAccess($user, $isOpen, $status) {
        // Adjust site settings accordingly
        DB::table('site_settings')->where('key', 'visitors_can_read')->update(['value' => $isOpen]);

        if ($user) {
            $response = $this->actingAs($this->user)->get('/misc');
        } else {
            $response = $this->get('/misc');
        }

        $response->assertStatus($status);
    }

    public static function readAccessProvider() {
        return [
            'visitor, site open'   => [0, 1, 200],
            'visitor, site closed' => [0, 0, 302],
            'user, site open'      => [1, 1, 200],
            'user, site closed'    => [1, 0, 200],
        ];
    }

    /**
     * Test access to account settings.
     * This should be representative of all member routes.
     *
     * @dataProvider memberAccessProvider
     *
     * @param bool $user
     * @param int  $rank
     * @param int  $status
     */
    public function testMemberRouteAccess($user, $rank, $status) {
        if ($user) {
            $user = User::factory()->make([
                'rank_id' => Rank::where('sort', $rank)->first()->id,
            ]);
            $response = $this->actingAs($user)->get('/account/settings');
        } else {
            $response = $this->get('/account/settings');
        }

        $response->assertStatus($status);
    }

    public static function memberAccessProvider() {
        return [
            'visitor' => [0, 0, 302],
            'user'    => [1, 0, 200],
            'editor'  => [1, 1, 200],
            'admin'   => [1, 2, 200],
        ];
    }

    /**
     * Test access to lexicon entry creation.
     * This should be representative of all editor routes.
     *
     * @dataProvider editorAccessProvider
     *
     * @param bool $user
     * @param int  $rank
     * @param int  $status
     */
    public function testEditorRouteAccess($user, $rank, $status) {
        if ($user) {
            $user = User::factory()->make([
                'rank_id' => Rank::where('sort', $rank)->first()->id,
            ]);
            $response = $this->actingAs($user)->get('/language/lexicon/create');
        } else {
            $response = $this->get('/language/lexicon/create');
        }

        $response->assertStatus($status);
    }

    public static function editorAccessProvider() {
        return [
            'visitor' => [0, 0, 302],
            'user'    => [1, 0, 302],
            'editor'  => [1, 1, 200],
            'admin'   => [1, 2, 200],
        ];
    }

    /**
     * Test access to the admin dashboard.
     * This should be representative of all admin-only routes.
     *
     * @dataProvider adminAccessProvider
     *
     * @param bool $user
     * @param int  $rank
     * @param int  $status
     */
    public function testAdminRouteAccess($user, $rank, $status) {
        if ($user) {
            $user = User::factory()->make([
                'rank_id' => Rank::where('sort', $rank)->first()->id,
            ]);
            $response = $this->actingAs($user)->get('/admin');
        } else {
            $response = $this->get('/admin');
        }

        $response->assertStatus($status);
    }

    public static function adminAccessProvider() {
        return [
            'visitor' => [0, 0, 302],
            'user'    => [1, 0, 302],
            'editor'  => [1, 1, 302],
            'admin'   => [1, 2, 200],
        ];
    }
}
