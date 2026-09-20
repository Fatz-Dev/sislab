<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laboran</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 8px; }
        .mb-6 { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; }
        h1, h2, h3, h4 { margin: 0 0 5px 0; }
    </style>
</head>
<body>
    <div class="text-center mb-6">
        <h2 class="font-bold">LAPORAN PEMELIHARAAN LABORATORIUM</h2>
        <h3>{{ env('APP_NAME', 'SISLAB FISIKA') }}</h3>
        <p>
            Dicetak pada: {{ date('d M Y H:i') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Laboran</th>
                <th>Ruangan</th>
                <th>SOP</th>
                <th>Barang</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->laboran->name ?? '-' }}</td>
                    <td>{{ $item->jadwal->kelasPraktikum->ruangan->nama_ruangan ?? '-' }}</td>
                    <td>{{ ucfirst($item->status_sop) }}</td>
                    <td>{{ ucfirst($item->kelayakan_barang) }}</td>
                    <td>{{ $item->catatan_temuan ?: '-' }}</td>
                    <td>{{ ucfirst($item->status_admin) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data laporan tidak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
