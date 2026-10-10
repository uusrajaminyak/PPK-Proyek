<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Buat Akun');
        $response->assertSee('Kembali ke Login');
        $response->assertSee('Masukkan Email');
        $response->assertSee('Masukkan Password');
        $response->assertSee('Konfirmasi Password');
    }

    public function test_authenticated_user_is_redirected_from_registration_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('home'));
    }

    public function test_new_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'email' => 'mahasiswa@undip.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'mahasiswa@undip.ac.id',
            'name' => 'mahasiswa',
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);
    }

    public function test_registration_requires_email_and_password(): void
    {
        $response = $this->post(route('register'), [
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_registration_validates_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'email' => 'test@undip.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'mismatched123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_registration_prevents_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'duplikat@undip.ac.id',
        ]);

        $response = $this->post(route('register'), [
            'email' => 'duplikat@undip.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
