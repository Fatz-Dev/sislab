<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai Tugas Praktikum</title>
</head>
<body>
    @php
        $totalTugas = $kelas->tugasLaporans->count();
        $totalMahasiswa = $kelas->approvedMahasiswas->count();
        $totalColspan = 3 + ($totalTugas > 0 ? $totalTugas : 1) + 4;
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
                REKAPITULASI NILAI TUGAS DAN MODUL PRAKTIKUM
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
            <td style="font-weight: bold;">Jumlah Modul/Tugas</td>
            <td colspan="2">: {{ $totalTugas }} Tugas</td>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>

        <!-- TABEL NILAI UTAMA -->
        <thead>
            <tr>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">No</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">NIM</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Nama Mahasiswa</th>
                @if($totalTugas > 0)
                    <th colspan="{{ $totalTugas }}" style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">Nilai Tugas / Modul</th>
                @else
                    <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Nilai Tugas</th>
                @endif
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Tugas Selesai</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Total Skor</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Rata-rata</th>
                <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2;">Status Kelulusan</th>
            </tr>
            <tr>
                @foreach($kelas->tugasLaporans as $i => $tugas)
                    <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f8f9fa;">
                        T{{ $i + 1 }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $sumRataRata = 0;
            @endphp
            @forelse($kelas->approvedMahasiswas as $index => $mhs)
                @php
                    $totalNilai = 0;
                    $tugasDikerjakan = 0;
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
                            <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                                @if($skor !== null)
                                    {{ $skor }}
                                @endif
                            </td>
                        @endforeach
                    @else
                        <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;"></td>
                    @endif
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                        {{ $tugasDikerjakan }} / {{ $totalTugas }}
                    </td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                        {{ $totalNilai }}
                    </td>
                    @php
                        $rataRata = $tugasDikerjakan > 0 ? round($totalNilai / $tugasDikerjakan, 1) : 0;
                        $sumRataRata += $rataRata;
                    @endphp
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ $rataRata }}
                    </td>
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                        @if($tugasDikerjakan === 0)
                            Belum Ada Nilai
                        @elseif($rataRata >= 60)
                            Lulus
                        @else
                            Tidak Lulus
                        @endif
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
                    <th colspan="{{ 3 + ($totalTugas > 0 ? $totalTugas : 1) + 2 }}" style="border: 1px solid #000000; font-weight: bold; text-align: right; background-color: #f2f2f2;">
                        Rata-rata Nilai Keseluruhan Kelas
                    </th>
                    <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">
                        {{ round($sumRataRata / $totalMahasiswa, 1) }}
                    </th>
                    <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #f2f2f2;">
                        Total: {{ $totalMahasiswa }} Mahasiswa
                    </th>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- DAFTAR JUDUL TUGAS & TANDA TANGAN AKADEMIK FORMAL -->
    <table>
        <tr>
            <td colspan="{{ $totalColspan }}"></td>
        </tr>
        @if($totalTugas > 0)
            <tr>
                <td colspan="5" style="font-weight: bold; text-decoration: underline; font-size: 10pt;">
                    Daftar Judul Tugas / Modul:
                </td>
                <td colspan="{{ max(1, $totalColspan - 9) }}"></td>
                <td colspan="4" style="text-align: center; font-size: 10pt;">
                    Banda Aceh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
            @foreach($kelas->tugasLaporans as $i => $tugas)
                <tr>
                    <td colspan="5" style="font-size: 9pt;">
                        T{{ $i + 1 }} : {{ $tugas->judul_tugas }}
                    </td>
                    @if($loop->first)
                        <td colspan="{{ max(1, $totalColspan - 9) }}"></td>
                        <td colspan="4" rowspan="{{ min(count($kelas->tugasLaporans), 2) }}" style="text-align: center;">
                            Dosen Pengampu
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5"></td>
                <td colspan="{{ max(1, $totalColspan - 9) }}"></td>
                <td colspan="4" style="text-align: center; font-size: 10pt;">
                    Banda Aceh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="{{ max(1, $totalColspan - 9) }}"></td>
                <td colspan="4" style="text-align: center;">
                    Dosen Pengampu
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="4" style="text-align: center;">
                Mengetahui,<br>
                Laboran Praktikum
            </td>
            <td colspan="{{ max(1, $totalColspan - 8) }}"></td>
            <td colspan="4" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="{{ $totalColspan }}" style="height: 40px;"></td>
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
