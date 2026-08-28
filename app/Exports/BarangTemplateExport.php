<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'kode_barang',
            'nama_barang',
            'merk',
            'kategori_id',
            'stok_baik',
            'stok_rusak_ringan',
            'stok_rusak_berat',
            'stok_hilang',
            'keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                'BRG-001',
                'Contoh Barang',
                'Merk A',
                '1', 
                '10',
                '0',
                '0',
                '0',
                'Contoh format keterangan',
            ]
        ];
    }
}
