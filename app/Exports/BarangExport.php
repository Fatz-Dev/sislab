<?php

namespace App\Exports;

use App\Models\BarangInventaris;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromQuery, WithHeadings, WithMapping
{
    protected $ruangan_id;
    protected $kategori_id;

    public function __construct($ruangan_id = null, $kategori_id = null)
    {
        $this->ruangan_id = $ruangan_id;
        $this->kategori_id = $kategori_id;
    }

    public function query()
    {
        return BarangInventaris::query()
            ->with(['ruangan', 'kategoriBarang'])
            ->when($this->ruangan_id, fn($q) => $q->where('ruangan_id', $this->ruangan_id))
            ->when($this->kategori_id, fn($q) => $q->where('kategori_id', $this->kategori_id));
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Barang',
            'Merk',
            'Kategori',
            'Ruangan',
            'Stok Baik',
            'Stok R.Ringan',
            'Stok R.Berat',
            'Stok Hilang',
            'Total Stok',
            'Keterangan',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->merk,
            $barang->kategoriBarang?->nama_kategori,
            $barang->ruangan?->nama_ruangan,
            $barang->stok_baik,
            $barang->stok_rusak_ringan,
            $barang->stok_rusak_berat,
            $barang->stok_hilang,
            $barang->total_stok,
            $barang->keterangan,
        ];
    }
}
