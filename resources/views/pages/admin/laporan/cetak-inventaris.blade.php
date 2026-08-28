<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Lab{{ $ruangan ? ' — ' . $ruangan->nama_ruangan : '' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            padding: 20px;
        }

        /* ─── Header ─── */
        .print-header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid #064e3b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .print-header img { width: 60px; }
        .print-header .institution { flex: 1; }
        .print-header .institution h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #064e3b;
        }
        .print-header .institution p { font-size: 10px; color: #555; margin-top: 2px; }
        .print-header .doc-label { text-align: right; font-size: 10px; color: #555; }
        .print-header .doc-label strong { display: block; font-size: 12px; color: #064e3b; }

        /* ─── Title ─── */
        .report-title { text-align: center; margin-bottom: 16px; }
        .report-title h1 {
            font-size: 15px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .report-title p { font-size: 11px; color: #555; margin-top: 4px; }

        /* ─── Summary Cards ─── */
        .summary-row {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }
        .summary-card {
            flex: 1;
            border: 1px solid #d1fae5;
            background: #ecfdf5;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
        }
        .summary-card .val {
            font-size: 20px;
            font-weight: bold;
            color: #064e3b;
        }
        .summary-card .lbl { font-size: 9px; color: #555; margin-top: 2px; }
        .summary-card.rusak { background: #fff7ed; border-color: #fed7aa; }
        .summary-card.rusak .val { color: #c2410c; }
        .summary-card.berat { background: #fef2f2; border-color: #fecaca; }
        .summary-card.berat .val { color: #b91c1c; }
        .summary-card.hilang { background: #f8fafc; border-color: #e2e8f0; }
        .summary-card.hilang .val { color: #475569; }

        /* ─── Table ─── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            border: 1px solid #a7f3d0;
        }
        thead { background: #d1fae5; }
        th {
            padding: 5px 7px;
            text-align: center;
            border: 1px solid #a7f3d0;
            font-weight: bold;
            color: #064e3b;
        }
        td { padding: 4px 7px; border: 1px solid #e0e0e0; vertical-align: middle; }
        tr:nth-child(even) td { background: #f0fdf4; }
        .td-no  { text-align: center; width: 28px; }
        .td-num { text-align: center; }
        .td-total { text-align: center; font-weight: bold; color: #065f46; }
        .badge-baik     { color: #166534; font-weight: bold; }
        .badge-ringan   { color: #92400e; font-weight: bold; }
        .badge-berat    { color: #991b1b; font-weight: bold; }
        .badge-hilang   { color: #475569; font-weight: bold; }

        /* ─── Ruangan Section Header ─── */
        .ruangan-header td {
            background: #064e3b;
            color: #fff;
            font-weight: bold;
            padding: 5px 10px;
        }

        /* ─── Footer ─── */
        .print-footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 60px;
            font-size: 10px;
        }
        .signature { text-align: center; }
        .signature .sig-line {
            width: 140px;
            border-bottom: 1px solid #333;
            margin: 50px auto 4px;
        }

        /* ─── Print ─── */
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; size: A4 landscape; }
        }
    </style>
</head>
<body>

    {{-- ─── Print Button ─── --}}
    <div class="no-print" style="text-align:right; margin-bottom:12px;">
        <button onclick="window.print()"
            style="background:#064e3b;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:12px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#e2e8f0;color:#333;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:12px;margin-left:8px;">
            ✕ Tutup
        </button>
    </div>

    {{-- ─── Kop ─── --}}
    <div class="print-header">
        <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" alt="Logo">
        <div class="institution">
            <h2>{{ env('APP_NAME', 'Sislab Fisika') }}</h2>
            <p>Laboratorium Fisika — UIN Ar-Raniry Banda Aceh</p>
        </div>
        <div class="doc-label">
            <strong>LAPORAN INVENTARIS LABORATORIUM</strong>
            <span>Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- ─── Judul ─── --}}
    <div class="report-title">
        <h1>Laporan Akhir Inventaris Laboratorium</h1>
        <p>{{ $ruangan ? 'Ruangan: ' . $ruangan->nama_ruangan : 'Seluruh Ruangan Laboratorium' }}</p>
    </div>

    {{-- ─── Summary Cards ─── --}}
    @php
        $totalBarang      = $barangs->count();
        $totalBaik        = $barangs->sum('stok_baik');
        $totalRingan      = $barangs->sum('stok_rusak_ringan');
        $totalBerat       = $barangs->sum('stok_rusak_berat');
        $totalHilang      = $barangs->sum('stok_hilang');
        $grandTotal       = $totalBaik + $totalRingan + $totalBerat + $totalHilang;
        $pctBaik          = $grandTotal > 0 ? round($totalBaik / $grandTotal * 100, 1) : 0;
    @endphp
    <div class="summary-row">
        <div class="summary-card">
            <div class="val">{{ $totalBarang }}</div>
            <div class="lbl">Jenis Barang</div>
        </div>
        <div class="summary-card">
            <div class="val">{{ $totalBaik }}</div>
            <div class="lbl">Kondisi Baik</div>
        </div>
        <div class="summary-card rusak">
            <div class="val">{{ $totalRingan }}</div>
            <div class="lbl">Rusak Ringan</div>
        </div>
        <div class="summary-card berat">
            <div class="val">{{ $totalBerat }}</div>
            <div class="lbl">Rusak Berat</div>
        </div>
        <div class="summary-card hilang">
            <div class="val">{{ $totalHilang }}</div>
            <div class="lbl">Hilang</div>
        </div>
        <div class="summary-card">
            <div class="val">{{ $pctBaik }}%</div>
            <div class="lbl">Tingkat Kelayakan</div>
        </div>
    </div>

    {{-- ─── Tabel Barang ─── --}}
    @php $grouped = $barangs->groupBy(fn($b) => $b->ruangan?->nama_ruangan ?? 'Tanpa Ruangan'); @endphp

    <table>
        <thead>
            <tr>
                <th class="td-no">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Kategori</th>
                @if(!$ruangan)<th>Ruangan</th>@endif
                <th class="td-num">Baik</th>
                <th class="td-num">R.Ringan</th>
                <th class="td-num">R.Berat</th>
                <th class="td-num">Hilang</th>
                <th class="td-num">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($grouped as $namaRuangan => $items)
                @if(!$ruangan)
                    <tr class="ruangan-header">
                        <td colspan="11">📍 {{ $namaRuangan }}</td>
                    </tr>
                @endif
                @foreach($items as $barang)
                    <tr>
                        <td class="td-no">{{ $no++ }}</td>
                        <td>{{ $barang->kode_barang }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->merk }}</td>
                        <td>{{ $barang->kategoriBarang?->nama_kategori }}</td>
                        @if(!$ruangan)<td>{{ $barang->ruangan?->nama_ruangan }}</td>@endif
                        <td class="td-num badge-baik">{{ $barang->stok_baik }}</td>
                        <td class="td-num badge-ringan">{{ $barang->stok_rusak_ringan }}</td>
                        <td class="td-num badge-berat">{{ $barang->stok_rusak_berat }}</td>
                        <td class="td-num badge-hilang">{{ $barang->stok_hilang }}</td>
                        <td class="td-total">{{ $barang->total_stok }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="{{ $ruangan ? 10 : 11 }}" style="text-align:center;padding:20px;color:#999;font-style:italic;">
                        Tidak ada data inventaris.
                    </td>
                </tr>
            @endforelse

            {{-- Total row --}}
            @if($barangs->count() > 0)
            <tr style="background:#d1fae5; font-weight:bold;">
                <td colspan="{{ $ruangan ? 5 : 6 }}" style="text-align:right; color:#064e3b;">TOTAL</td>
                <td class="td-total badge-baik">{{ $totalBaik }}</td>
                <td class="td-total badge-ringan">{{ $totalRingan }}</td>
                <td class="td-total badge-berat">{{ $totalBerat }}</td>
                <td class="td-total badge-hilang">{{ $totalHilang }}</td>
                <td class="td-total">{{ $grandTotal }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- ─── Tanda Tangan ─── --}}
    <div class="print-footer">
        <div class="signature">
            <div>Banda Aceh, {{ now()->translatedFormat('d F Y') }}</div>
            <div class="sig-line"></div>
            <div><strong>Kepala Laboratorium</strong></div>
        </div>
    </div>

</body>
</html>
