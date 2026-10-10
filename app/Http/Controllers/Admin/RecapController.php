<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) $request->get('month', 9); // September 2026 default
        $selectedYear  = (int) $request->get('year', 2026);
        $exportFormat  = strtoupper($request->get('format', 'PDF'));

        // Query facilities with aggregated counts matching ERD schema
        $facilities = Facility::withCount([
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
                'nama' => $facility->nama_fasilitas,
                'count' => $facility->total_direservasi,
                'percentage' => round(($facility->total_direservasi / $maxReservations) * 100),
            ];
        })->sortByDesc('count')->values();

        $reportFrequencyData = $facilities->map(function ($facility) use ($maxReports) {
            return [
                'nama' => $facility->nama_fasilitas,
                'count' => $facility->total_dilaporkan,
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

        // Updated view path: admin.recap.index
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

    public function export(Request $request): StreamedResponse
    {
        $selectedMonth = (int) $request->get('month', 9);
        $selectedYear  = (int) $request->get('year', 2026);
        $format        = strtoupper($request->get('format', 'CSV'));

        $facilities = Facility::withCount([
            'reservations as total_direservasi' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('start_time', $selectedMonth)
                    ->whereYear('start_time', $selectedYear);
            },
            'reports as total_dilaporkan' => function ($query) use ($selectedMonth, $selectedYear) {
                $query->whereMonth('created_at', $selectedMonth)
                    ->whereYear('created_at', $selectedYear);
            }
        ])->get();

        $fileName = "Recap_Fasilitas_{$selectedMonth}_{$selectedYear}." . strtolower($format === 'PDF' ? 'csv' : $format);

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function () use ($facilities) {
            $file = fopen('php://output', 'w');
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
