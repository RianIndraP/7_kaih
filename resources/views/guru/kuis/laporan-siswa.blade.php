<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kuis — {{ $row['siswa']->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            @page {
                margin: 1.5cm;
                size: A4;
            }

            .print-hidden {
                display: none !important;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        body {
            background: #f3f4f6;
            font-family: 'Times New Roman', serif;
        }

        .paper {
            background: #fff;
            max-width: 720px;
            margin: 24px auto;
            padding: 48px 56px;
            color: #111;
            border: 1px solid #e5e7eb;
        }

        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 3px solid #1a3a6b;
            margin-bottom: 4px;
        }

        .kop-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            flex-shrink: 0;
            overflow: hidden;
        }

        .kop-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kop-logo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #1a3a6b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #fff;
            font-weight: 700;
            text-align: center;
            line-height: 1.3;
            flex-shrink: 0;
        }

        .kop-text h1 {
            font-size: 16px;
            font-weight: 700;
            color: #1a3a6b;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 3px;
        }

        .kop-text p {
            font-size: 11px;
            color: #555;
            line-height: 1.5;
        }

        .kop-garis2 {
            height: 1px;
            background: #1a3a6b;
            opacity: .25;
            margin-bottom: 20px;
        }

        .doc-title {
            text-align: center;
            margin-bottom: 16px;
        }

        .doc-title h2 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .doc-title p {
            font-size: 12px;
            color: #444;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .info-table td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 145px;
            color: #555;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        .info-table td:last-child {
            color: #111;
        }

        .status-row {
            display: flex;
            gap: 24px;
            font-size: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .status-item {
            display: flex;
            gap: 5px;
        }

        .status-item span {
            color: #666;
        }

        .status-item strong {
            color: #111;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #1a3a6b;
            margin: 16px 0 8px;
            border-left: 3px solid #1a3a6b;
            padding-left: 8px;
        }

        .soal-box {
            background: #fffbe6;
            border: 1px solid #ffe58f;
            border-radius: 3px;
            padding: 10px 14px;
            font-size: 12px;
            color: #333;
            margin-bottom: 12px;
            font-style: italic;
            line-height: 1.6;
        }

        .jawaban-box {
            border: 1px solid #d1d5db;
            border-radius: 3px;
            padding: 14px;
            font-size: 12px;
            color: #111;
            line-height: 1.8;
            min-height: 100px;
            white-space: pre-wrap;
            font-family: 'Times New Roman', serif;
        }

        .jawaban-kosong {
            font-style: italic;
            color: #9ca3af;
            text-align: center;
            padding: 32px;
            border: 1px dashed #d1d5db;
            border-radius: 3px;
        }

        .ttd-section {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            font-size: 12px;
        }

        .ttd-box {
            text-align: center;
        }

        .ttd-box .ttd-label {
            margin-bottom: 64px;
            color: #333;
            line-height: 1.6;
        }

        .ttd-line {
            border-top: 1px solid #111;
            padding-top: 5px;
        }

        .ttd-nama {
            font-weight: 700;
            color: #111;
            margin-bottom: 2px;
        }

        .ttd-nip {
            color: #666;
        }

        .print-bar {
            max-width: 720px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
    </style>
</head>

<body>

    <div class="print-bar print-hidden">
        <a href="{{ route('guru.pemantauan-kuis') }}?kuis_id={{ $selectedKuis->id }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-sans text-gray-700 hover:bg-gray-50 transition-colors">
            ← Kembali
        </a>
        <button onclick="window.print()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-sans hover:bg-blue-700 transition-colors">
            Print / Simpan PDF
        </button>
    </div>

    <div class="paper">

        {{-- Kop Sekolah --}}
        <div class="kop">
            @if(file_exists(public_path('img/logo-1.png')))
                <div class="kop-logo">
                    <img src="{{ asset('img/logo-1.png') }}" alt="Logo Sekolah">
                </div>
            @else
                <div class="kop-logo-placeholder">SMK<br>N 5</div>
            @endif
            <div class="kop-text">
                <h1>SMK Negeri 5 Telkom Banda Aceh</h1>
                <p>Jl. Utama Barona Jaya, Krueng Barona Jaya, Aceh Besar, Aceh 23372</p>
                <p>Telp. (0651) 123456 &nbsp;|&nbsp; Email: smkn5telkom@disdik.acehprov.go.id &nbsp;|&nbsp; NPSN:
                    10106822</p>
            </div>
        </div>
        <div class="kop-garis2"></div>

        {{-- Judul Dokumen --}}
        <div class="doc-title">
            <h2>Laporan Pemantauan Kuis</h2>
            <p>Literasi &amp; Numerasi &mdash; Tahun Ajaran {{ date('Y') - 1 }}/{{ date('Y') }}</p>
        </div>

        {{-- Info Siswa & Kuis --}}
        @php
            $siswa = $row['siswa'];
            $jawaban = $row['jawaban'];
            $status = $row['status'];
            $guruWali = $guru ?? null;
            $guruNama = $guruWali?->user?->name ?? '-';
            $guruNip = $guruWali?->user?->nip ?? '-';
        @endphp

        <table class="info-table">
            <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td><strong>{{ $siswa->name }}</strong></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{ $siswa->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $siswa->kelas?->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td>Guru Wali</td>
                <td>:</td>
                <td>{{ $guruNama }}</td>
            </tr>
            <tr>
                <td>Kuis</td>
                <td>:</td>
                <td>{{ $selectedKuis->judul }}</td>
            </tr>
            <tr>
                <td>Tema Bacaan</td>
                <td>:</td>
                <td>{{ $selectedKuis->tema }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>:</td>
                <td>{{ ucfirst($selectedKuis->kategori) }}</td>
            </tr>
            <tr>
                <td>Waktu Pelaksanaan</td>
                <td>:</td>
                <td>{{ $selectedKuis->waktu_mulai->translatedFormat('l, d F Y') }} &mdash;
                    {{ $selectedKuis->waktu_mulai->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>:</td>
                <td>{{ $selectedKuis->durasi_menit }} menit</td>
            </tr>
        </table>

        {{-- Status pengerjaan --}}
        <div class="status-row">
            <div class="status-item">
                <span>Mulai dikerjakan:</span>
                <strong>{{ $jawaban?->mulai_dikerjakan?->format('H:i') . ' WIB' ?? '—' }}</strong>
            </div>
            <div class="status-item">
                <span>Waktu pengiriman:</span>
                <strong>{{ $jawaban?->waktu_kirim?->format('H:i') . ' WIB' ?? '—' }}</strong>
            </div>
            <div class="status-item">
                <span>Status:</span>
                <strong style="{{ $status === 'sudah_dikerjakan' ? 'color:#15803d' : 'color:#b45309' }}">
                    {{ match ($status) {
    'sudah_dikerjakan' => 'Sudah menjawab',
    'sedang_berlangsung' => 'Sedang mengerjakan',
    'kadaluarsa' => 'Kadaluarsa (tidak selesai)',
    default => 'Belum menjawab',
} }}
                </strong>
            </div>
        </div>

        {{-- Soal --}}
        <div class="section-label">Soal</div>
        <div class="soal-box">{{ $selectedKuis->soal }}</div>

        {{-- Jawaban --}}
        <div class="section-label">Jawaban Siswa</div>
        @if($jawaban?->jawaban)
            <div class="jawaban-box">{{ $jawaban->jawaban }}</div>
        @else
            <div class="jawaban-kosong">Siswa belum memberikan jawaban</div>
        @endif

        {{-- Tanda Tangan --}}
        <div class="ttd-section">
            <div class="ttd-box">
                <div class="ttd-label">
                    Mengetahui,<br>
                    Kepala Sekolah
                </div>
                <div class="ttd-line">
                    <div class="ttd-nama">Drs. Muhammad Iqbal, M.Pd</div>
                    <div class="ttd-nip">NIP. 197305151998021002</div>
                </div>
            </div>
            <div class="ttd-box">
                <div class="ttd-label">
                    Banda Aceh,
                    {{ $jawaban?->waktu_kirim?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}<br>
                    Guru Wali Kelas
                </div>
                <div class="ttd-line">
                    <div class="ttd-nama">{{ $guruNama }}</div>
                    <div class="ttd-nip">NIP. {{ $guruNip }}</div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>