<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\Report;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->get('month', 9); // September 2026 default
        $selectedYear  = (int) $request->get('year', 2026);
        $exportFormat  = strtoupper($request->get('format', 'PDF'));

        $facilities = Facility::withTrashed()->withCount([
            'reservations as total_direservasi' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('start_time', $selectedMonth)
                    ->whereYear('start_time', $selectedYear);
            },
            'reports as total_dilaporkan' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('created_at', $selectedMonth)
                    ->whereYear('created_at', $selectedYear);
            }
        ])->get();

        $maxReservations = max($facilities->max('total_direservasi') ?? 1, 1);
        $maxReports      = max($facilities->max('total_dilaporkan') ?? 1, 1);

        $occupancyData = $facilities->map(function ($facility) use ($maxReservations) {
            return [
                'id'         => $facility->id,
                'nama'       => $facility->nama_fasilitas,
                'count'      => $facility->total_direservasi,
                'percentage' => round(($facility->total_direservasi / $maxReservations) * 100),
            ];
        })->sortByDesc('count')->values();

        $reportFrequencyData = $facilities->map(function ($facility) use ($maxReports) {
            return [
                'id'         => $facility->id,
                'nama'       => $facility->nama_fasilitas,
                'count'      => $facility->total_dilaporkan,
                'percentage' => round(($facility->total_dilaporkan / $maxReports) * 100),
            ];
        })->sortByDesc('count')->values();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $years = [2024, 2025, 2026, 2027];

        return view('admin.recap.index', compact(
            'facilities',
            'occupancyData',
            'reportFrequencyData',
            'selectedMonth',
            'selectedYear',
            'exportFormat',
            'months',
            'years'
        ));
    }

    public function showFacility(Request $request, $facilityId)
    {
        $selectedMonth = (int) $request->get('month', 9);
        $selectedYear  = (int) $request->get('year', 2026);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $years = [2024, 2025, 2026, 2027];

        $allFacilities = Facility::withTrashed()->orderBy('nama_fasilitas')->get();

        if ($facilityId === 'all') {
            $facility = null;

            $reservations = Reservation::with(['facility', 'user'])
                ->whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)
                ->latest()
                ->paginate(5, ['*'], 'reservations_page')
                ->withQueryString();

            $reports = Report::with(['facility', 'reporter'])
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->latest()
                ->paginate(5, ['*'], 'reports_page')
                ->withQueryString();

            $totalReservations = Reservation::whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)->count();

            $approvedReservations = Reservation::whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)
                ->where('status_reservasi', 'approved')->count();

            $totalReports = Report::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)->count();

            $resolvedReports = Report::whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status_laporan', 'selesai')->count();
        } else {
            $facility = Facility::withTrashed()->findOrFail($facilityId);

            $reservations = $facility->reservations()
                ->with('user')
                ->whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)
                ->latest()
                ->paginate(5, ['*'], 'reservations_page')
                ->withQueryString();

            $reports = $facility->reports()
                ->with('reporter')
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->latest()
                ->paginate(5, ['*'], 'reports_page')
                ->withQueryString();

            $totalReservations = $facility->reservations()
                ->whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)->count();

            $approvedReservations = $facility->reservations()
                ->whereMonth('start_time', $selectedMonth)
                ->whereYear('start_time', $selectedYear)
                ->where('status_reservasi', 'approved')->count();

            $totalReports = $facility->reports()
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)->count();

            $resolvedReports = $facility->reports()
                ->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->where('status_laporan', 'selesai')->count();
        }

        return view('admin.recap.show', compact(
            'facility',
            'facilityId',
            'allFacilities',
            'reservations',
            'reports',
            'selectedMonth',
            'selectedYear',
            'totalReservations',
            'approvedReservations',
            'totalReports',
            'resolvedReports',
            'months',
            'years'
        ));
    }

    public function export(Request $request)
    {
        $selectedMonth = (int) $request->get('month', 9);
        $selectedYear  = (int) $request->get('year', 2026);
        $format        = strtoupper($request->get('format', 'CSV'));

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $facilities = Facility::withTrashed()->withCount([
            'reservations as total_direservasi' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('start_time', $selectedMonth)
                    ->whereYear('start_time', $selectedYear);
            },
            'reports as total_dilaporkan' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('created_at', $selectedMonth)
                    ->whereYear('created_at', $selectedYear);
            }
        ])->get();

        // 1. PDF Export Branch
        if ($format === 'PDF') {
            return response()->view('admin.recap.export_pdf', compact(
                'facilities',
                'selectedMonth',
                'selectedYear',
                'months'
            ));
        }

        // 2. XLSX / Excel Spreadsheet XML Branch
        if ($format === 'XLSX') {
            $fileName = "Recap_Fasilitas_{$selectedMonth}_{$selectedYear}.xls";

            $headers = [
                "Content-type"        => "application/vnd.ms-excel; charset=UTF-8",
                "Content-Disposition" => "attachment; filename={$fileName}",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            return response()->stream(function () use ($facilities, $selectedMonth, $selectedYear, $months) {
                echo 'xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
                echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
                echo '<h2>REKAP OKUPANSI & LAPORAN FASILITAS</h2>';
                echo '<p>Periode: ' . $months[$selectedMonth] . ' ' . $selectedYear . '</p>';
                echo '<table border="1" style="border-collapse:collapse;">';
                echo '<tr style="background-color:#001248; color:#ffffff; font-weight:bold;">';
                echo '<th>Nama Fasilitas</th><th>Tipe</th><th>Total Direservasi</th><th>Total Dilaporkan</th>';
                echo '</tr>';

                foreach ($facilities as $facility) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($facility->nama_fasilitas) . '</td>';
                    echo '<td>' . htmlspecialchars($facility->tipe ?? 'Ruang Kelas/Aula') . '</td>';
                    echo '<td style="text-align:center;">' . $facility->total_direservasi . '</td>';
                    echo '<td style="text-align:center;">' . $facility->total_dilaporkan . '</td>';
                    echo '</tr>';
                }

                echo '</table></body></html>';
            }, 200, $headers);
        }

        // 3. Default CSV Branch
        $fileName = "Recap_Fasilitas_{$selectedMonth}_{$selectedYear}.csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function () use ($facilities) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['Nama Fasilitas', 'Tipe', 'Total Direservasi', 'Total Dilaporkan']);

            foreach ($facilities as $facility) {
                fputcsv($file, [
                    $facility->nama_fasilitas,
                    $facility->tipe ?? 'Ruang Kelas/Aula',
                    $facility->total_direservasi,
                    $facility->total_dilaporkan
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }
}
