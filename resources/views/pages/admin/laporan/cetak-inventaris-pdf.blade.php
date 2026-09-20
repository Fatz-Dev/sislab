<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Laboratorium</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-6 { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; }
        .text-right { text-align: right; }
        h1, h2, h3, h4 { margin: 0 0 5px 0; }
    </style>
</head>
<body>
    <div class="text-center mb-6">
        <h2 class="font-bold">LAPORAN INVENTARIS LABORATORIUM</h2>
        <h3>{{ env('APP_NAME', 'SISLAB FISIKA') }}</h3>
        <p>
            {{ $ruangan ? 'Ruangan: ' . $ruangan->nama_ruangan : 'Semua Ruangan' }}<br>
            Dicetak pada: {{ date('d M Y H:i') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Ruangan</th>
                <th>Stok Baik</th>
                <th>Rusak Ringan</th>
                <th>Rusak Berat</th>
                <th>Hilang</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategoriBarang->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->ruangan->nama_ruangan ?? '-' }}</td>
                    <td>{{ $item->stok_baik }}</td>
                    <td>{{ $item->stok_rusak_ringan }}</td>
                    <td>{{ $item->stok_rusak_berat }}</td>
                    <td>{{ $item->stok_hilang }}</td>
                    <td><strong>{{ $item->total_stok }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Data inventaris tidak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
