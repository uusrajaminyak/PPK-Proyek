<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityAvailabilityVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_facilities_are_hidden_from_public_and_officer_lists(): void
    {
        $officer = $this->createUser('petugas');
        $this->createFacility('Fasilitas Aktif', 'active');
        $inactiveFacility = $this->createFacility('Fasilitas Nonaktif Rahasia', 'inactive');

        $publicResponse = $this->get(route('facilities.index'));
        $officerResponse = $this->actingAs($officer)->get(route('officer.facilities.index'));

        $publicResponse->assertSee('Fasilitas Aktif')->assertDontSee('Fasilitas Nonaktif Rahasia');
        $officerResponse->assertSee('Fasilitas Aktif')->assertDontSee('Fasilitas Nonaktif Rahasia');
        $this->assertModelExists($inactiveFacility);
    }

    public function test_users_and_officers_receive_not_found_for_inactive_facility_routes(): void
    {
        $user = $this->createUser('pengguna');
        $officer = $this->createUser('petugas');
        $inactiveFacility = $this->createFacility('Fasilitas Nonaktif', 'inactive');

        $reservationResponse = $this->actingAs($user)->get(route('reservations.create', $inactiveFacility));
        $scheduleResponse = $this->get(route('reservations.schedule', $inactiveFacility));
        $officerFacilityResponse = $this->actingAs($officer)->get(route('officer.facilities.show', $inactiveFacility));

        $reservationResponse->assertNotFound();
        $scheduleResponse->assertNotFound();
        $officerFacilityResponse->assertNotFound();
    }

    public function test_users_cannot_create_reservations_or_reports_for_inactive_facilities(): void
    {
        $user = $this->createUser('pengguna');
        $inactiveFacility = $this->createFacility('Fasilitas Nonaktif', 'inactive');

        $reservationResponse = $this->actingAs($user)->post(route('reservations.store'), [
            'facility_id' => $inactiveFacility->id,
            'tanggal' => now()->addDay()->toDateString(),
            'slot_start' => '09:00',
            'slot_end' => '10:00',
            'tujuan_penggunaan' => 'Diskusi kelompok mahasiswa.',
        ]);
        $reportResponse = $this->actingAs($user)->post(route('reports.store'), [
            'facility_id' => $inactiveFacility->id,
            'kategori_laporan' => 'Kerusakan/Kendala Fasilitas',
            'deskripsi' => 'Perlu perbaikan pada bagian fasilitas ini.',
        ]);

        $reservationResponse->assertSessionHasErrors('facility_id');
        $reportResponse->assertSessionHasErrors('facility_id');
        $this->assertDatabaseMissing('reservations', ['facility_id' => $inactiveFacility->id]);
        $this->assertDatabaseMissing('reports', ['facility_id' => $inactiveFacility->id]);
    }

    public function test_admin_can_mark_facility_inactive_and_petugas_cannot(): void
    {
        $admin = $this->createUser('admin');
        $officer = $this->createUser('petugas');
        $facility = $this->createFacility('Fasilitas Untuk Dinonaktifkan', 'active');

        $adminResponse = $this->actingAs($admin)
            ->patch(route('officer.facilities.update-status', $facility), ['status_fasilitas' => 'inactive']);

        $adminResponse->assertRedirect();
        $this->assertDatabaseHas('facilities', ['id' => $facility->id, 'status_fasilitas' => 'inactive']);

        $officerResponse = $this->actingAs($officer)
            ->patch(route('officer.facilities.update-status', $facility), ['status_fasilitas' => 'inactive']);

        $officerResponse->assertForbidden();
        $this->assertDatabaseHas('facilities', ['id' => $facility->id, 'status_fasilitas' => 'inactive']);
    }

    public function test_petugas_cannot_mark_a_facility_inactive_through_report_processing(): void
    {
        $officer = $this->createUser('petugas');
        $reporter = $this->createUser('pengguna');
        $facility = $this->createFacility('Fasilitas Dalam Laporan', 'active');
        $report = Report::create([
            'reporter_id' => $reporter->id,
            'facility_id' => $facility->id,
            'kategori_laporan' => 'Kerusakan/Kendala Fasilitas',
            'deskripsi' => 'Ada kerusakan pada bagian fasilitas yang perlu diperiksa.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($officer)->patch(route('officer.reports.update', $report), [
            'status_laporan' => 'diproses',
            'update_facility_status' => 'inactive',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('facilities', ['id' => $facility->id, 'status_fasilitas' => 'active']);
    }

    private function createUser(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
            'status_akun' => 'verified',
        ]);
    }

    private function createFacility(string $name, string $status): Facility
    {
        return Facility::create([
            'nama_fasilitas' => $name,
            'tipe' => 'Ruang Kelas',
            'lokasi' => 'Gedung A Lt. 1',
            'kapasitas' => 30,
            'status_fasilitas' => $status,
        ]);
    }
}
