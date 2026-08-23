<?php

namespace App\Imports;

use App\Models\BarangInventaris;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    protected int $ruanganId;

    public function __construct(int $ruanganId)
    {
        $this->ruanganId = $ruanganId;
    }

    public function model(array $row)
    {
        return new BarangInventaris([
            'kode_barang'       => $row['kode_barang'] ?? null,
            'nama_barang'       => $row['nama_barang'] ?? '',
            'merk'              => $row['merk'] ?? null,
            'kategori_id'       => $row['kategori_id'] ?? null,
            'stok_baik'         => $row['stok_baik'] ?? 0,
            'stok_rusak_ringan' => $row['stok_rusak_ringan'] ?? 0,
            'stok_rusak_berat'  => $row['stok_rusak_berat'] ?? 0,
            'stok_hilang'       => $row['stok_hilang'] ?? 0,
            'ruangan_id'        => $this->ruanganId,
            'keterangan'        => $row['keterangan'] ?? null,
        ]);
    }
}
