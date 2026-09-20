<?php

namespace App\Exports;

use App\Models\BarangInventaris;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventarisExport implements FromQuery, WithHeadings, WithMapping
{
    protected $ruangan_id;

    public function __construct($ruangan_id)
    {
        $this->ruangan_id = $ruangan_id;
    }

    public function query()
    {
        return BarangInventaris::query()
            ->with(['ruangan', 'kategoriBarang'])
            ->when($this->ruangan_id, fn($q) => $q->where('ruangan_id', $this->ruangan_id))
            ->orderBy('ruangan_id')
            ->orderBy('nama_barang');
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Ruangan',
            'Stok Baik',
            'Rusak Ringan',
            'Rusak Berat',
            'Hilang',
            'Total Stok'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->kode_barang,
            $row->nama_barang,
            $row->kategoriBarang->nama_kategori ?? '-',
            $row->ruangan->nama_ruangan ?? '-',
            $row->stok_baik,
            $row->stok_rusak_ringan,
            $row->stok_rusak_berat,
            $row->stok_hilang,
            $row->total_stok
        ];
    }
}
