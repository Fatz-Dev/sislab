<?php

namespace App\Exports;

use App\Models\KelasPraktikum;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAbsensiExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected KelasPraktikum $kelas;

    public function __construct(KelasPraktikum $kelas)
    {
        $this->kelas = $kelas;
    }

    public function view(): View
    {
        return view('exports.rekap-absensi-excel', [
            'kelas' => $this->kelas
        ]);
    }

    public function title(): string
    {
        return 'Rekap Presensi';
    }

    public function styles(Worksheet $sheet)
    {
        // Pengaturan font global untuk konsistensi dokumen akademik formal
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(11);

        return [];
    }
}
