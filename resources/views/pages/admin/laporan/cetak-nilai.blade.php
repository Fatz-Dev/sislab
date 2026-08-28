<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Nilai Praktikum{{ $semester ? ' — ' . $semester->nama_semester : '' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            padding: 20px;
        }

        /* ─── Cover / Header ─── */
        .print-header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .print-header img { width: 60px; }
        .print-header .institution { flex: 1; }
        .print-header .institution h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a5f;
        }
        .print-header .institution p { font-size: 10px; color: #555; margin-top: 2px; }
        .print-header .doc-label {
            text-align: right;
            font-size: 10px;
            color: #555;
        }
        .print-header .doc-label strong { display: block; font-size: 12px; color: #1e3a5f; }

        /* ─── Report Title ─── */
        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .report-title h1 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title p { font-size: 11px; color: #555; margin-top: 4px; }

        /* ─── Kelas Block ─── */
        .kelas-block { margin-bottom: 28px; page-break-inside: avoid; }
        .kelas-header {
            background: #1e3a5f;
            color: #fff;
            padding: 6px 10px;
            border-radius: 4px 4px 0 0;
            font-weight: bold;
            font-size: 11px;
        }
        .kelas-meta {
            display: flex;
            gap: 24px;
            padding: 6px 10px;
            background: #eef2f7;
            font-size: 10px;
            color: #444;
            border: 1px solid #d0d8e4;
            border-top: none;
        }
        .kelas-meta span strong { color: #1e3a5f; }

        /* ─── Table ─── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            border: 1px solid #d0d8e4;
            border-top: none;
        }
        thead { background: #dce6f0; }
        th {
            padding: 5px 7px;
            text-align: center;
            border: 1px solid #c8d5e4;
            font-weight: bold;
            color: #1e3a5f;
        }
        td {
            padding: 4px 7px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        tr:nth-child(even) td { background: #f7f9fc; }
        .td-no  { text-align: center; width: 28px; }
        .td-nim { text-align: center; white-space: nowrap; }
        .td-val { text-align: center; }
        .td-avg { text-align: center; font-weight: bold; color: #1e3a5f; }
        .empty-row td { text-align: center; color: #999; font-style: italic; padding: 10px; }

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

        /* ─── Print Media ─── */
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
            @page { margin: 1.5cm; size: A4; }
        }
    </style>
</head>
<body>

    {{-- ─── Print Button (hanya tampil di layar) ─── --}}
    <div class="no-print" style="text-align:right; margin-bottom:12px;">
        <button onclick="window.print()"
            style="background:#1e3a5f;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:12px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            style="background:#e2e8f0;color:#333;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:12px;margin-left:8px;">
            ✕ Tutup
        </button>
    </div>

    {{-- ─── Kop Surat ─── --}}
    <div class="print-header">
        <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" alt="Logo">
        <div class="institution">
            <h2>{{ env('APP_NAME', 'Sislab Fisika') }}</h2>
            <p>Laboratorium Fisika — UIN Ar-Raniry Banda Aceh</p>
        </div>
        <div class="doc-label">
            <strong>REKAP NILAI PRAKTIKUM</strong>
            <span>Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- ─── Judul ─── --}}
    <div class="report-title">
        <h1>Rekapitulasi Nilai Praktikum</h1>
        <p>{{ $semester ? 'Semester: ' . $semester->nama_semester : 'Semua Semester' }}</p>
    </div>

    {{-- ─── Konten Per Kelas ─── --}}
    @forelse($kelasList as $kelas)
        <div class="kelas-block">
            <div class="kelas-header">
                {{ $kelas->nama_kelas }}
                @if($kelas->semester) — {{ $kelas->semester->nama_semester }} @endif
            </div>
            <div class="kelas-meta">
                <span>Dosen: <strong>{{ $kelas->dosen?->name ?? 'Belum ditentukan' }}</strong></span>
                <span>Laboran: <strong>{{ $kelas->laboran?->name ?? 'Belum ditentukan' }}</strong></span>
                <span>Mahasiswa: <strong>{{ $kelas->approvedMahasiswas->count() }}</strong></span>
                <span>Jumlah Tugas: <strong>{{ $kelas->tugasLaporans->count() }}</strong></span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th class="td-no">No</th>
                        <th>Nama Mahasiswa</th>
                        <th class="td-nim">NIM</th>
                        @foreach($kelas->tugasLaporans as $tugas)
                            <th class="td-val" title="{{ $tugas->judul_tugas }}">
                                T{{ $loop->iteration }}
                            </th>
                        @endforeach
                        <th class="td-avg">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas->approvedMahasiswas as $mhs)
                        @php
                            $totalNilai = 0;
                            $countNilai = 0;
                        @endphp
                        <tr>
                            <td class="td-no">{{ $loop->iteration }}</td>
                            <td>{{ $mhs->name }}</td>
                            <td class="td-nim">{{ $mhs->mahasiswaProfile?->nim }}</td>
                            @foreach($kelas->tugasLaporans as $tugas)
                                @php
                                    $n = $mhs->nilaiMap[$tugas->id] ?? null;
                                    if ($n) { $totalNilai += $n->nilai; $countNilai++; }
                                @endphp
                                <td class="td-val">{{ $n?->nilai }}</td>
                            @endforeach
                            <td class="td-avg">
                                {{ $countNilai > 0 ? number_format($totalNilai / $countNilai, 1) : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="{{ 3 + $kelas->tugasLaporans->count() + 1 }}">
                                Belum ada mahasiswa terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Keterangan judul tugas --}}
            @if($kelas->tugasLaporans->count() > 0)
                <div style="font-size:9px;color:#666;padding:4px 8px;background:#f0f4f8;border:1px solid #d0d8e4;border-top:none;">
                    Keterangan:
                    @foreach($kelas->tugasLaporans as $tugas)
                        T{{ $loop->iteration }} = {{ $tugas->judul_tugas }}{{ !$loop->last ? '; ' : '' }}
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div style="text-align:center; padding:40px; color:#999; font-style:italic;">
            Tidak ada data kelas praktikum untuk filter yang dipilih.
        </div>
    @endforelse

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
