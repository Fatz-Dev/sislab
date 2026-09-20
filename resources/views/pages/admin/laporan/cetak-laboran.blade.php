<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Laboran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .print-container { padding: 0; box-shadow: none; max-width: 100%; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-gray-800">
    
    <div class="max-w-5xl mx-auto mb-6 text-right no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm font-medium transition-colors">
            Cetak Halaman ini
        </button>
    </div>

    <div class="print-container max-w-5xl mx-auto bg-white p-10 rounded-lg shadow-sm border border-gray-200">
        
        <div class="text-center mb-10 border-b-2 border-gray-800 pb-6">
            <h1 class="text-2xl font-bold uppercase mb-1">LAPORAN PEMELIHARAAN LABORATORIUM</h1>
            <h2 class="text-xl font-semibold">{{ env('APP_NAME', 'SISLAB FISIKA') }}</h2>
            <p class="text-gray-600 mt-3 text-sm">
                Waktu Cetak: {{ date('d F Y H:i') }}
            </p>
        </div>

        <table class="w-full text-sm border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-3 text-left">No</th>
                    <th class="border border-gray-300 p-3 text-left">Tanggal</th>
                    <th class="border border-gray-300 p-3 text-left">Laboran</th>
                    <th class="border border-gray-300 p-3 text-left">Ruangan</th>
                    <th class="border border-gray-300 p-3 text-left">SOP</th>
                    <th class="border border-gray-300 p-3 text-left">Barang</th>
                    <th class="border border-gray-300 p-3 text-left">Catatan</th>
                    <th class="border border-gray-300 p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $item)
                    <tr>
                        <td class="border border-gray-300 p-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 p-3">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-gray-300 p-3">{{ $item->laboran->name ?? '-' }}</td>
                        <td class="border border-gray-300 p-3">{{ $item->jadwal->kelasPraktikum->ruangan->nama_ruangan ?? '-' }}</td>
                        <td class="border border-gray-300 p-3">{{ ucfirst($item->status_sop) }}</td>
                        <td class="border border-gray-300 p-3">{{ ucfirst($item->kelayakan_barang) }}</td>
                        <td class="border border-gray-300 p-3">{{ $item->catatan_temuan ?: '-' }}</td>
                        <td class="border border-gray-300 p-3">{{ ucfirst($item->status_admin) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border border-gray-300 p-6 text-center text-gray-500">
                            Tidak ada data laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-16 flex justify-end">
            <div class="text-center">
                <p class="mb-20">Mengetahui,<br>Kepala Laboratorium</p>
                <p class="font-bold border-b border-gray-800 pb-1 w-48 inline-block"></p>
                <p class="mt-1">NIP.</p>
            </div>
        </div>

    </div>
</body>
</html>
