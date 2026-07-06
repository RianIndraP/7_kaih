<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemantauan Kuis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            @page { margin: 1.5cm; }
            .print-hidden { display: none !important; }
            body { background: white !important; -webkit-print-color-adjust: exact; }
            .break-inside-avoid { break-inside: avoid; page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans p-8 max-w-4xl mx-auto" onload="window.print()">

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold uppercase mb-1">Laporan Pemantauan Kuis</h2>
        <h3 class="text-lg font-semibold text-gray-700">
            {{ $selectedKuis ? $selectedKuis->judul . ' — ' . $selectedKuis->tema : 'Belum ada kuis' }}
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            Filter Status: 
            <span class="font-medium">
                {{ $statusFilter == 'sudah' ? 'Sudah Menjawab' : ($statusFilter == 'belum' ? 'Belum Menjawab' : 'Semua Status') }}
            </span>
        </p>
    </div>

    @forelse($data as $i => $row)
        @php
            $statusLabel = match ($row['status']) {
                'sudah_dikerjakan' => 'Sudah menjawab',
                'sedang_berlangsung' => 'Sedang mengerjakan',
                'kadaluarsa' => 'Kadaluarsa',
                default => 'Belum menjawab',
            };
        @endphp
        <div class="mb-5 border border-gray-300 rounded-lg p-5 break-inside-avoid bg-blue-50/30">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-4 border-b border-gray-200 pb-3">
                <div><span class="font-semibold text-gray-700">Nama Murid :</span> {{ $row['siswa']->name }}</div>
                <div><span class="font-semibold text-gray-700">NISN :</span> {{ $row['siswa']->nisn ?? '-' }}</div>
                <div><span class="font-semibold text-gray-700">Kelas :</span> {{ $row['siswa']->kelas->nama_kelas ?? '-' }}</div>
                <div>
                    <span class="font-semibold text-gray-700">Status :</span> 
                    <span class="{{ $row['status'] == 'sudah_dikerjakan' ? 'text-green-600 font-medium' : 'text-amber-600 font-medium' }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
            
            <div class="text-sm">
                <span class="font-semibold text-gray-700 block mb-2">Jawaban Kuis :</span>
                @if($row['status'] == 'sudah_dikerjakan' && $row['jawaban'])
                    <div class="p-3 bg-white border border-gray-200 rounded text-gray-800 whitespace-pre-wrap">{{ $row['jawaban']->jawaban }}</div>
                    <div class="mt-2 text-xs text-gray-500 text-right">
                        Dikirim pada: {{ $row['jawaban']->waktu_kirim ? $row['jawaban']->waktu_kirim->format('d M Y, H:i') : '-' }}
                    </div>
                @else
                    <div class="p-3 bg-white border border-gray-200 rounded italic text-gray-400 text-center">-</div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center text-gray-500 p-8 border border-gray-300 rounded-lg italic">
            Tidak ada data siswa yang cocok dengan filter ini.
        </div>
    @endforelse

    <div class="mt-8 pt-4 border-t border-gray-200 text-sm text-gray-500 flex justify-between print-hidden">
        <span>Gunakan fitur print dari browser untuk menyimpan sebagai PDF.</span>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print / Simpan PDF</button>
    </div>

</body>
</html>
