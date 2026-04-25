@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Kirim Pesan Bantuan')

@section('content')

    <style>
        /* ── Hero banner ─────────────────────────────────────── */
        .bantuan-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .bantuan-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-deco-1 {
            position: absolute;
            top: -40px;
            right: -20px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .hero-deco-2 {
            position: absolute;
            bottom: -30px;
            right: 80px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        /* ── Section header ──────────────────────────────────── */
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #e0e7ff;
            background: linear-gradient(90deg, #eff6ff, #eef2ff);
        }

        .section-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            flex-shrink: 0;
        }

        /* ── Fancy input / textarea / select ─────────────────── */
        .fancy-input,
        .fancy-textarea,
        .fancy-select {
            width: 100%;
            padding: 10px 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            font-family: inherit;
        }

        .fancy-input:hover,
        .fancy-textarea:hover,
        .fancy-select:hover {
            border-color: #bfdbfe;
            background: #fff;
        }

        .fancy-input:focus,
        .fancy-textarea:focus,
        .fancy-select:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .fancy-input::placeholder,
        .fancy-textarea::placeholder {
            color: #cbd5e1;
        }

        .fancy-textarea {
            resize: none;
        }

        .fancy-select {
            cursor: pointer;
        }

        .fancy-input.error,
        .fancy-textarea.error,
        .fancy-select.error {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, .12);
        }

        /* ── Field label ──────────────────────────────────────── */
        .field-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 5px;
            display: block;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        /* ── Kategori pill selector ──────────────────────────── */
        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .kategori-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 11px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            transition: all .18s;
            user-select: none;
        }

        .kategori-pill:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .kategori-pill.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
        }

        .kategori-pill input {
            display: none;
        }

        .kat-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            transition: all .18s;
            flex-shrink: 0;
            font-size: 14px;
        }

        .kategori-pill.selected .kat-icon {
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
        }

        .kat-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            transition: color .18s;
        }

        .kategori-pill.selected .kat-label {
            color: #1d4ed8;
        }

        /* ── Char counter ────────────────────────────────────── */
        .char-counter {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-align: right;
            margin-top: 4px;
        }

        .char-counter.warn {
            color: #f97316;
        }

        .char-counter.over {
            color: #ef4444;
        }

        /* ── Submit button ───────────────────────────────────── */
        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 11px 32px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
            font-family: inherit;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }

        /* ── Alert boxes ─────────────────────────────────────── */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1.5px solid #86efac;
            border-radius: 12px;
            padding: 12px 14px;
        }

        .alert-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Info card (kanan) ───────────────────────────────── */
        .info-card {
            background: linear-gradient(135deg, #f0f9ff, #eef2ff);
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 16px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: white;
            border: 1px solid #e0e7ff;
            transition: border-color .18s;
        }

        .info-row:hover {
            border-color: #bfdbfe;
        }

        .info-row-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            flex-shrink: 0;
        }

        /* ── Hero shield ─────────────────────────────────────── */
        .hero-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .18);
            border: 1.5px solid rgba(255, 255, 255, .28);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Stagger animations ──────────────────────────────── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fu-0 {
            animation: fadeUp .36s ease both;
        }

        .fu-1 {
            animation: fadeUp .36s .06s ease both;
        }

        .fu-2 {
            animation: fadeUp .36s .12s ease both;
        }

        .fu-3 {
            animation: fadeUp .36s .18s ease both;
        }

        /* ── Progress dots (step indicator) ─────────────────── */
        .step-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            transition: all .3s;
        }

        .step-dot.active {
            background: white;
            width: 22px;
            border-radius: 4px;
        }
    </style>

    @php $user = auth()->user(); @endphp

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════ --}}
        <div class="bantuan-hero rounded-2xl px-6 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>
            <div class="relative z-10 flex items-center gap-4 flex-wrap">
                <div class="hero-icon-wrap">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21
                               12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1">
                        Kirim Pesan Bantuan 🆘
                    </h1>
                    <p class="text-white/72 text-[12.5px]">Ada masalah atau pertanyaan? Kami siap membantu kamu</p>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
         GRID: FORM + INFO
    ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- ── FORM (2/3 lebar) ────────────────────────────── --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">
                <div class="section-header">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Form Pesan Bantuan</span>
                </div>

                <div class="p-5">

                    {{-- Alert sukses --}}
                    @if (session('success'))
                        <div class="alert-success mb-5">
                            <div class="alert-icon bg-green-500">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-[12.5px] font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.kirim-pesan-bantuan.store') }}" class="space-y-5"
                        id="bantuanForm">
                        @csrf

                        {{-- Nama Pengirim --}}
                        <div>
                            <label class="field-label">Nama Pengirim</label>
                            <input type="text" name="nama_pengirim" value="{{ old('nama_pengirim', $user->name ?? '') }}"
                                placeholder="Masukkan nama kamu..."
                                class="fancy-input @error('nama_pengirim') error @enderror" />
                            @error('nama_pengirim')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori Pesan (pill selector) --}}
                        <div>
                            <label class="field-label">Kategori Pesan</label>
                            <input type="hidden" name="kategori" id="kategoriHidden" value="{{ old('kategori') }}" />
                            <div class="kategori-grid">
                                @foreach ([['val' => 'teknis', 'label' => 'Masalah Teknis', 'emoji' => '⚙️'], ['val' => 'akun', 'label' => 'Masalah Akun', 'emoji' => '👤'], ['val' => 'kebiasaan', 'label' => 'Pertanyaan Kebiasaan', 'emoji' => '✅'], ['val' => 'profil', 'label' => 'Masalah Profil', 'emoji' => '📋'], ['val' => 'lainnya', 'label' => 'Lainnya', 'emoji' => '💬']] as $kat)
                                    <div class="kategori-pill {{ old('kategori') === $kat['val'] ? 'selected' : '' }}"
                                        data-val="{{ $kat['val'] }}" onclick="pilihKategori(this)">
                                        <div class="kat-icon">
                                            <span style="line-height:1;">{{ $kat['emoji'] }}</span>
                                        </div>
                                        <span class="kat-label">{{ $kat['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @error('kategori')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Judul Pesan --}}
                        <div>
                            <label class="field-label">Judul Pesan</label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                placeholder="Tuliskan judul pesan kamu..." maxlength="100"
                                oninput="updateJudulCounter(this)" class="fancy-input @error('judul') error @enderror" />
                            <div class="char-counter" id="judulCounter">0 / 100</div>
                            @error('judul')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Isi Pesan --}}
                        <div>
                            <label class="field-label">Isi Pesan</label>
                            <textarea name="isi" rows="6" id="isiPesan"
                                placeholder="Ceritakan masalah atau pertanyaan kamu secara detail agar kami bisa membantu dengan tepat..."
                                maxlength="1000" oninput="updateIsiCounter(this)" class="fancy-textarea @error('isi') error @enderror">{{ old('isi') }}</textarea>
                            <div class="char-counter" id="isiCounter">0 / 1000</div>
                            @error('isi')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="submit-btn" id="submitBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Pesan Bantuan
                        </button>

                    </form>
                </div>
            </div>

            {{-- ── INFO PANEL (1/3 lebar) ────────────────────── --}}
            <div class="flex flex-col gap-4 fu-2">

                {{-- Pengirim Info Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Info Pengirim</span>
                    </div>
                    <div class="p-4 space-y-2.5">
                        {{-- Avatar --}}
                        <div class="flex items-center gap-3 p-3 rounded-xl"
                            style="background:linear-gradient(135deg,#eff6ff,#eef2ff); border:1.5px solid #bfdbfe;">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden"
                                style="background:linear-gradient(135deg,#3b82f6,#4f46e5);">
                                @if (!empty($user->foto))
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto"
                                        class="w-full h-full object-cover" />
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-[13px] font-extrabold text-gray-900">{{ $user->name ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-indigo-600">
                                    {{ $user->kelas?->nama_kelas ?? '-' }}</p>
                            </div>
                        </div>

                        @foreach ([['label' => 'NISN', 'val' => $user->nisn ?? '-'], ['label' => 'Email', 'val' => $user->email ?? '-']] as $row)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 border border-slate-100">
                                <span
                                    class="text-[10.5px] font-bold text-gray-400 uppercase w-10 shrink-0">{{ $row['label'] }}</span>
                                <span
                                    class="text-[12px] font-semibold text-gray-700 flex-1 truncate">{{ $row['val'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Panduan Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-3">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Panduan Pengisian</span>
                    </div>
                    <div class="p-4 space-y-2.5">
                        @foreach ([['icon' => '1', 'text' => 'Pilih kategori yang paling sesuai dengan masalahmu'], ['icon' => '2', 'text' => 'Tuliskan judul singkat yang menggambarkan masalah'], ['icon' => '3', 'text' => 'Jelaskan masalah secara detail di bagian isi pesan'], ['icon' => '4', 'text' => 'Klik "Kirim" dan tim kami akan segera menghubungimu']] as $step)
                            <div class="flex items-start gap-3 p-2.5 rounded-xl"
                                style="background:linear-gradient(135deg,#f0f9ff,#eef2ff); border:1.5px solid #bfdbfe;">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 text-[11px] font-extrabold text-white"
                                    style="background:linear-gradient(135deg,#2563eb,#4f46e5);">
                                    {{ $step['icon'] }}
                                </div>
                                <p class="text-[12px] font-medium text-gray-700 leading-relaxed">{{ $step['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Waktu Respons Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Estimasi Respons</span>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach ([['label' => 'Masalah Teknis', 'time' => '1–2 hari kerja', 'color' => '#3b82f6'], ['label' => 'Masalah Akun', 'time' => '< 1 hari kerja', 'color' => '#22c55e'], ['label' => 'Lainnya', 'time' => '2–3 hari kerja', 'color' => '#a855f7']] as $resp)
                            <div
                                class="flex items-center justify-between px-3 py-2 rounded-lg bg-slate-50 border border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full" style="background:{{ $resp['color'] }};"></div>
                                    <span class="text-[12px] font-semibold text-gray-700">{{ $resp['label'] }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-gray-500">{{ $resp['time'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>{{-- end max-width --}}

    <script>
        /* ── Kategori pill selector ───────────────────────────── */
        function pilihKategori(el) {
            document.querySelectorAll('.kategori-pill').forEach(p => p.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('kategoriHidden').value = el.dataset.val;
        }

        /* ── Char counters ───────────────────────────────────── */
        function updateJudulCounter(inp) {
            const el = document.getElementById('judulCounter');
            const len = inp.value.length;
            const max = 100;
            el.textContent = len + ' / ' + max;
            el.className = 'char-counter' + (len >= max ? ' over' : len >= max * .8 ? ' warn' : '');
        }

        function updateIsiCounter(inp) {
            const el = document.getElementById('isiCounter');
            const len = inp.value.length;
            const max = 1000;
            el.textContent = len + ' / ' + max;
            el.className = 'char-counter' + (len >= max ? ' over' : len >= max * .8 ? ' warn' : '');
        }

        /* ── Init counters on load ───────────────────────────── */
        document.addEventListener('DOMContentLoaded', () => {
            const judulInp = document.querySelector('input[name="judul"]');
            const isiInp = document.getElementById('isiPesan');
            if (judulInp?.value) updateJudulCounter(judulInp);
            if (isiInp?.value) updateIsiCounter(isiInp);

            // Restore selected pill from old input
            const oldKat = document.getElementById('kategoriHidden').value;
            if (oldKat) {
                const pill = document.querySelector(`.kategori-pill[data-val="${oldKat}"]`);
                if (pill) pill.classList.add('selected');
            }
        });

        /* ── Submit loading state ────────────────────────────── */
        document.getElementById('bantuanForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Mengirim...`;
        });
    </script>

@endsection