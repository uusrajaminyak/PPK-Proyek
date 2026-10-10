<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficerReportStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $petugas;
    private User $userA;
    private User $userB;
    private Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->petugas = User::create([
            'name' => 'Petugas Bambang',
            'email' => 'bambang@petugas.ac.id',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'status_akun' => 'verified',
        ]);

        $this->userA = User::create([
            'name' => 'Mahasiswa A',
            'email' => 'mhs_a@student.ac.id',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $this->userB = User::create([
            'name' => 'Mahasiswa B',
            'email' => 'mhs_b@student.ac.id',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $this->facility = Facility::create([
            'nama_fasilitas' => 'Aula Serbaguna FSM',
            'tipe' => 'Aula',
            'lokasi' => 'Gedung A',
            'kapasitas' => 200,
            'status_fasilitas' => 'active',
        ]);
    }

    public function test_officer_can_view_all_reports_queue(): void
    {
        Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Laporan dari User A: Sound system dengung.',
            'status_laporan' => 'baru',
        ]);

        Report::create([
            'reporter_id' => $this->userB->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Laporan dari User B: Kursi patah.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('officer.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Laporan Kerusakan');
        $response->assertSee('Laporan dari User A: Sound system dengung.');
        $response->assertSee('Laporan dari User B: Kursi patah.');
        $response->assertSee('Mahasiswa A');
        $response->assertSee('Mahasiswa B');
    }

    public function test_officer_can_update_report_status_to_diproses(): void
    {
        $report = Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Proyektor mati total.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'diproses',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status_laporan' => 'diproses',
        ]);
    }

    public function test_validation_requires_resolution_note_when_marking_as_selesai(): void
    {
        $report = Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'AC mati.',
            'status_laporan' => 'diproses',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'selesai',
                'catatan_resolusi' => '', // kosong, padahal wajib saat ditutup
            ]);

        $response->assertSessionHasErrors(['catatan_resolusi']);
    }

    public function test_validation_requires_resolution_note_when_marking_as_ditolak(): void
    {
        $report = Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'AC mati.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'ditolak',
                'catatan_resolusi' => null,
            ]);

        $response->assertSessionHasErrors(['catatan_resolusi']);
    }

    public function test_officer_can_close_report_as_selesai_with_resolution_note(): void
    {
        $report = Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Kelistrikan',
            'deskripsi' => 'Stop kontak aula meledak.',
            'status_laporan' => 'diproses',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'selesai',
                'catatan_resolusi' => 'Unit MCB dan stop kontak telah diganti baru oleh teknisi PLN kampus.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Unit MCB dan stop kontak telah diganti baru oleh teknisi PLN kampus.',
        ]);
    }

    public function test_officer_can_reject_report_with_reason(): void
    {
        $report = Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Laptop pribadi tidak bisa konek.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->petugas)
            ->patch(route('officer.reports.update', $report->id), [
                'status_laporan' => 'ditolak',
                'catatan_resolusi' => 'Laporan ditolak karena kendala ada pada perangkat pribadi pelapor, bukan fasilitas kampus.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status_laporan' => 'ditolak',
            'catatan_resolusi' => 'Laporan ditolak karena kendala ada pada perangkat pribadi pelapor, bukan fasilitas kampus.',
        ]);
    }

    public function test_officer_can_filter_queue_by_status(): void
    {
        Report::create([
            'reporter_id' => $this->userA->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Laporan baru antrean.',
            'status_laporan' => 'baru',
        ]);

        Report::create([
            'reporter_id' => $this->userB->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Laporan sudah beres.',
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Sudah beres.',
        ]);

        $response = $this->actingAs($this->petugas)
            ->get(route('officer.reports.index', ['status' => 'baru']));

        $response->assertStatus(200);
        $response->assertSee('Laporan baru antrean.');
        $response->assertDontSee('Laporan sudah beres.');
    }
}

