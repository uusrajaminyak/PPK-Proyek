<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportDamageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_report_creation_form(): void
    {
        $activeFacility = Facility::create([
            'nama_fasilitas' => 'Aula Serbaguna',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung Utama Lt. 1',
            'kapasitas' => 150,
            'status_fasilitas' => 'active',
        ]);

        $inactiveFacility = Facility::create([
            'nama_fasilitas' => 'Gudang Lama',
            'tipe' => 'Gudang',
            'lokasi' => 'Gedung Belakang',
            'kapasitas' => 10,
            'status_fasilitas' => 'inactive',
        ]);

        $response = $this->get(route('reports.create'));

        $response->assertStatus(200);
        $response->assertSee('Laporkan Kerusakan Fasilitas');
        $response->assertSee('Aula Serbaguna');
        $response->assertDontSee('Gudang Lama');
    }

    public function test_validation_errors_when_required_fields_are_missing(): void
    {
        $response = $this->post(route('reports.store'), []);

        $response->assertSessionHasErrors(['facility_id', 'kategori_laporan', 'deskripsi']);
    }

    public function test_validation_fails_if_description_is_too_short(): void
    {
        $facility = Facility::create([
            'nama_fasilitas' => 'Lab Fisika',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung B Lt. 2',
            'kapasitas' => 30,
            'status_fasilitas' => 'active',
        ]);

        $response = $this->post(route('reports.store'), [
            'facility_id' => $facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'pendek', // kurang dari 10 karakter
        ]);

        $response->assertSessionHasErrors(['deskripsi']);
    }

    public function test_user_can_submit_damage_report_with_photos(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $facility = Facility::create([
            'nama_fasilitas' => 'Lab Komputer 1',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung C',
            'kapasitas' => 40,
            'status_fasilitas' => 'active',
        ]);

        $photo1 = UploadedFile::fake()->create('rusak1.jpg', 100, 'image/jpeg');
        $photo2 = UploadedFile::fake()->create('rusak2.png', 100, 'image/png');

        $response = $this->actingAs($user)->post(route('reports.store'), [
            'facility_id' => $facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Monitor komputer nomor 12 tidak menyala sama sekali.',
            'fotos' => [$photo1, $photo2],
        ]);

        $response->assertRedirect(route('reports.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $user->id,
            'facility_id' => $facility->id,
            'kategori_laporan' => 'Hardware',
            'status_laporan' => 'baru',
        ]);

        $report = Report::where('facility_id', $facility->id)->first();
        $this->assertNotNull($report);
        $this->assertCount(2, $report->foto_paths);

        // Verify files stored in storage
        foreach ($report->foto_paths as $storedPath) {
            Storage::disk('public')->assertExists($storedPath);
        }
    }
}

