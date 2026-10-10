<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficerFacilityStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;
    private Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Petugas Fasilitas',
            'email' => 'petugas@kampus.ac.id',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'status_akun' => 'verified',
        ]);

        $this->facility = Facility::create([
            'nama_fasilitas' => 'Laboratorium Jaringan Komputer',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung E Lt. 2',
            'kapasitas' => 30,
            'status_fasilitas' => 'active',
        ]);
    }

    public function test_officer_can_view_facilities_status_page(): void
    {
        $response = $this->actingAs($this->petugas)->get(route('officer.facilities.index'));

        $response->assertStatus(200);
        $response->assertSee('Status Operasional Fasilitas');
        $response->assertSee('Laboratorium Jaringan Komputer');
        $response->assertSee('Aktif');
    }

    public function test_officer_can_mark_facility_as_in_repair_directly(): void
    {
        $this->assertEquals('active', $this->facility->status_fasilitas);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.facilities.update-status', $this->facility->id), [
                'status_fasilitas' => 'in_repair',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status_fasilitas' => 'in_repair',
        ]);
    }

    public function test_officer_can_restore_facility_to_active_directly(): void
    {
        $this->facility->update(['status_fasilitas' => 'in_repair']);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.facilities.update-status', $this->facility->id), [
                'status_fasilitas' => 'active',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status_fasilitas' => 'active',
        ]);
    }

    public function test_validation_rejects_invalid_facility_status(): void
    {
        // Petugas cannot set 'inactive' (only admin) or unknown values
        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.facilities.update-status', $this->facility->id), [
                'status_fasilitas' => 'inactive',
            ]);

        $response->assertSessionHasErrors(['status_fasilitas']);

        $responseInvalid = $this->actingAs($this->petugas)
            ->patch(route('officer.facilities.update-status', $this->facility->id), [
                'status_fasilitas' => 'broken',
            ]);

        $responseInvalid->assertSessionHasErrors(['status_fasilitas']);
    }

    public function test_officer_can_sync_facility_status_to_in_repair_when_processing_report(): void
    {
        $user = User::create([
            'name' => 'Mahasiswa Pelapor',
            'email' => 'mhs@test.com',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $report = Report::create([
            'reporter_id' => $user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Router switch utama terbakar.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'diproses',
                'update_facility_status' => 'in_repair',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status_laporan' => 'diproses',
        ]);

        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status_fasilitas' => 'in_repair',
        ]);
    }

    public function test_officer_can_sync_facility_status_to_active_when_completing_report(): void
    {
        $this->facility->update(['status_fasilitas' => 'in_repair']);

        $user = User::create([
            'name' => 'Mahasiswa Pelapor',
            'email' => 'mhs2@test.com',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $report = Report::create([
            'reporter_id' => $user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Router switch utama terbakar.',
            'status_laporan' => 'diproses',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'selesai',
                'catatan_resolusi' => 'Switch baru 24-port telah dipasang dan konfigurasi VLAN selesai.',
                'update_facility_status' => 'active',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status_laporan' => 'selesai',
        ]);

        $this->assertDatabaseHas('facilities', [
            'id' => $this->facility->id,
            'status_fasilitas' => 'active',
        ]);
    }
}

