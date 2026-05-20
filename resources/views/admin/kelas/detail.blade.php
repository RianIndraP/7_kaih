<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-1.png') }}">
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $kelas->nama_kelas }} — Profil Kelas | SMK N 5 Telkom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
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
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400 font-sans hidden sm:block">SMK N 5 Telkom Banda Aceh</span>
            <button onclick="openWaliModal()"
                class="inline-flex items-center gap-2 text-sm text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 px-3 py-1.5 rounded-lg transition-all font-sans">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
                Pengaturan Kelas
            </button>
        </div>
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
                Profil Kelas
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
                        <div
                            class="absolute top-3 right-3 w-[18px] h-[18px] rounded-full flex items-center justify-center text-[9px] font-bold font-sans
                                    {{ $s->gender == 'Laki-laki' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                            {{ $s->gender == 'Laki-laki' ? 'L' : 'P' }}
                        </div>
                    @endif

                    {{-- Photo --}}
                    <div class="w-[90px] h-[108px] rounded-[3px] overflow-hidden mb-3 flex-shrink-0"
                        style="background:{{ $ac[0] }}">
                        @if($s->foto)
                            <img src="{{ Storage::url($s->foto) }}" alt="{{ $s->name }}" class="w-full h-full object-cover">
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

{{-- Modal Pengaturan Wali Kelas --}}
<div id="waliModal" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">

        {{-- Head --}}
        <div class="flex items-start justify-between gap-3 p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[15px] font-semibold text-gray-900 font-sans">Pengaturan Wali Kelas</h3>
                    <p class="text-xs text-gray-400 font-sans mt-0.5">{{ $kelas->nama_kelas }}</p>
                </div>
            </div>
            <button onclick="closeWaliModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <form action="{{ route('admin.kelas.updateWali', $kelas->id) }}" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PATCH')

            {{-- Wali saat ini --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-3">
                @php
                    $waliNow = $kelas->guruWali->user->name ?? 'Belum ditentukan';
                    $waliInitNow = collect(explode(' ', $waliNow))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->join('');
                @endphp
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold font-sans flex-shrink-0" id="previewAvatar">
                    {{ $waliInitNow }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-gray-400 font-sans uppercase tracking-wider mb-0.5">Wali kelas saat ini</p>
                    <p class="text-sm font-semibold text-gray-800 font-sans truncate" id="previewName">{{ $waliNow }}</p>
                </div>
                <span class="text-[10px] bg-green-50 text-green-700 font-semibold font-sans px-2.5 py-1 rounded-full shrink-0">Aktif</span>
            </div>

            {{-- Select guru --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 font-sans">
                    Pilih Guru Wali Kelas
                </label>
                <select name="guru_wali_id" id="guruWaliSelect" required
                        onchange="updateWaliPreview(this)"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm font-sans text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 bg-white transition-all">
                    <option value="">— Pilih Guru —</option>
                    @foreach($guruList as $guru)
                        <option value="{{ $guru->id }}"
                                data-nama="{{ $guru->user->name ?? 'Guru ' . $guru->id }}"
                                {{ $kelas->guru_id == $guru->id ? 'selected' : '' }}>
                            {{ $guru->user->name ?? 'Guru ' . $guru->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Info --}}
            <div class="flex gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-3.5 py-3">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                </svg>
                <p class="text-xs text-amber-700 font-sans leading-relaxed">
                    Perubahan wali kelas akan langsung berlaku. Pastikan guru yang dipilih sudah dikonfirmasi.
                </p>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2.5 pt-1">
                <button type="button" onclick="closeWaliModal()"
                        class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors font-sans">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors font-sans">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                        <polyline points="17,21 17,13 7,13 7,21"/>
                        <polyline points="7,3 7,8 15,8"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
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
function openWaliModal() {
    const m = document.getElementById('waliModal');
    m.classList.remove('hidden');
    m.style.display = 'flex';
}
function closeWaliModal() {
    const m = document.getElementById('waliModal');
    m.style.display = 'none';
    m.classList.add('hidden');
}
function updateWaliPreview(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    const nama = opt.dataset.nama;
    const initials = nama.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
    document.getElementById('previewName').textContent = nama;
    document.getElementById('previewAvatar').textContent = initials;
}
document.getElementById('waliModal').addEventListener('click', function(e) {
    if (e.target === this) closeWaliModal();
});
</script>