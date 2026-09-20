<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Praktikum — {{ $kelas->nama_kelas }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background-color: #f1f5f9;
            padding: 20px 0;
            line-height: 1.3;
        }

        /* ─── Floating Toolbar Atas (Hanya tampil di layar) ─── */
        .no-print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: #1e293b;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
        }
        .no-print-bar .title-bar {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .no-print-bar .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            font-size: 12px;
        }
        .btn-print { background: #2563eb; color: #fff; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-pdf { background: #e11d48; color: #fff; }
        .btn-pdf:hover { background: #be123c; }
        .btn-close { background: #475569; color: #fff; }
        .btn-close:hover { background: #334155; }
        .filter-chip {
            padding: 5px 12px;
            border-radius: 20px;
            background: rgba(255,255,255,0.1);
            color: #cbd5e1;
            text-decoration: none;
            font-size: 11px;
            font-weight: 500;
        }
        .filter-chip.active {
            background: #38bdf8;
            color: #0f172a;
            font-weight: 700;
        }

        /* ─── Kertas Lembar Kerja Standar A4 Landscape ─── */
        .paper-sheet {
            width: 297mm;
            min-height: 210mm;
            margin: 60px auto 30px;
            background: #fff;
            padding: 15mm 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 4px;
        }

        /* ─── KOP Surat Formal Institusi ─── */
        .kop-container {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .kop-logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text h3 {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .kop-text h2 {
            font-size: 15pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kop-text h4 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .kop-text p {
            font-size: 9.5pt;
            font-style: italic;
            color: #222;
        }

        /* ─── Judul Dokumen ─── */
        .doc-title {
            text-align: center;
            margin: 14px 0 16px;
        }
        .doc-title h1 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 10pt;
            margin-top: 2px;
        }

        /* ─── Identitas Kelas ─── */
        .meta-grid {
            width: 100%;
            margin-bottom: 16px;
            font-size: 10.5pt;
            border-collapse: collapse;
        }
        .meta-grid td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .meta-grid td.label {
            font-weight: bold;
            width: 18%;
        }
        .meta-grid td.colon {
            width: 2%;
            text-align: center;
        }
        .meta-grid td.val {
            width: 30%;
        }

        /* ─── Section Divider ─── */
        .section-header {
            font-size: 11pt;
            font-weight: bold;
            margin: 18px 0 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        /* ─── Tabel Akademik Hitam Putih ─── */
        table.academic-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 16px;
        }
        table.academic-table th, 
        table.academic-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            line-height: 1.2;
        }
        table.academic-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            font-size: 9pt;
        }
        table.academic-table td.center {
            text-align: center;
        }
        table.academic-table td.right {
            text-align: right;
        }
        table.academic-table tr.summary-row th {
            background-color: #e5e5e5;
            font-weight: bold;
        }

        /* ─── Legenda / Catatan Kaki ─── */
        .legend-box {
            font-size: 9pt;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* ─── Pengesahan / Tanda Tangan ─── */
        .signature-container {
            width: 100%;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }
        .signature-box {
            width: 260px;
            text-align: center;
        }
        .signature-box .date-line {
            margin-bottom: 4px;
        }
        .signature-box .role-line {
            margin-bottom: 65px;
        }
        .signature-box .name-line {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-box .nip-line {
            margin-top: 2px;
        }

        /* ─── Pengaturan Media Cetak ─── */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .paper-sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 landscape;
                margin: 12mm 15mm;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    @php
        $type = request('type', 'semua');
        $totalPertemuan = $kelas->jadwals->count();
        $totalTugas = $kelas->tugasLaporans->count();
        $totalMahasiswa = $kelas->approvedMahasiswas->count();
        $isDosen = auth()->user()?->role === 'dosen';
        $exportPdfRoute = $isDosen ? route('dosen.rekap.export-pdf', ['id' => $kelas->id, 'type' => $type]) : route('laboran.rekap.export-pdf', ['id' => $kelas->id, 'type' => $type]);
        $cetakBaseRoute = $isDosen ? route('dosen.rekap.cetak', $kelas->id) : route('laboran.rekap.cetak', $kelas->id);
    @endphp

    <!-- ─── Floating Top Toolbar ─── -->
    <div class="no-print-bar">
        <div class="title-bar">
            <i class="bi bi-file-earmark-text text-sky-400"></i>
            <span>Dokumen Cetak Rekap: {{ $kelas->nama_kelas }}</span>
        </div>
        <div class="actions">
            <span style="color:#94a3b8; font-size:11px; margin-right:4px;">Tampilan:</span>
            <a href="{{ $cetakBaseRoute }}?type=semua" class="filter-chip {{ $type === 'semua' ? 'active' : '' }}">Lengkap</a>
            <a href="{{ $cetakBaseRoute }}?type=absensi" class="filter-chip {{ $type === 'absensi' ? 'active' : '' }}">Hanya Presensi</a>
            <a href="{{ $cetakBaseRoute }}?type=nilai" class="filter-chip {{ $type === 'nilai' ? 'active' : '' }}">Hanya Nilai</a>

            <div style="width:1px; height:24px; background:#475569; margin: 0 4px;"></div>

            <button onclick="window.print()" class="btn-action btn-print">
                <i class="bi bi-printer"></i> Cetak / Simpan PDF
            </button>
            <a href="{{ $exportPdfRoute }}" class="btn-action btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
            </a>
            <button onclick="window.close()" class="btn-action btn-close">
                <i class="bi bi-x-lg"></i> Tutup
            </button>
        </div>
    </div>

    <!-- ─── Lembar Kertas Dokumen ─── -->
    <div class="paper-sheet">
        
        <!-- KOP Surat Resmi -->
        <div class="kop-container">
            <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" alt="Logo UIN" class="kop-logo">
            <div class="kop-text">
                <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                <h2>UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH</h2>
                <h4>FAKULTAS TARBIYAH DAN KEGURUAN - PROGRAM STUDI PENDIDIKAN FISIKA</h4>
                <p>Laboratorium Pendidikan Fisika, Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh Telp/Fax. : 0651-752921</p>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            @if($type === 'absensi')
                <h1>REKAPITULASI PRESENSI KEGIATAN PRAKTIKUM</h1>
            @elseif($type === 'nilai')
                <h1>REKAPITULASI NILAI TUGAS & MODUL PRAKTIKUM</h1>
            @else
                <h1>LAPORAN REKAPITULASI KEGIATAN PRAKTIKUM</h1>
            @endif
            <p>Semester: {{ $kelas->semester?->nama_semester ?? 'Tahun Akademik Berjalan' }}</p>
        </div>

        <!-- Identitas Kelas Praktikum -->
        <table class="meta-grid">
            <tr>
                <td class="label">Mata Kuliah / Kelas</td>
                <td class="colon">:</td>
                <td class="val">{{ $kelas->nama_kelas }}</td>
                <td class="label">Ruangan</td>
                <td class="colon">:</td>
                <td class="val">{{ $kelas->ruangan?->nama_ruangan ?? 'Belum ditentukan' }}</td>
            </tr>
            <tr>
                <td class="label">Dosen Pengampu</td>
                <td class="colon">:</td>
                <td class="val">{{ $kelas->dosen?->name ?? 'Belum ditentukan' }}</td>
                <td class="label">Jadwal Praktikum</td>
                <td class="colon">:</td>
                <td class="val">
                    {{ $kelas->hari }}{{ ($kelas->jam_mulai && $kelas->jam_selesai) ? ', ' . substr($kelas->jam_mulai, 0, 5) . ' - ' . substr($kelas->jam_selesai, 0, 5) . ' WIB' : '' }}
                </td>
            </tr>
            <tr>
                <td class="label">Laboran</td>
                <td class="colon">:</td>
                <td class="val">{{ $kelas->laboran?->name ?? 'Belum ditentukan' }}</td>
                <td class="label">Jumlah Mahasiswa</td>
                <td class="colon">:</td>
                <td class="val">{{ $totalMahasiswa }} Mahasiswa Terdaftar</td>
            </tr>
        </table>

        {{-- ─── 1. TABEL PRESENSI (Tampil jika type 'semua' atau 'absensi') ─── --}}
        @if($type === 'semua' || $type === 'absensi')
            <div class="section-header">
                <span>I. REKAPITULASI PRESENSI MAHASISWA</span>
                <span style="font-size:9.5pt; font-weight:normal;">Total Pertemuan: {{ $totalPertemuan }} Sesi</span>
            </div>

            <table class="academic-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:30px;">No</th>
                        <th rowspan="2" style="width:110px;">NIM</th>
                        <th rowspan="2" style="width:180px;">Nama Mahasiswa</th>
                        @if($totalPertemuan > 0)
                            <th colspan="{{ $totalPertemuan }}">Pertemuan Praktikum</th>
                        @else
                            <th rowspan="2">Pertemuan</th>
                        @endif
                        <th colspan="4" style="width:120px;">Rekapitulasi</th>
                        <th rowspan="2" style="width:55px;">% Hadir</th>
                    </tr>
                    <tr>
                        @foreach($kelas->jadwals as $jadwal)
                            <th style="width:26px;" title="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d/m/Y') }}">
                                P{{ $jadwal->pertemuan_ke }}
                            </th>
                        @endforeach
                        <th style="width:30px;">H</th>
                        <th style="width:30px;">I</th>
                        <th style="width:30px;">S</th>
                        <th style="width:30px;">A</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sumPersentase = 0; @endphp
                    @forelse($kelas->approvedMahasiswas as $index => $mhs)
                        @php
                            $hadirCount = 0;
                            $izinCount = 0;
                            $sakitCount = 0;
                            $alpaCount = 0;
                        @endphp
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="center">
                                @if($mhs->mahasiswaProfile?->nim)
                                    {{ $mhs->mahasiswaProfile->nim }}
                                @elseif($mhs->nip_nim)
                                    {{ $mhs->nip_nim }}
                                @endif
                            </td>
                            <td>{{ $mhs->name }}</td>
                            @if($totalPertemuan > 0)
                                @foreach($kelas->jadwals as $jadwal)
                                    @php
                                        $absen = $jadwal->absensis->where('user_id', $mhs->id)->first();
                                        $status = $absen ? $absen->status_hadir : null;
                                        $symbol = '';
                                        if ($status === 'hadir') {
                                            $symbol = 'H';
                                            $hadirCount++;
                                        } elseif ($status === 'izin') {
                                            $symbol = 'I';
                                            $izinCount++;
                                        } elseif ($status === 'sakit') {
                                            $symbol = 'S';
                                            $sakitCount++;
                                        } elseif ($status === 'alpa') {
                                            $symbol = 'A';
                                            $alpaCount++;
                                        }
                                    @endphp
                                    <td class="center">{{ $symbol }}</td>
                                @endforeach
                            @else
                                <td class="center"></td>
                            @endif
                            <td class="center">{{ $hadirCount }}</td>
                            <td class="center">{{ $izinCount }}</td>
                            <td class="center">{{ $sakitCount }}</td>
                            <td class="center">{{ $alpaCount }}</td>
                            @php
                                $persen = $totalPertemuan > 0 ? round(($hadirCount / $totalPertemuan) * 100) : 0;
                                $sumPersentase += $persen;
                            @endphp
                            <td class="center" style="font-weight: bold;">{{ $persen }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($totalPertemuan > 0 ? $totalPertemuan : 1) + 5 }}" class="center" style="padding:10px; font-style:italic;">
                                Belum ada data mahasiswa terdaftar.
                            </td>
                        </tr>
                    @endforelse
                    @if($totalMahasiswa > 0)
                        <tr class="summary-row">
                            <th colspan="{{ 3 + ($totalPertemuan > 0 ? $totalPertemuan : 1) }}" class="right">
                                Rata-rata Kehadiran Kelas
                            </th>
                            <th colspan="4" class="center">
                                Total: {{ $totalMahasiswa }} Mahasiswa
                            </th>
                            <th class="center">
                                {{ round($sumPersentase / $totalMahasiswa) }}%
                            </th>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="legend-box">
                <strong>Keterangan:</strong> 
                H = Hadir &nbsp;|&nbsp; 
                I = Izin Resmi &nbsp;|&nbsp; 
                S = Sakit &nbsp;|&nbsp; 
                A = Alpa / Tanpa Keterangan
            </div>
        @endif

        {{-- Page break jika mencetak kedua tabel sekaligus agar tabel nilai berada di halaman baru yang rapi --}}
        @if($type === 'semua')
            <div class="page-break" style="margin-top: 24px;"></div>
        @endif

        {{-- ─── 2. TABEL NILAI (Tampil jika type 'semua' atau 'nilai') ─── --}}
        @if($type === 'semua' || $type === 'nilai')
            <div class="section-header" style="{{ $type === 'semua' ? 'margin-top:20px;' : '' }}">
                <span>{{ $type === 'semua' ? 'II. REKAPITULASI NILAI TUGAS & MODUL' : 'REKAPITULASI NILAI TUGAS & MODUL' }}</span>
                <span style="font-size:9.5pt; font-weight:normal;">Total Tugas: {{ $totalTugas }} Modul</span>
            </div>

            <table class="academic-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:30px;">No</th>
                        <th rowspan="2" style="width:110px;">NIM</th>
                        <th rowspan="2" style="width:180px;">Nama Mahasiswa</th>
                        @if($totalTugas > 0)
                            <th colspan="{{ $totalTugas }}">Nilai Tugas / Modul</th>
                        @else
                            <th rowspan="2">Nilai Tugas</th>
                        @endif
                        <th rowspan="2" style="width:65px;">Tugas Selesai</th>
                        <th rowspan="2" style="width:65px;">Total Skor</th>
                        <th rowspan="2" style="width:60px;">Rata-rata</th>
                        <th rowspan="2" style="width:85px;">Status</th>
                    </tr>
                    <tr>
                        @foreach($kelas->tugasLaporans as $i => $tugas)
                            <th style="width:32px;" title="{{ $tugas->judul_tugas }}">
                                T{{ $i + 1 }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $sumRataRata = 0; @endphp
                    @forelse($kelas->approvedMahasiswas as $index => $mhs)
                        @php
                            $totalNilai = 0;
                            $tugasDikerjakan = 0;
                        @endphp
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="center">
                                @if($mhs->mahasiswaProfile?->nim)
                                    {{ $mhs->mahasiswaProfile->nim }}
                                @elseif($mhs->nip_nim)
                                    {{ $mhs->nip_nim }}
                                @endif
                            </td>
                            <td>{{ $mhs->name }}</td>
                            @if($totalTugas > 0)
                                @foreach($kelas->tugasLaporans as $tugas)
                                    @php
                                        $nilai = $tugas->nilai->where('mahasiswa_id', $mhs->id)->first();
                                        $skor = $nilai ? $nilai->nilai : null;
                                        if ($skor !== null) {
                                            $totalNilai += $skor;
                                            $tugasDikerjakan++;
                                        }
                                    @endphp
                                    <td class="center">
                                        @if($skor !== null)
                                            {{ $skor }}
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                <td class="center"></td>
                            @endif
                            <td class="center">{{ $tugasDikerjakan }} / {{ $totalTugas }}</td>
                            <td class="center">{{ $totalNilai }}</td>
                            @php
                                $rataRata = $tugasDikerjakan > 0 ? round($totalNilai / $tugasDikerjakan, 1) : 0;
                                $sumRataRata += $rataRata;
                            @endphp
                            <td class="center" style="font-weight: bold;">{{ $rataRata }}</td>
                            <td class="center" style="font-size: 8.5pt;">
                                @if($tugasDikerjakan === 0)
                                    Belum Dinilai
                                @elseif($rataRata >= 60)
                                    Lulus
                                @else
                                    Tidak Lulus
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($totalTugas > 0 ? $totalTugas : 1) + 4 }}" class="center" style="padding:10px; font-style:italic;">
                                Belum ada data mahasiswa terdaftar.
                            </td>
                        </tr>
                    @endforelse
                    @if($totalMahasiswa > 0)
                        <tr class="summary-row">
                            <th colspan="{{ 3 + ($totalTugas > 0 ? $totalTugas : 1) + 2 }}" class="right">
                                Rata-rata Nilai Keseluruhan Kelas
                            </th>
                            <th class="center">
                                {{ round($sumRataRata / $totalMahasiswa, 1) }}
                            </th>
                            <th class="center">
                                Total: {{ $totalMahasiswa }} Mahasiswa
                            </th>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if($totalTugas > 0)
                <div class="legend-box" style="margin-bottom: 8px;">
                    <strong>Daftar Tugas / Modul:</strong>
                    @foreach($kelas->tugasLaporans as $i => $tugas)
                        <span>[T{{ $i + 1 }}] {{ $tugas->judul_tugas }} &nbsp;&nbsp;</span>
                    @endforeach
                </div>
            @endif
        @endif

        <!-- ─── Tanda Tangan Pengesahan Akademik Formal ─── -->
        <div class="signature-container">
            <div class="signature-box">
                <div class="date-line">&nbsp;</div>
                <div class="role-line">Mengetahui,<br><strong>Laboran Praktikum</strong></div>
                <div class="name-line">{{ $kelas->laboran?->name ?? '_____________________' }}</div>
                <div class="nip-line">NIP. {{ $kelas->laboran?->nip_nim ?? '.....................................' }}</div>
            </div>

            <div class="signature-box">
                <div class="date-line">Banda Aceh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
                <div class="role-line"><br><strong>Dosen Pengampu</strong></div>
                <div class="name-line">{{ $kelas->dosen?->name ?? '_____________________' }}</div>
                <div class="nip-line">NIP. {{ $kelas->dosen?->nip_nim ?? '.....................................' }}</div>
            </div>
        </div>

    </div>

</body>
</html>
