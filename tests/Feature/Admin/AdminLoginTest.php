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
        // Arrange: buat akun admin sesuai pre-condition
        $admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '123456',
        ]);

        // Assert: diarahkan ke dashboard dan terautentikasi pada guard 'admin'
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    /** @test */
    public function LG_02()
    {
        // Arrange: pastikan ada akun admin (pre-condition)
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login dengan username & password kosong
        $response = $this->post('/admin/login', [
            'username' => '',
            'password' => '',
        ]);

        // Assert: tetap di halaman login (redirect back) dan ada error validasi untuk kedua field
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest('admin');
    }
    /** @test */
    public function LG_03()
    {
        // Arrange: buat akun admin sesuai pre-condition
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'), // password asli
        ]);

        // Act: kirim request login dengan password SALAH
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '1234', // password salah
        ]);

        // Assert: tetap di halaman login dan muncul pesan error
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_04()
    {
        // Arrange: buat akun admin sesuai pre-condition
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login dengan username SALAH
        $response = $this->post('/admin/login', [
            'username' => 'abc',      // username tidak valid
            'password' => '123456',   // password valid
        ]);

        // Assert: tetap di halaman login dan muncul pesan error
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_05()
    {
        // Arrange: buat akun admin sesuai pre-condition
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login dengan username kosong
        $response = $this->post('/admin/login', [
            'username' => '',
            'password' => '123456',
        ]);

        // Assert: validasi gagal dengan pesan khusus "Username harus diisi"
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username' => 'Username harus diisi']);

        // Pastikan user tetap guest
        $this->assertGuest('admin');
    }
    
    /** @test */
    public function LG_06()
    {
        // Arrange: buat akun admin
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login dengan password kosong
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => '',
        ]);

        // Assert: sistem harus redirect dan memunculkan error pada field password
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);

        // Assert: admin tidak boleh login
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_07()
    {
        // Arrange: buat akun admin dengan username lowercase
        $admin = Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: kirim request login dengan USERNAME kapital
        $response = $this->post('/admin/login', [
            'username' => 'ADMIN', // Caps Lock aktif
            'password' => '123456',
        ]);

        // Assert: login harus gagal dan tetap di halaman login
        $response->assertStatus(302);
        $response->assertSessionHasErrors(); // harus ada pesan error "username/password salah"

        // Assert: user tidak berhasil login
        $this->assertGuest('admin');
    }

    /** @test */
    public function LG_08()
    {
        // Arrange: buat akun admin
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

        // Act: user login dengan username emoji
        $response = $this->post('/admin/login', [
            'username' => '😊😊😊',
            'password' => '123456',
        ]);

        // Assert: login gagal (redirect back)
        $response->assertStatus(302);

        // Cek bahwa validasi memunculkan pesan khusus
        $response->assertSessionHasErrors([
            'username' => 'Gunakan format yang benar.'
        ]);

        // User tetap guest
        $this->assertGuest('admin');
    }
}
