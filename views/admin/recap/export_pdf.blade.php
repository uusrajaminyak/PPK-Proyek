<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap_Fasilitas_{{ $selectedMonth }}_{{ $selectedYear }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #001248;
            background: #ffffff;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            border-bottom: 2px solid #001248;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-size: 18px;
            font-weight: 800;
            color: #001248;
            margin: 0;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .meta-item {
            font-size: 11px;
        }

        .meta-item span {
            font-weight: 700;
            color: #001248;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #001248;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
            padding: 8px 12px;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #001248;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4 portrait;
                margin: 15mm;
            }
        }
    </style>
</head>

<body>

    <!-- Toolbar for manual triggers -->
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="background: #001248; color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;">
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Official Report Header -->
    <div class="header">
        <div>
            <h1 class="title">LAPORAN REKAP FASILITAS FSM</h1>
            <p class="subtitle">Fakultas Sains dan Matematika (FSM), Universitas Diponegoro</p>
        </div>
    </div>

    <!-- Period & Summary Metadata -->
    <div class="meta-box">
        <!-- Period & Summary Metadata -->
        @php
        $nowWib = \Illuminate\Support\Carbon::now('Asia/Jakarta');
        $monthsIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $tanggalCetakWib = $nowWib->format('d') . ' ' . $monthsIndo[$nowWib->month] . ' ' . $nowWib->format('Y, H:i');
        @endphp

        <div class="meta-box">
            <div class="meta-item">Periode Laporan: <span>{{ $months[$selectedMonth] }} {{ $selectedYear }}</span></div>
            <div class="meta-item">Total Fasilitas: <span>{{ $facilities->count() }}</span></div>
            <div class="meta-item">Tanggal Cetak: <span>{{ $tanggalCetakWib }} WIB</span></div>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Fasilitas</th>
                <th style="width: 25%;">Tipe / Kategori</th>
                <th style="width: 17%; text-align: center;">Total Reservasi</th>
                <th style="width: 18%; text-align: center;">Total Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $index => $facility)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $facility->nama_fasilitas }}</strong></td>
                <td>{{ $facility->tipe ?? 'Ruang Kelas / Aula' }}</td>
                <td style="text-align: center;">{{ $facility->total_direservasi }} kali</td>
                <td style="text-align: center;">{{ $facility->total_dilaporkan }} laporan</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">
                    Tidak ada data rekap fasilitas untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Fasilita FSM, sistem informasi fasilitas Fakultas Sains dan Matematika.
    </div>

    <script>
        // Auto trigger print dialog on page load for seamless PDF saving
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>
