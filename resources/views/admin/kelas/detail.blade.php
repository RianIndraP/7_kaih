<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $kelas->nama_kelas }} — Buku Tahunan | SMK N 5 Telkom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            background: #f5f4f0;
            font-family: 'Georgia', serif;
            min-height: 100%;
        }
    </style>
</head>

<body class="min-h-screen bg-[#f5f4f0]">

    {{-- Top Bar --}}
    <div class="sticky top-0 z-10 bg-white border-b border-[#e5e3dc] px-6 h-[52px] flex items-center justify-between">
        <a href="{{ route('admin.kelas') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 hover:bg-[#f0eeea] px-3 py-1.5 rounded-lg transition-all font-sans">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7" />
            </svg>
            Kembali ke Manajemen Kelas
        </a>
        <span class="text-xs text-gray-400 font-sans">SMK N 5 Telkom Banda Aceh</span>
    </div>

    {{-- Cover Banner --}}
    @php
        $colors = [
            0 => ['#1e3a5f', '#2563eb'],
            1 => ['#3b0764', '#7c3aed'],
            2 => ['#831843', '#ec4899'],
            3 => ['#7c2d12', '#f97316'],
            4 => ['#064e3b', '#10b981'],
            5 => ['#164e63', '#06b6d4'],
            6 => ['#881337', '#f43f5e'],
            7 => ['#365314', '#84cc16'],
            8 => ['#4c1d95', '#a78bfa'],
            9 => ['#7c2d12', '#fb923c'],
            10 => ['#064e3b', '#34d399'],
            11 => ['#0c4a6e', '#38bdf8'],
        ];
        $c = $colors[$kelas->color_index % count($colors)];
        $laki = $kelas->siswa->where('gender', 'Laki-laki')->count();
        $perempuan = $kelas->siswa->where('gender', 'Perempuan')->count();
    @endphp

    <div class="relative overflow-hidden text-white text-center px-8 py-12"
        style="background: linear-gradient(135deg, {{ $c[0] }}, {{ $c[1] }})">
        <div class="absolute inset-0 opacity-5" style="background-image:url('data:image/svg+xml,...')"></div>
        <div class="relative z-10">
            <span
                class="inline-block text-[11px] font-sans tracking-[2px] uppercase px-3.5 py-1 rounded-full border border-white/25 bg-white/15 mb-3">
                Buku Tahunan Kelas
            </span>
            <h1 class="text-4xl font-bold tracking-tight mb-1" style="font-family:'Georgia',serif">
                {{ $kelas->nama_kelas }}
            </h1>
            <p class="text-sm font-sans opacity-70">Tahun Ajaran {{ date('Y') - 1 }} / {{ date('Y') }}</p>
            <div class="flex justify-center gap-8 mt-6 pt-6 border-t border-white/20">
                <div class="text-center">
                    <div class="text-2xl font-bold font-sans">{{ $kelas->siswa->count() }}</div>
                    <div class="text-[10px] font-sans opacity-60 uppercase tracking-wider mt-0.5">Siswa</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold font-sans">{{ $laki }}</div>
                    <div class="text-[10px] font-sans opacity-60 uppercase tracking-wider mt-0.5">Laki-laki</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold font-sans">{{ $perempuan }}</div>
                    <div class="text-[10px] font-sans opacity-60 uppercase tracking-wider mt-0.5">Perempuan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto px-6 py-10">

        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <span class="text-xs font-sans text-gray-400 tracking-[2px] uppercase">Daftar Siswa</span>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari nama atau NISN…"
                    class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm font-sans bg-white w-52 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 text-gray-700">
                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </div>
        </div>

        {{-- Yearbook Grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-[2px] bg-[#dbd9d3] border-2 border-[#dbd9d3] rounded-sm overflow-hidden"
            id="yearbookGrid">

            @forelse($kelas->siswa->sortBy('name') as $s)
                @php
                    $inisial = collect(explode(' ', $s->name))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                    $avatarColors = [
                        ['#dbeafe', '#1d4ed8'],
                        ['#fce7f3', '#be185d'],
                        ['#dcfce7', '#15803d'],
                        ['#fef9c3', '#a16207'],
                        ['#ede9fe', '#7c3aed'],
                        ['#ffedd5', '#c2410c'],
                        ['#e0f2fe', '#0369a1'],
                        ['#fce7f3', '#db2777'],
                    ];
                    $ac = $avatarColors[$loop->index % count($avatarColors)];
                @endphp
                <div class="student-card bg-white flex flex-col items-center px-3 pt-5 pb-4 text-center hover:bg-[#fafaf8] transition-colors relative"
                    data-name="{{ strtolower($s->name) }}" data-nisn="{{ $s->nisn }}">

                    {{-- Gender Badge --}}
                    @if($s->gender)
                        <div class="absolute top-3 right-3 w-[18px] h-[18px] rounded-full flex items-center justify-center text-[9px] font-bold font-sans
                            {{ $s->gender == 'Laki-laki' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                            {{ $s->gender == 'Laki-laki' ? 'L' : 'P' }}
                        </div>
                    @endif

                    {{-- Photo --}}
                    <div class="w-[90px] h-[108px] min-w-[90px] min-h-[108px] max-w-[90px] max-h-[108px] rounded-[3px] overflow-hidden mb-3 flex-shrink-0 relative flex items-center justify-center"
                        style="background:{{ $ac[0] }}">
                        @if($s->foto)
                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->name }}" class="absolute inset-0 w-full h-full object-cover" style="width: 100%; height: 100%;">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold font-sans"
                                    style="background:{{ $ac[0] }};color:{{ $ac[1] }}">
                                    {{ $inisial }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="text-[12px] font-bold leading-snug text-gray-900 mb-1 break-words w-full"
                        style="font-family:'Georgia',serif">
                        {{ $s->name }}
                    </div>

                    {{-- NISN --}}
                    <div class="text-[10px] text-gray-400" style="font-family:'Courier New',monospace;letter-spacing:0.5px">
                        {{ $s->nisn }}
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center font-sans text-sm text-gray-400 bg-white">
                    Belum ada siswa di kelas ini
                </div>
            @endforelse

        </div>

        {{-- Guru Wali --}}
        @if($kelas->guruWali)
            @php
                $waliName = $kelas->guruWali->user->name ?? '-';
                $waliInisial = collect(explode(' ', $waliName))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->join('');
            @endphp
            <div class="mt-8 bg-white rounded-xl border-t-2 border-[#dbd9d3] p-5 flex items-center gap-4 font-sans">
                <div
                    class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-base font-bold flex-shrink-0">
                    {{ $waliInisial }}
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Guru Wali Kelas</p>
                    <h4 class="text-base text-gray-900" style="font-family:'Georgia',serif">{{ $waliName }}</h4>
                </div>
            </div>
        @else
            <div class="mt-8 bg-white rounded-xl border-t-2 border-[#dbd9d3] p-5 flex items-center gap-4 font-sans">
                <div
                    class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center text-base font-bold flex-shrink-0">
                    ?
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-0.5">Guru Wali Kelas</p>
                    <h4 class="text-base text-gray-400 italic" style="font-family:'Georgia',serif">Belum ditentukan</h4>
                </div>
            </div>
        @endif

    </div>

    {{-- Footer --}}
    <div class="text-center py-8 font-sans text-[11px] text-gray-300 tracking-widest uppercase">
        SMK Negeri 5 Telkom Banda Aceh &nbsp;·&nbsp; {{ $kelas->nama_kelas }} &nbsp;·&nbsp; {{ date('Y') }}
    </div>

</body>

</html>

<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.student-card').forEach(card => {
            const match = card.dataset.name.includes(q) || card.dataset.nisn.includes(q);
            card.style.display = match ? '' : 'none';
        });
    });
</script>