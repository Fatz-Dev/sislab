<?php

namespace App\Exports;

use App\Models\LaporanLaboran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaboranExport implements FromQuery, WithHeadings, WithMapping
{
    protected $status_admin;
    protected $ruangan_id;
    protected $start_date;
    protected $end_date;

    public function __construct($status_admin, $ruangan_id, $start_date, $end_date)
    {
        $this->status_admin = $status_admin;
        $this->ruangan_id = $ruangan_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function query()
    {
        return LaporanLaboran::query()
            ->with(['jadwal.kelasPraktikum.ruangan', 'laboran'])
            ->when($this->status_admin, fn($q) => $q->where('status_admin', $this->status_admin))
            ->when($this->ruangan_id, function($q) {
                $q->whereHas('jadwal.kelasPraktikum', function($q2) {
                    $q2->where('ruangan_id', $this->ruangan_id);
                });
            })
            ->when($this->start_date, fn($q) => $q->whereDate('created_at', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('created_at', '<=', $this->end_date))
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Laboran',
            'Ruangan',
            'Kelas',
            'Status SOP',
            'Kelayakan Barang',
            'Catatan Temuan',
            'Status Admin'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->created_at->format('Y-m-d H:i'),
            $row->laboran->name ?? '-',
            $row->jadwal->kelasPraktikum->ruangan->nama_ruangan ?? '-',
            $row->jadwal->kelasPraktikum->nama_kelas ?? '-',
            ucfirst($row->status_sop),
            ucfirst($row->kelayakan_barang),
            $row->catatan_temuan,
            ucfirst($row->status_admin)
        ];
    }
}
