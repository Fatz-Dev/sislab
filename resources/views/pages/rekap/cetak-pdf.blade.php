<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Praktikum — {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 12mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ─── KOP Surat Formal (Table Layout untuk DomPDF) ─── */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
        }
        .kop-logo {
            width: 75px;
            height: auto;
        }
        .kop-title h3 {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        .kop-title h2 {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
        }
        .kop-title h4 {
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }
        .kop-title p {
            font-size: 8pt;
            font-style: italic;
            text-align: center;
        }

        /* ─── Judul Dokumen ─── */
        .doc-title {
            text-align: center;
            margin: 8px 0 12px;
        }
        .doc-title h1 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title p {
            font-size: 9pt;
            margin-top: 2px;
        }

        /* ─── Identitas Kelas ─── */
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }
        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }
        .meta-table td.label {
            font-weight: bold;
            width: 16%;
        }
        .meta-table td.colon {
            width: 2%;
            text-align: center;
        }
        .meta-table td.val {
            width: 32%;
        }

        /* ─── Section Header ─── */
        .section-header {
            font-size: 9.5pt;
            font-weight: bold;
            margin: 12px 0 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        /* ─── Tabel Akademik Hitam Putih ─── */
        table.academic-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 10px;
        }
        table.academic-table th, 
        table.academic-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            line-height: 1.1;
        }
        table.academic-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            font-size: 7.5pt;
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

        /* ─── Keterangan ─── */
        .legend-box {
            font-size: 7.5pt;
            margin-top: 4px;
            line-height: 1.3;
        }

        /* ─── Tanda Tangan ─── */
        .signature-table {
            width: 100%;
            margin-top: 24px;
            page-break-inside: avoid;
            font-size: 8.5pt;
        }
        .signature-table td {
            border: none;
            text-align: center;
            vertical-align: top;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @php
        $type = $type ?? request('type', 'semua');
        $totalPertemuan = $kelas->jadwals->count();
        $totalTugas = $kelas->tugasLaporans->count();
        $totalMahasiswa = $kelas->approvedMahasiswas->count();

        // Path logo institusi
        $logoPath = public_path('assets/image/Lambang_UIN_Ar-Raniry.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
    @endphp

    <!-- ─── KOP Surat Resmi ─── -->
    <table class="kop-table">
        <tr>
            <td style="width: 80px; text-align: center;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="kop-logo" alt="Logo">
                @endif
            </td>
            <td class="kop-title">
                <h3>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h3>
                <h2>UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH</h2>
                <h4>FAKULTAS TARBIYAH DAN KEGURUAN - PROGRAM STUDI PENDIDIKAN FISIKA</h4>
                <p>Laboratorium Pendidikan Fisika, Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh Telp/Fax. : 0651-752921</p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <!-- ─── Judul Dokumen ─── -->
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

    <!-- ─── Identitas Kelas ─── -->
    <table class="meta-table">
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
            I. REKAPITULASI PRESENSI MAHASISWA (Total: {{ $totalPertemuan }} Pertemuan)
        </div>

        <table class="academic-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width:25px;">No</th>
                    <th rowspan="2" style="width:90px;">NIM</th>
                    <th rowspan="2" style="width:160px;">Nama Mahasiswa</th>
                    @if($totalPertemuan > 0)
                        <th colspan="{{ $totalPertemuan }}">Pertemuan Praktikum</th>
                    @else
                        <th rowspan="2">Pertemuan</th>
                    @endif
                    <th colspan="4" style="width:100px;">Rekapitulasi</th>
                    <th rowspan="2" style="width:45px;">% Hadir</th>
                </tr>
                <tr>
                    @foreach($kelas->jadwals as $jadwal)
                        <th style="width:22px;">
                            P{{ $jadwal->pertemuan_ke }}
                        </th>
                    @endforeach
                    <th style="width:25px;">H</th>
                    <th style="width:25px;">I</th>
                    <th style="width:25px;">S</th>
                    <th style="width:25px;">A</th>
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
                        <td colspan="{{ 3 + ($totalPertemuan > 0 ? $totalPertemuan : 1) + 5 }}" class="center" style="padding:8px; font-style:italic;">
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
            <strong>Keterangan:</strong> H = Hadir | I = Izin Resmi | S = Sakit | A = Alpa / Tanpa Keterangan
        </div>
    @endif

    {{-- Page break jika mencetak kedua tabel sekaligus --}}
    @if($type === 'semua')
        <div class="page-break"></div>
    @endif

    {{-- ─── 2. TABEL NILAI (Tampil jika type 'semua' atau 'nilai') ─── --}}
    @if($type === 'semua' || $type === 'nilai')
        <div class="section-header">
            {{ $type === 'semua' ? 'II. REKAPITULASI NILAI TUGAS & MODUL' : 'REKAPITULASI NILAI TUGAS & MODUL' }} (Total: {{ $totalTugas }} Modul)
        </div>

        <table class="academic-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width:25px;">No</th>
                    <th rowspan="2" style="width:90px;">NIM</th>
                    <th rowspan="2" style="width:160px;">Nama Mahasiswa</th>
                    @if($totalTugas > 0)
                        <th colspan="{{ $totalTugas }}">Nilai Tugas / Modul</th>
                    @else
                        <th rowspan="2">Nilai Tugas</th>
                    @endif
                    <th rowspan="2" style="width:55px;">Selesai</th>
                    <th rowspan="2" style="width:55px;">Total</th>
                    <th rowspan="2" style="width:50px;">Rata-rata</th>
                    <th rowspan="2" style="width:75px;">Status</th>
                </tr>
                <tr>
                    @foreach($kelas->tugasLaporans as $i => $tugas)
                        <th style="width:26px;">
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
                        <td class="center" style="font-size: 7.5pt;">
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
                        <td colspan="{{ 3 + ($totalTugas > 0 ? $totalTugas : 1) + 4 }}" class="center" style="padding:8px; font-style:italic;">
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
            <div class="legend-box" style="margin-bottom: 6px;">
                <strong>Daftar Tugas / Modul:</strong>
                @foreach($kelas->tugasLaporans as $i => $tugas)
                    <span>[T{{ $i + 1 }}] {{ $tugas->judul_tugas }} &nbsp;&nbsp;</span>
                @endforeach
            </div>
        @endif
    @endif

    <!-- ─── Tanda Tangan Pengesahan Akademik Formal ─── -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                &nbsp;<br>
                Mengetahui,<br>
                <strong>Laboran Praktikum</strong>
                <br><br><br><br>
                <strong><u>{{ $kelas->laboran?->name ?? '_____________________' }}</u></strong><br>
                NIP. {{ $kelas->laboran?->nip_nim ?? '.....................................' }}
            </td>
            <td style="width: 50%;">
                Banda Aceh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                <br>
                <strong>Dosen Pengampu</strong>
                <br><br><br><br>
                <strong><u>{{ $kelas->dosen?->name ?? '_____________________' }}</u></strong><br>
                NIP. {{ $kelas->dosen?->nip_nim ?? '.....................................' }}
            </td>
        </tr>
    </table>

</body>
</html>
