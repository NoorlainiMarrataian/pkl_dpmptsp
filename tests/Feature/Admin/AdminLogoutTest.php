<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminLogoutTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);
    }

    /** @test */
    public function LT_01()
    {
        $this->actingAs($this->admin, 'admin');
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);      
        $dashboardResponse->assertSee('Keluar');
        $dashboardResponse->assertSee('btnLogoutSidebar');
        $dashboardResponse->assertSee('Konfirmasi Logout');
        $dashboardResponse->assertSee('Apakah Anda yakin ingin keluar dari akun admin?');
        $dashboardResponse->assertSee('Batal');
        
        $this->assertAuthenticatedAs($this->admin, 'admin');
        $stillInDashboard = $this->get(route('admin.dashboard'));
        $stillInDashboard->assertStatus(200);
        $stillInDashboard->assertSee('Dashboard');
        
        $this->assertAuthenticated('admin');
    }

    /** @test */
    public function LT_02()
    {
        $this->actingAs($this->admin, 'admin');
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Keluar');
        $dashboardResponse->assertSee('btnLogoutSidebar');
        $dashboardResponse->assertSee('Konfirmasi Logout');
        $dashboardResponse->assertSee('Apakah Anda yakin ingin keluar dari akun admin?');
        $dashboardResponse->assertSee('Ya, Logout');
        
        $response = $this->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');
        $this->assertGuest('admin');
    }
    
}
