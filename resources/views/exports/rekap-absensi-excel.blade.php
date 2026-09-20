<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Praktikum</title>
</head>
<body>
    @php
        $totalPertemuan = $kelas->jadwals->count();
        $totalMahasiswa = $kelas->approvedMahasiswas->count();
        $totalColspan = 3 + ($totalPertemuan > 0 ? $totalPertemuan : 1) + 5;
    @endphp

    <table>
        <!-- KOP / INSTITUSI PENDIDIKAN FORMAL -->
        <tr>
            <th colspan="{{ $totalColspan }}" style="text-align: center; font-size: 14pt; font-weight: bold;">
                KEMENTERIAN AGAMA REPUBLIK INDONESIA
            </th>
        </tr>
        <tr>
            <th colspan="{{ $totalColspan }}" style="text-align: center; font-size: 14pt; font-weight: bold;">
                UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH
            </th>
        </tr>
        <tr>
            <th colspan="{{ $totalColspan }}" style="text-align: center; font-size: 12pt; font-weight: bold;">
                FAKULTAS TARBIYAH DAN KEGURUAN - PROGRAM STUDI PENDIDIKAN FISIKA
            </th>
        </tr>
        <tr>
            <th colspan="{{ $totalColspan }}" style="text-align: center; font-size: 11pt; font-style: italic;">
                Laboratorium Pendidikan Fisika, Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh
            </th>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}" style="border-bottom: 2px solid #000000;"></td>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>

        <!-- JUDUL DOKUMEN -->
        <tr>
            <th colspan="{{ $totalColspan }}" style="text-align: center; font-size: 13pt; font-weight: bold; text-decoration: underline;">
                REKAPITULASI PRESENSI KEGIATAN PRAKTIKUM
            </th>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>

        <!-- IDENTITAS KELAS -->
        <tr>
            <td style="font-weight: bold;">Mata Kuliah / Kelas</td>
            <td colspan="3">: {{ $kelas->nama_kelas }}</td>
            <td colspan="{{ max(1, $totalColspan - 6) }}"></td>
            <td style="font-weight: bold;">Semester</td>
            <td colspan="2">: {{ $kelas->semester?->nama_semester }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dosen Pengampu</td>
            <td colspan="3">: {{ $kelas->dosen?->name }}</td>
            <td colspan="{{ max(1, $totalColspan - 6) }}"></td>
            <td style="font-weight: bold;">Ruangan</td>
            <td colspan="2">: {{ $kelas->ruangan?->nama_ruangan }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Laboran</td>
            <td colspan="3">: {{ $kelas->laboran?->name }}</td>
            <td colspan="{{ max(1, $totalColspan - 6) }}"></td>
            <td style="font-weight: bold;">Jadwal / Waktu</td>
            <td colspan="2">
                : {{ $kelas->hari }}@if($kelas->jam_mulai && $kelas->jam_selesai), {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }} WIB @endif
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>

        <!-- TABEL PRESENSI UTAMA -->
        <thead>
            <tr>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">No</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">NIM</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Nama Mahasiswa</th>
                @if($totalPertemuan > 0)
                    <th colspan="{{ $totalPertemuan }}" style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">Pertemuan Praktikum</th>
                @else
                    <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Pertemuan</th>
                @endif
                <th colspan="4" style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">Rekapitulasi</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">% Hadir</th>
            </tr>
            <tr>
                @foreach($kelas->jadwals as $jadwal)
                    <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">
                        P{{ $jadwal->pertemuan_ke }}
                    </th>
                @endforeach
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">H</th>
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">I</th>
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">S</th>
                <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">A</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumPersentase = 0;
            @endphp
            @forelse($kelas->approvedMahasiswas as $index => $mhs)
                @php
                    $hadirCount = 0;
                    $izinCount = 0;
                    $sakitCount = 0;
                    $alpaCount = 0;
                @endphp
                <tr>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                        @if($mhs->mahasiswaProfile?->nim)
                            {{ $mhs->mahasiswaProfile->nim }}
                        @elseif($mhs->nip_nim)
                            {{ $mhs->nip_nim }}
                        @endif
                    </td>
                    <td style="border: 1px solid #000000; text-align: left; vertical-align: middle;">{{ $mhs->name }}</td>
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
                            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                                {{ $symbol }}
                            </td>
                        @endforeach
                    @else
                        <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;"></td>
                    @endif
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $hadirCount }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $izinCount }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $sakitCount }}</td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $alpaCount }}</td>
                    @php
                        $persen = $totalPertemuan > 0 ? round(($hadirCount / $totalPertemuan) * 100) : 0;
                        $sumPersentase += $persen;
                    @endphp
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ $persen }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $totalColspan }}" style="border: 1px solid #000000; text-align: center; padding: 12px; font-style: italic;">
                        Belum ada data mahasiswa terdaftar pada kelas praktikum ini.
                    </td>
                </tr>
            @endforelse
            @if($totalMahasiswa > 0)
                <tr>
                    <th colspan="{{ 3 + ($totalPertemuan > 0 ? $totalPertemuan : 1) }}" style="border: 1px solid #000000; font-weight: bold; text-align: right; background-color: #f2f2f2;">
                        Rata-rata Kehadiran Kelas
                    </th>
                    <th colspan="4" style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">
                        Total: {{ $totalMahasiswa }} Mahasiswa
                    </th>
                    <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">
                        {{ round($sumPersentase / $totalMahasiswa) }}%
                    </th>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- KETERANGAN & TANDA TANGAN AKADEMIK FORMAL -->
    <table>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>
        <tr>
            <td colspan="4" style="font-size: 10pt;">
                <strong>Keterangan:</strong><br>
                H : Hadir<br>
                I : Izin Resmi<br>
                S : Sakit<br>
                A : Alpa / Tanpa Keterangan
            </td>
            <td colspan="{{ max(1, $totalColspan - 8) }}"></td>
            <td colspan="4" style="text-align: center; font-size: 10pt;">
                Banda Aceh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                Mengetahui,<br>
                Laboran Praktikum
            </td>
            <td colspan="{{ max(1, $totalColspan - 8) }}"></td>
            <td colspan="4" style="text-align: center;">
                Dosen Pengampu
            </td>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}" style="height: 50px;"></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">
                {{ $kelas->laboran?->name }}
            </td>
            <td colspan="{{ max(1, $totalColspan - 8) }}"></td>
            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">
                {{ $kelas->dosen?->name }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                NIP: {{ $kelas->laboran?->nip_nim }}
            </td>
            <td colspan="{{ max(1, $totalColspan - 8) }}"></td>
            <td colspan="4" style="text-align: center;">
                NIP: {{ $kelas->dosen?->nip_nim }}
            </td>
        </tr>
    </table>
</body>
</html>
