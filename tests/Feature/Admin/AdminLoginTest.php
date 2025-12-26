<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function LG_01()
    {
        $admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '123456',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    /** @test */
    public function LG_02()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest('admin');
    }
    /** @test */
    public function LG_03()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_04()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'abc',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_05()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => '',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username' => 'Username harus diisi']);
        $this->assertGuest('admin');
    }
    
    /** @test */
    public function LG_06()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_07()
    {
        $admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => 'ADMIN',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_08()
    {
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        $response = $this->post('/admin/login', [
            'username' => '😊😊😊',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'username' => 'Gunakan format yang benar.'
        ]);

        $this->assertGuest('admin');
    }
}
