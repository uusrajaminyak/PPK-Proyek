<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Budi Pengguna',
            'email' => 'budi@kampus.ac.id',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        $this->facility = Facility::create([
            'nama_fasilitas' => 'Lab Multimedia',
            'tipe' => 'Laboratorium',
            'lokasi' => 'Gedung D Lt. 3',
            'kapasitas' => 35,
            'status_fasilitas' => 'active',
        ]);
    }

    public function test_user_can_view_own_damage_reports(): void
    {
        Report::create([
            'reporter_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Komputer PC-05 motherboard konslet.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat & Status Laporan');
        $response->assertSee('Lab Multimedia');
        $response->assertSee('Komputer PC-05 motherboard konslet.');
        $response->assertSee('Baru (Menunggu Verifikasi)');
    }

    public function test_user_only_sees_their_own_reports(): void
    {
        $otherUser = User::create([
            'name' => 'Siti Pengguna',
            'email' => 'siti@kampus.ac.id',
            'password' => bcrypt('password'),
            'role' => 'pengguna',
            'status_akun' => 'verified',
        ]);

        // Report by current user
        Report::create([
            'reporter_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Laporan milik Budi: AC bocor.',
            'status_laporan' => 'baru',
        ]);

        // Report by other user
        Report::create([
            'reporter_id' => $otherUser->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Laporan rahasia milik Siti: Pintu rusak.',
            'status_laporan' => 'baru',
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan milik Budi: AC bocor.');
        $response->assertDontSee('Laporan rahasia milik Siti: Pintu rusak.');
    }

    public function test_user_can_filter_reports_by_status(): void
    {
        Report::create([
            'reporter_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Hardware',
            'deskripsi' => 'Laporan masih baru.',
            'status_laporan' => 'baru',
        ]);

        Report::create([
            'reporter_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Fasilitas Umum',
            'deskripsi' => 'Laporan sudah selesai diperbaiki.',
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Sudah diperbaiki.',
        ]);

        // Filter: selesai
        $responseSelesai = $this->actingAs($this->user)->get(route('reports.index', ['status' => 'selesai']));
        $responseSelesai->assertStatus(200);
        $responseSelesai->assertSee('Laporan sudah selesai diperbaiki.');
        $responseSelesai->assertDontSee('Laporan masih baru.');

        // Filter: baru
        $responseBaru = $this->actingAs($this->user)->get(route('reports.index', ['status' => 'baru']));
        $responseBaru->assertStatus(200);
        $responseBaru->assertSee('Laporan masih baru.');
        $responseBaru->assertDontSee('Laporan sudah selesai diperbaiki.');
    }

    public function test_report_displays_resolution_note_when_available(): void
    {
        Report::create([
            'reporter_id' => $this->user->id,
            'facility_id' => $this->facility->id,
            'kategori_laporan' => 'Kelistrikan',
            'deskripsi' => 'Lampu sorot panggung putus.',
            'status_laporan' => 'selesai',
            'catatan_resolusi' => 'Bohlam LED 50W telah diganti baru oleh teknisi listrik.',
        ]);

        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Catatan Resolusi Petugas:');
        $response->assertSee('Bohlam LED 50W telah diganti baru oleh teknisi listrik.');
    }

    public function test_empty_state_displayed_when_user_has_no_reports(): void
    {
        $response = $this->actingAs($this->user)->get(route('reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada Laporan Kerusakan');
        $response->assertSee('Buat Laporan Baru Sekarang');
    }
}

