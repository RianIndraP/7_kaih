@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | 7 Kebiasaan')

@section('content')

    <style>
        .hidden-field {
            display: none;
        }

        input[type="time"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }

        /* ── Hero banner ─────────────────────────────────────── */
        .kebias-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .kebias-hero::before {
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

        /* ── Stepper / tab pill row ──────────────────────────── */
        .tab-pill-bar {
            display: flex;
            gap: 6px;
            padding: 14px 16px;
            overflow-x: auto;
            border-bottom: 1.5px solid #e0e7ff;
            background: linear-gradient(90deg, #f8faff, #f5f3ff);
            scrollbar-width: none;
        }

        .tab-pill-bar::-webkit-scrollbar {
            display: none;
        }

        .tab-pill {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            border: 1.5px solid #e2e8f0;
            background: white;
            color: #64748b;
            cursor: pointer;
            transition: all .22s;
            flex-shrink: 0;
        }

        .tab-pill:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #2563eb;
        }

        .tab-pill.active {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
        }

        .tab-pill .pill-check {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .22);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 9px;
        }

        .tab-pill.done-pill {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .tab-pill.done-pill .pill-check {
            background: #22c55e;
        }

        .tab-pill.done-pill.active {
            background: linear-gradient(135deg, #16a34a, #15803d);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 14px rgba(22, 163, 74, .28);
        }

        /* ── Section header (same as profile) ───────────────── */
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

        /* ── Quote card ──────────────────────────────────────── */
        .quote-card {
            background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 16px;
            font-size: 13px;
            color: #374151;
            line-height: 1.65;
            position: relative;
        }

        .quote-card::before {
            content: '"';
            position: absolute;
            top: 8px;
            left: 14px;
            font-size: 48px;
            line-height: 1;
            color: rgba(99, 102, 241, .15);
            font-family: Georgia, serif;
            pointer-events: none;
        }

        .quote-card p {
            padding-left: 6px;
        }

        .quote-card .hadith {
            margin-top: 10px;
            font-style: italic;
            color: #4f46e5;
            font-size: 12.5px;
            background: white;
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            padding: 10px 12px;
        }

        /* ── Radio toggle ────────────────────────────────────── */
        .radio-group {
            display: flex;
            gap: 10px;
        }

        .radio-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 30px;
            border: 1.5px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            transition: all .18s;
            user-select: none;
        }

        .radio-pill:has(input:checked) {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            color: #1d4ed8;
        }

        .radio-pill input {
            display: none;
        }

        .radio-pill-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            flex-shrink: 0;
            transition: all .18s;
        }

        .radio-pill:has(input:checked) .radio-pill-dot {
            border-color: #2563eb;
            background: #2563eb;
            box-shadow: inset 0 0 0 3px white;
        }

        /* ── Sholat checklist ────────────────────────────────── */
        .sholat-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            border: 1.5px solid #f1f5f9;
            background: #f8fafc;
            transition: all .18s;
        }

        .sholat-row:has(input[type=checkbox]:checked) {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .makan-row:has(input[type=checkbox]:checked) .custom-checkbox {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-color: transparent;
        }

        .makan-row:has(input[type=checkbox]:checked) .custom-checkbox svg {
            opacity: 1;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 2px solid #cbd5e1;
            background: white;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .18s;
        }

        .sholat-row:has(input[type=checkbox]:checked) .custom-checkbox {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-color: transparent;
        }

        .custom-checkbox svg {
            opacity: 0;
            transition: opacity .15s;
        }

        .sholat-row:has(input[type=checkbox]:checked) .custom-checkbox svg {
            opacity: 1;
        }


        /* ── Makan sehat checklist ───────────────────────────── */
        .makan-row {
            border: 1.5px solid #f1f5f9;
            border-radius: 12px;
            background: #f8fafc;
            padding: 12px;
            transition: all .18s;
        }

        .makan-row:has(input[type=checkbox]:checked) {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        /* ── Olahraga item card ──────────────────────────────── */
        .olahraga-item {
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            background: linear-gradient(135deg, #f0f9ff, #eef2ff);
            padding: 12px;
            space-y: 8px;
        }

        /* ── Fancy input/textarea ────────────────────────────── */
        .fancy-input,
        .fancy-textarea,
        .fancy-select {
            width: 100%;
            padding: 9px 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 500;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
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

        .fancy-textarea {
            resize: none;
        }

        .fancy-select {
            background: #f8fafc;
            cursor: pointer;
        }

        .fancy-input::placeholder,
        .fancy-textarea::placeholder {
            color: #cbd5e1;
        }

        .fancy-input:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        /* ── Kirim button ────────────────────────────────────── */
        .kirim-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
            font-family: inherit;
        }

        .kirim-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
        }

        .kirim-btn:active {
            transform: translateY(0);
        }

        /* ── Tambah btn (outline) ────────────────────────────── */
        .tambah-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: #2563eb;
            border: 1.5px solid #bfdbfe;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
        }

        .tambah-btn:hover {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        /* ── Lock overlay ────────────────────────────────────── */
        .lock-overlay {
            background: linear-gradient(135deg, #fffbeb, #fff7ed);
            border: 1.5px solid #fde68a;
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
        }

        .lock-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #f59e0b, #ea580c);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 6px 20px rgba(245, 158, 11, .30);
        }

        /* ── Bermasyarakat checkbox pills ────────────────────── */
        .ms-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 30px;
            border: 1.5px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            transition: all .18s;
            user-select: none;
        }

        .ms-pill:has(input:checked) {
            border-color: #2563eb;
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            color: #1d4ed8;
        }

        .ms-pill input {
            display: none;
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

        /* ── Time input wrapper ──────────────────────────────── */
        .time-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1.5px solid #bfdbfe;
            border-radius: 10px;
            padding: 8px 12px;
            width: fit-content;
        }

        .time-wrap svg {
            color: #3b82f6;
            flex-shrink: 0;
        }

        .time-wrap input[type=time] {
            border: none;
            outline: none;
            font-size: 13px;
            font-weight: 600;
            color: #1d4ed8;
            background: transparent;
        }

        /* Scrollbar progress dots */
        .progress-dots {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .prog-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #e2e8f0;
            transition: all .25s;
            flex-shrink: 0;
        }

        .prog-dot.done {
            background: #22c55e;
        }

        .prog-dot.active {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            width: 20px;
            border-radius: 4px;
        }
    </style>

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════ --}}
        @php
            $tabs = [
                [
                    'id' => 'bangun_pagi',
                    'label' => 'Bangun Pagi',
                    'icon' =>
                        'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707',
                ],
                [
                    'id' => 'beribadah',
                    'label' => 'Beribadah',
                    'icon' =>
                        'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                ],
                ['id' => 'berolahraga', 'label' => 'Berolahraga', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                [
                    'id' => 'makan_sehat',
                    'label' => 'Makan Sehat',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'id' => 'gemar_belajar',
                    'label' => 'Gemar Belajar',
                    'icon' =>
                        'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                ],
                [
                    'id' => 'bermasyarakat',
                    'label' => 'Bermasyarakat',
                    'icon' =>
                        'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                ],
                [
                    'id' => 'tidur_cepat',
                    'label' => 'Tidur Cepat',
                    'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
                ],
            ];
            $checklist = $kebiasaan->exists ? $kebiasaan->statusChecklist() : [];
            $selesaiCount = collect($checklist)->filter()->count();
            $totalCount = count($tabs);
            $persen = $totalCount > 0 ? round(($selesaiCount / $totalCount) * 100) : 0;
        @endphp

        <div class="kebias-hero rounded-2xl px-6 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>
            <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1">
                        7 Kebiasaan Anak Indonesia Hebat 🌟
                    </h1>
                    <p class="text-white/75 text-[13px]">
                        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                        · {{ $selesaiCount }}/{{ $totalCount }} kebiasaan selesai
                    </p>
                </div>
                {{-- Progress ring summary --}}
                <div class="flex items-center gap-3 bg-white/12 border border-white/20 rounded-2xl px-4 py-3">
                    <div class="relative w-12 h-12 flex-shrink-0">
                        <svg class="w-12 h-12 -rotate-90" viewBox="0 0 48 48">
                            <circle cx="24" cy="24" r="20" fill="none" stroke="rgba(255,255,255,.2)"
                                stroke-width="4" />
                            <circle cx="24" cy="24" r="20" fill="none" stroke="white" stroke-width="4"
                                stroke-dasharray="{{ round(2 * 3.14159 * 20) }}"
                                stroke-dashoffset="{{ round(2 * 3.14159 * 20 * (1 - $persen / 100)) }}"
                                stroke-linecap="round" />
                        </svg>
                        <span
                            class="absolute inset-0 flex items-center justify-center text-white font-extrabold text-[11px]">{{ $persen }}%</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-[13px]">{{ $persen }}% Lengkap</p>
                        <p class="text-white/65 text-[11px]">{{ $totalCount - $selesaiCount }} tersisa</p>
                    </div>
                </div>
            </div>

            {{-- Progress dots --}}
            <div class="relative z-10 flex items-center gap-2 mt-4 flex-wrap">
                @foreach ($tabs as $i => $tab)
                    @php $isDone = !empty($checklist[$tab['id']]); @endphp
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full
                    {{ $isDone ? 'bg-white/20' : 'bg-white/8' }}"
                        style="border: 1px solid {{ $isDone ? 'rgba(255,255,255,.35)' : 'rgba(255,255,255,.12)' }}">
                        <div
                            class="w-4 h-4 rounded-full flex items-center justify-center
                        {{ $isDone ? 'bg-green-400' : 'bg-white/20' }}">
                            @if ($isDone)
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <div class="w-1.5 h-1.5 rounded-full bg-white/50"></div>
                            @endif
                        </div>
                        <span class="text-white/85 text-[10.5px] font-semibold">{{ $tab['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
         MAIN CARD
    ════════════════════════════════════════════ --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">

            {{-- TAB PILL BAR --}}
            <div class="tab-pill-bar">
                @foreach ($tabs as $i => $tab)
                    @php $isDone = !empty($checklist[$tab['id']]); @endphp

                    <button onclick="switchTab('{{ $tab['id'] }}')" id="tab_{{ $tab['id'] }}"
                        class="tab-pill {{ $i === 0 ? 'active' : '' }} {{ $isDone ? 'done-pill' : '' }}">
                        <div class="pill-check">
                            @if ($isDone)
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <span style="font-size:9px;">{{ $i + 1 }}</span>
                            @endif
                        </div>
                        {{ $tab['label'] }}
                        @if ($tab['id'] === 'bangun_pagi')
                            <svg id="bangun-lock-icon" class="w-3 h-3 hidden text-blue-500" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                        @if ($tab['id'] === 'tidur_cepat')
                            <svg id="tidur-lock-icon" class="w-3 h-3 hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="p-5">

                {{-- ============================================================
                 TAB 1 – BANGUN PAGI
            ============================================================ --}}
                <div id="panel_bangun_pagi" class="tab-panel">

                    {{-- 1. Pesan Terkunci (Muncul jika sebelum jam 04:30) --}}
                    <div id="bangun_locked_message" class="lock-overlay hidden mb-5">
                        <div class="lock-icon-wrap" style="background: #e0f2fe;"> {{-- Warna biru muda subuh --}}
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-[17px] font-extrabold text-blue-900 mb-2">Belum Waktunya Bangun?</h3>
                        <p class="text-[13px] text-blue-700 mb-4">Form "Bangun Pagi" baru bisa diisi mulai <strong>jam 04:30
                                subuh</strong>.</p>
                    </div>

                    {{-- 2. Konten Form (Akan diburamkan oleh JS jika terkunci) --}}
                    <div id="bangun_form_content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="quote-card self-start">
                                <p>Bangun pagi dalam Islam sangat dianjurkan karena merupakan waktu penuh berkah, rezeki,
                                    dan
                                    doa khusus dari Rasulullah SAW.</p>
                                <div class="hadith">"Ya Allah, berkahilah umatku di waktu paginya." <strong>(HR. Abu
                                        Dawud)</strong></div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="field-label">Apakah kamu bangun pagi?</label>
                                    <div class="radio-group">
                                        <label class="radio-pill">
                                            <input type="radio" name="bp_status" value="iya"
                                                {{ $kebiasaan->bangun_pagi === true ? 'checked' : '' }}
                                                onchange="toggleShow('bp_jam_section', this.value === 'iya')" />
                                            <span class="radio-pill-dot"></span> Iya
                                        </label>
                                        <label class="radio-pill">
                                            <input type="radio" name="bp_status" value="tidak"
                                                {{ $kebiasaan->bangun_pagi === false ? 'checked' : '' }}
                                                onchange="toggleShow('bp_jam_section', this.value === 'iya')" />
                                            <span class="radio-pill-dot"></span> Tidak
                                        </label>
                                    </div>
                                </div>
                                <div id="bp_jam_section"
                                    class="{{ $kebiasaan->bangun_pagi === true ? '' : 'hidden-field' }}">
                                    <label class="field-label">Jam bangun</label>
                                    <div class="time-wrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <input type="time" id="bp_jam" name="bp_jam" min="04:30"
                                            max="06:00" step="60" oninput="cekBatasanJam(this)"
                                            onchange="cekBatasanJam(this)"
                                            value="{{ $kebiasaan->jam_bangun ? \Carbon\Carbon::parse($kebiasaan->jam_bangun)->format('H:i') : '05:30' }}" />
                                    </div>
                                </div>
                                <div>
                                    <label class="field-label">Catatan</label>
                                    <textarea id="bp_catatan" rows="4" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->bangun_catatan }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                            <button onclick="kirimKebiasaan('bangun_pagi')" class="kirim-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan & Lanjut
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 2 – BERIBADAH
            ============================================================ --}}
                <div id="panel_beribadah" class="tab-panel hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Kebiasaan beribadah bukanlah pilihan sampingan, melainkan alasan utama kita diciptakan.</p>
                            <div class="hadith">"Dan Aku tidak menciptakan jin dan manusia melainkan supaya mereka mengabdi
                                kepada-Ku." <strong>(QS. Adz-Dzariyat: 56)</strong></div>
                        </div>
                        <div class="space-y-4">
                            {{-- Sholat 5 waktu --}}
                            <div>
                                <label class="field-label">Sholat 5 Waktu</label>
                                <div class="space-y-2">
                                    @php
                                        $sholatList = [
                                            'subuh' => [
                                                'label' => 'Subuh',
                                                'default' => '04:30',
                                                'field' => 'sholat_subuh',
                                                'jam' => 'jam_sholat_subuh',
                                            ],
                                            'dzuhur' => [
                                                'label' => 'Dzuhur',
                                                'default' => '12:30',
                                                'field' => 'sholat_dzuhur',
                                                'jam' => 'jam_sholat_dzuhur',
                                            ],
                                            'ashar' => [
                                                'label' => 'Ashar',
                                                'default' => '15:30',
                                                'field' => 'sholat_ashar',
                                                'jam' => 'jam_sholat_ashar',
                                            ],
                                            'maghrib' => [
                                                'label' => 'Maghrib',
                                                'default' => '18:15',
                                                'field' => 'sholat_maghrib',
                                                'jam' => 'jam_sholat_maghrib',
                                            ],
                                            'isya' => [
                                                'label' => 'Isya',
                                                'default' => '19:30',
                                                'field' => 'sholat_isya',
                                                'jam' => 'jam_sholat_isya',
                                            ],
                                        ];
                                    @endphp
                                    @foreach ($sholatList as $key => $s)
                                        @php
                                            $isChecked = $kebiasaan->{$s['field']} ?? false;
                                            $now = \Carbon\Carbon::now();

                                            // Definisikan Batas Waktu berdasarkan input Anda
                                            $jadwal = [
                                                'subuh' => ['start' => '05:10', 'end' => '06:24'],
                                                'dzuhur' => ['start' => '12:40', 'end' => '15:55'],
                                                'ashar' => ['start' => '15:55', 'end' => '18:49'],
                                                'maghrib' => ['start' => '18:49', 'end' => '19:59'],
                                                'isya' => ['start' => '19:59', 'end' => '23:59'], // Akhir hari
                                            ];

                                            $start = \Carbon\Carbon::createFromTimeString($jadwal[$key]['start']);
                                            $isLocked = $now->lessThan($start);
                                        @endphp
                                        <div class="sholat-row {{ $isLocked ? 'opacity-50' : '' }}">
                                            <label
                                                class="flex items-center gap-2.5 {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }} flex-1">
                                                <div class="custom-checkbox {{ $isLocked ? 'bg-gray-200' : '' }}"
                                                    onclick="{{ $isLocked ? "tampilkanToast('Waktu $s[label] belum masuk!', 'red')" : 'this.previousElementSibling?.click()' }}">
                                                    <svg class="w-2.5 h-2.5 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <input type="checkbox" name="sholat[]" value="{{ $key }}"
                                                    id="cb_sholat_{{ $key }}" {{ $isChecked ? 'checked' : '' }}
                                                    {{ $isLocked ? 'disabled' : '' }}
                                                    onchange="toggleShow('jam_{{ $key }}_row', this.checked)"
                                                    class="hidden" />
                                                <span
                                                    class="text-[13px] font-semibold {{ $isLocked ? 'text-gray-400' : 'text-gray-700' }} w-16">
                                                    {{ $s['label'] }}
                                                </span>
                                            </label>

                                            <div id="jam_{{ $key }}_row"
                                                class="{{ $isChecked ? '' : 'hidden-field' }}">
                                                <div class="time-wrap" style="padding:5px 10px;">
                                                    <input type="time" id="jam_{{ $key }}"
                                                        name="jam_{{ $key }}"
                                                        min="{{ $jadwal[$key]['start'] }}"
                                                        max="{{ $jadwal[$key]['end'] }}"
                                                        onchange="validateSholatTime('{{ $key }}', this)"
                                                        value="{{ $kebiasaan->{$s['jam']} ? \Carbon\Carbon::parse($kebiasaan->{$s['jam']})->format('H:i') : $s['default'] }}"
                                                        style="font-size:12px;" />
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Baca Quran --}}
                            <div>
                                <label class="field-label">Baca Al-Quran</label>
                                <div class="radio-group mb-3">
                                    <label class="radio-pill">
                                        <input type="radio" name="quran_status" value="iya"
                                            {{ $kebiasaan->baca_quran === true ? 'checked' : '' }}
                                            onchange="toggleShow('quran_surah_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Iya
                                    </label>
                                    <label class="radio-pill">
                                        <input type="radio" name="quran_status" value="tidak"
                                            {{ $kebiasaan->baca_quran === false ? 'checked' : '' }}
                                            onchange="toggleShow('quran_surah_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Tidak
                                    </label>
                                </div>
                                <div id="quran_surah_section"
                                    class="{{ $kebiasaan->baca_quran === true ? '' : 'hidden-field' }}">
                                    <label class="field-label">Surah yang dibaca</label>
                                    <select name="quran_surah" class="fancy-select">
                                        <option value="">-- Pilih Surah --</option>
                                        @php
                                            $surahList = [
                                                1 => 'Al-Fatihah',
                                                2 => 'Al-Baqarah',
                                                3 => 'Ali Imran',
                                                4 => 'An-Nisa',
                                                5 => 'Al-Maidah',
                                                6 => 'Al-Anam',
                                                7 => 'Al-Araf',
                                                8 => 'Al-Anfal',
                                                9 => 'At-Taubah',
                                                10 => 'Yunus',
                                                11 => 'Hud',
                                                12 => 'Yusuf',
                                                13 => 'Ar-Rad',
                                                14 => 'Ibrahim',
                                                15 => 'Al-Hijr',
                                                16 => 'An-Nahl',
                                                17 => 'Al-Isra',
                                                18 => 'Al-Kahf',
                                                19 => 'Maryam',
                                                20 => 'Ta-Ha',
                                                21 => 'Al-Anbiya',
                                                22 => 'Al-Hajj',
                                                23 => 'Al-Muminun',
                                                24 => 'An-Nur',
                                                25 => 'Al-Furqan',
                                                26 => 'Asy-Syuara',
                                                27 => 'An-Naml',
                                                28 => 'Al-Qasas',
                                                29 => 'Al-Ankabut',
                                                30 => 'Ar-Rum',
                                                31 => 'Luqman',
                                                32 => 'As-Sajdah',
                                                33 => 'Al-Ahzab',
                                                34 => 'Saba',
                                                35 => 'Fatir',
                                                36 => 'Ya-Sin',
                                                37 => 'As-Saffat',
                                                38 => 'Sad',
                                                39 => 'Az-Zumar',
                                                40 => 'Ghafir',
                                                41 => 'Fussilat',
                                                42 => 'Asy-Syura',
                                                43 => 'Az-Zukhruf',
                                                44 => 'Ad-Dukhan',
                                                45 => 'Al-Jasiyah',
                                                46 => 'Al-Ahqaf',
                                                47 => 'Muhammad',
                                                48 => 'Al-Fath',
                                                49 => 'Al-Hujurat',
                                                50 => 'Qaf',
                                                51 => 'Az-Zariyat',
                                                52 => 'At-Tur',
                                                53 => 'An-Najm',
                                                54 => 'Al-Qamar',
                                                55 => 'Ar-Rahman',
                                                56 => 'Al-Waqiah',
                                                57 => 'Al-Hadid',
                                                58 => 'Al-Mujadilah',
                                                59 => 'Al-Hasyr',
                                                60 => 'Al-Mumtahanah',
                                                61 => 'As-Saf',
                                                62 => 'Al-Jumuah',
                                                63 => 'Al-Munafiqun',
                                                64 => 'At-Taghabun',
                                                65 => 'At-Talaq',
                                                66 => 'At-Tahrim',
                                                67 => 'Al-Mulk',
                                                68 => 'Al-Qalam',
                                                69 => 'Al-Haqqah',
                                                70 => 'Al-Maarij',
                                                71 => 'Nuh',
                                                72 => 'Al-Jin',
                                                73 => 'Al-Muzzammil',
                                                74 => 'Al-Muddassir',
                                                75 => 'Al-Qiyamah',
                                                76 => 'Al-Insan',
                                                77 => 'Al-Mursalat',
                                                78 => 'An-Naba',
                                                79 => 'An-Naziat',
                                                80 => 'Abasa',
                                                81 => 'At-Takwir',
                                                82 => 'Al-Infitar',
                                                83 => 'Al-Mutaffifin',
                                                84 => 'Al-Insyiqaq',
                                                85 => 'Al-Buruj',
                                                86 => 'At-Tariq',
                                                87 => 'Al-Ala',
                                                88 => 'Al-Ghasyiyah',
                                                89 => 'Al-Fajr',
                                                90 => 'Al-Balad',
                                                91 => 'Asy-Syams',
                                                92 => 'Al-Lail',
                                                93 => 'Ad-Duha',
                                                94 => 'Al-Insyirah',
                                                95 => 'At-Tin',
                                                96 => 'Al-Alaq',
                                                97 => 'Al-Qadr',
                                                98 => 'Al-Bayyinah',
                                                99 => 'Az-Zalzalah',
                                                100 => 'Al-Adiyat',
                                                101 => 'Al-Qariah',
                                                102 => 'At-Takasur',
                                                103 => 'Al-Asr',
                                                104 => 'Al-Humazah',
                                                105 => 'Al-Fil',
                                                106 => 'Quraisy',
                                                107 => 'Al-Maun',
                                                108 => 'Al-Kausar',
                                                109 => 'Al-Kafirun',
                                                110 => 'An-Nasr',
                                                111 => 'Al-Masad',
                                                112 => 'Al-Ikhlas',
                                                113 => 'Al-Falaq',
                                                114 => 'An-Nas',
                                            ];
                                        @endphp
                                        @foreach ($surahList as $no => $nama)
                                            <option value="{{ $no }}"
                                                {{ $kebiasaan->quran_surah == $no ? 'selected' : '' }}>
                                                {{ $no }}. {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Catatan</label>
                                <textarea id="ib_catatan" rows="3" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->ibadah_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                        <button onclick="kirimKebiasaan('beribadah')" class="kirim-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 3 – BEROLAHRAGA
            ============================================================ --}}
                <div id="panel_berolahraga" class="tab-panel hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Kesehatan bukan milik kita sepenuhnya, melainkan amanah. Berolahraga adalah cara memenuhi hak
                                tubuh tersebut.</p>
                            <div class="hadith">"Sesungguhnya tubuhmu memiliki hak atas dirimu." <strong>(HR.
                                    Bukhari)</strong></div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Apakah kamu berolahraga?</label>
                                <div class="radio-group">
                                    <label class="radio-pill">
                                        <input type="radio" name="ol_status" value="iya"
                                            {{ $kebiasaan->berolahraga === true ? 'checked' : '' }}
                                            onchange="toggleOlShow(this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Iya
                                    </label>
                                    <label class="radio-pill">
                                        <input type="radio" name="ol_status" value="tidak"
                                            {{ $kebiasaan->berolahraga === false ? 'checked' : '' }}
                                            onchange="toggleOlShow(this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Tidak
                                    </label>
                                </div>
                            </div>

                            <div id="ol_detail_section"
                                class="{{ $kebiasaan->berolahraga === true ? '' : 'hidden-field' }} space-y-2">
                                <label class="field-label">Jenis olahraga</label>
                                <div id="olahragaList" class="space-y-2">
                                    @php
                                        $jenisOlahraga = $kebiasaan->jenis_olahraga ?? [
                                            ['jenis' => 'badminton', 'catatan' => ''],
                                        ];
                                        $opsiOlahraga = [
                                            'atletik',
                                            'badminton',
                                            'basket',
                                            'bersepeda',
                                            'futsal',
                                            'jalan santai',
                                            'jogging',
                                            'kasti',
                                            'lari',
                                            'memanah',
                                            'pencak silat',
                                            'renang',
                                            'senam',
                                            'sepak bola',
                                            'skipping',
                                            'tenis meja',
                                            'voli',
                                            'lainnya',
                                        ];
                                    @endphp
                                    @foreach ($jenisOlahraga as $ol)
                                        @php
                                            $jenisFill = is_array($ol) ? $ol['jenis'] ?? $ol : $ol;
                                            $catatanFill = is_array($ol) ? $ol['catatan'] ?? '' : '';
                                        @endphp
                                        <div class="olahraga-item space-y-2">
                                            <div class="flex items-center gap-2">
                                                <select name="ol_jenis[]" class="fancy-select flex-1"
                                                    style="padding:8px 12px;">
                                                    @foreach ($opsiOlahraga as $opsi)
                                                        <option value="{{ $opsi }}"
                                                            {{ $jenisFill === $opsi ? 'selected' : '' }}>
                                                            {{ ucfirst($opsi) }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" onclick="hapusOlahraga(this)"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <textarea name="ol_catatan[]" rows="2" class="fancy-textarea" placeholder="Catatan olahraga ini...">{{ $catatanFill }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div id="ol_catatan_umum_section"
                                class="{{ $kebiasaan->berolahraga === false ? '' : 'hidden-field' }}">
                                <label class="field-label">Catatan</label>
                                <textarea id="ol_catatan_umum" rows="4" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->olahraga_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-indigo-100">
                        <div id="tambah_olahraga_btn"
                            class="{{ $kebiasaan->berolahraga === true ? '' : 'hidden-field' }}">
                            <button type="button" onclick="tambahOlahraga()" class="tambah-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah olahraga
                            </button>
                        </div>
                        <button onclick="kirimKebiasaan('berolahraga')" class="kirim-btn ml-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 4 – MAKAN SEHAT
            ============================================================ --}}
                <div id="panel_makan_sehat" class="tab-panel hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Al-Qur'an memerintahkan kita mencari yang halal sekaligus <em>thayyib</em> — bergizi dan
                                bersih bagi tubuh.</p>
                            <div class="hadith">"Wahai manusia! Makanlah dari makanan yang halal dan baik (thayyib) yang
                                terdapat di bumi..." <strong>(QS. Al-Baqarah: 168)</strong></div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Apakah makan sehat hari ini?</label>
                                <div class="radio-group">
                                    <label class="radio-pill">
                                        <input type="radio" name="mk_status" value="iya"
                                            {{ $kebiasaan->makan_sehat === true ? 'checked' : '' }}
                                            onchange="toggleShow('mk_detail_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Iya
                                    </label>
                                    <label class="radio-pill">
                                        <input type="radio" name="mk_status" value="tidak"
                                            {{ $kebiasaan->makan_sehat === false ? 'checked' : '' }}
                                            onchange="toggleShow('mk_detail_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Tidak
                                    </label>
                                </div>
                            </div>

                            <div id="mk_detail_section"
                                class="{{ $kebiasaan->makan_sehat === true ? '' : 'hidden-field' }} space-y-3">
                                @foreach ([['key' => 'pagi', 'label' => 'Makan Pagi', 'val' => $kebiasaan->makan_pagi, 'done' => $kebiasaan->makan_pagi_done], ['key' => 'siang', 'label' => 'Makan Siang', 'val' => $kebiasaan->makan_siang, 'done' => $kebiasaan->makan_siang_done], ['key' => 'malam', 'label' => 'Makan Malam', 'val' => $kebiasaan->makan_malam, 'done' => $kebiasaan->makan_malam_done]] as $makan)
                                    <div class="makan-row">
                                        <label class="flex items-center gap-2.5 cursor-pointer mb-2">
                                            {{-- Kotak Visual --}}
                                            <div class="custom-checkbox" id="cbox_vis_mk_{{ $makan['key'] }}"
                                                onclick="document.getElementById('cb_mk_{{ $makan['key'] }}').click()">
                                                <svg class="w-2.5 h-2.5 text-white {{ $makan['done'] ? 'opacity-100' : 'opacity-0' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>

                                            {{-- Input Asli (Hidden) --}}
                                            <input type="checkbox" id="cb_mk_{{ $makan['key'] }}"
                                                name="mk_{{ $makan['key'] }}_done" value="1"
                                                {{ $makan['done'] ? 'checked' : '' }}
                                                onchange="toggleMakanRow('{{ $makan['key'] }}', this.checked)"
                                                class="hidden" />

                                            <span class="text-[13px] font-bold text-gray-800">{{ $makan['label'] }}</span>
                                        </label>
                                        <input type="text" id="inp_mk_{{ $makan['key'] }}"
                                            name="mk_{{ $makan['key'] }}"
                                            value="{{ old('mk_' . $makan['key'], $makan['val']) }}"
                                            {{ !$makan['done'] ? 'disabled' : '' }} class="fancy-input"
                                            style="font-size:12px;"
                                            placeholder="{{ $makan['done'] ? 'Contoh: nasi goreng, roti...' : 'Centang untuk mengisi...' }}" />
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                <label class="field-label">Catatan</label>
                                <textarea id="mk_catatan" rows="3" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->makan_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                        <button onclick="kirimKebiasaan('makan_sehat')" class="kirim-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 5 – GEMAR BELAJAR
            ============================================================ --}}
                <div id="panel_gemar_belajar" class="tab-panel hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Belajar bukan pilihan, melainkan tugas setiap individu sepanjang hayat untuk mengenal
                                penciptanya dan dunianya.</p>
                            <div class="hadith">"Menuntut ilmu itu wajib atas setiap muslim." <strong>(HR. Ibnu
                                    Majah)</strong></div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Apakah kamu belajar hari ini?</label>
                                <div class="radio-group">
                                    <label class="radio-pill">
                                        <input type="radio" name="bl_status" value="iya"
                                            {{ $kebiasaan->gemar_belajar === true ? 'checked' : '' }}
                                            onchange="toggleShow('bl_pelajaran_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Iya
                                    </label>
                                    <label class="radio-pill">
                                        <input type="radio" name="bl_status" value="tidak"
                                            {{ $kebiasaan->gemar_belajar === false ? 'checked' : '' }}
                                            onchange="toggleShow('bl_pelajaran_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Tidak
                                    </label>
                                </div>
                            </div>
                            <div id="bl_pelajaran_section"
                                class="{{ $kebiasaan->gemar_belajar === true ? '' : 'hidden-field' }}">
                                <label class="field-label">Apa yang dipelajari?</label>
                                <input type="text" name="bl_pelajaran"
                                    value="{{ old('bl_pelajaran', $kebiasaan->materi_belajar) }}" class="fancy-input"
                                    placeholder="Contoh: matematika, pemrograman web..." />
                            </div>
                            <div>
                                <label class="field-label">Catatan</label>
                                <textarea id="bl_catatan" rows="5" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->belajar_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                        <button onclick="kirimKebiasaan('gemar_belajar')" class="kirim-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 6 – BERMASYARAKAT
            ============================================================ --}}
                <div id="panel_bermasyarakat" class="tab-panel hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Kebiasaan membantu sesama dan memberi solusi bagi masalah sosial adalah amalan yang sangat
                                mulia.</p>
                            <div class="hadith">"Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya."
                                <strong>(HR. Ahmad)</strong>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Dengan siapa kamu bermasyarakat?</label>
                                @php $bersamaData = $kebiasaan->bersama ?? []; @endphp
                                <div class="flex flex-wrap gap-2">
                                    @foreach (['keluarga' => '👨‍👩‍👧 Keluarga', 'teman' => '👫 Teman', 'tetangga' => '🏘️ Tetangga', 'publik' => '🌐 Publik'] as $val => $label)
                                        <label class="ms-pill">
                                            <input type="checkbox" name="ms_dengan[]" value="{{ $val }}"
                                                {{ in_array($val, $bersamaData) ? 'checked' : '' }} />
                                            {{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Catatan</label>
                                <textarea id="ms_catatan" rows="7" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->masyarakat_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                        <button onclick="kirimKebiasaan('bermasyarakat')" class="kirim-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan & Lanjut
                        </button>
                    </div>
                </div>

                {{-- ============================================================
                 TAB 7 – TIDUR CEPAT
            ============================================================ --}}
                <div id="panel_tidur_cepat" class="tab-panel hidden">

                    {{-- Lock message --}}
                    <div id="tidur_locked_message" class="lock-overlay hidden mb-5">
                        <div class="lock-icon-wrap">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-[17px] font-extrabold text-amber-800 mb-2">Form Terkunci</h3>
                        <p class="text-[13px] text-amber-700 mb-4">Form "Tidur Cepat" baru bisa diisi mulai <strong>jam 8
                                malam</strong>.</p>
                        <div
                            class="inline-flex items-center gap-2 bg-white border border-blue-200 rounded-xl px-4 py-2.5 text-[12px] font-semibold text-blue-700">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Waktu tidur ideal: 21:00 – 22:00
                        </div>
                    </div>

                    <div id="tidur_form_content" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="quote-card self-start">
                            <p>Rasulullah mengajarkan untuk segera beristirahat setelah Isya, agar tidak membuang waktu
                                untuk hal yang sia-sia.</p>
                            <div class="hadith">"Rasulullah membenci tidur sebelum salat Isya dan bincang-bincang (yang
                                tidak bermanfaat) setelahnya." <strong>(HR. Bukhari & Muslim)</strong></div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Apakah kamu tidur cepat?</label>
                                <div class="radio-group">
                                    <label class="radio-pill">
                                        <input type="radio" name="tc_status" value="iya"
                                            {{ $kebiasaan->tidur_cepat === true ? 'checked' : '' }}
                                            onchange="toggleShow('tc_jam_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Iya
                                    </label>
                                    <label class="radio-pill">
                                        <input type="radio" name="tc_status" value="tidak"
                                            {{ $kebiasaan->tidur_cepat === false ? 'checked' : '' }}
                                            onchange="toggleShow('tc_jam_section', this.value === 'iya')" />
                                        <span class="radio-pill-dot"></span> Tidak
                                    </label>
                                </div>
                            </div>
                            <div id="tc_jam_section"
                                class="{{ $kebiasaan->tidur_cepat === true ? '' : 'hidden-field' }}">
                                <label class="field-label">Jam tidur</label>
                                <div class="time-wrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <input type="time" id="tc_jam" name="tc_jam"
                                        value="{{ $kebiasaan->jam_tidur ? \Carbon\Carbon::parse($kebiasaan->jam_tidur)->format('H:i') : '21:30' }}" />
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Catatan</label>
                                <textarea id="tc_catatan" rows="5" class="fancy-textarea" placeholder="Tuliskan catatan...">{{ $kebiasaan->tidur_catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-5 pt-4 border-t border-indigo-100">
                        <button onclick="kirimKebiasaan('tidur_cepat')" class="kirim-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>

            </div>{{-- end panel wrapper --}}
        </div>{{-- end main card --}}
    </div>{{-- end max-width --}}

    {{-- Toast --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[9999] flex items-center gap-2.5 text-white text-[13px] font-bold
           px-4 py-3 rounded-2xl shadow-2xl opacity-0 -translate-y-3 pointer-events-none transition-all duration-300"
        style="background:linear-gradient(135deg,#2563eb,#4f46e5); min-width:220px;">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg"></span>
    </div>

    <script>
        const TAB_IDS = ['bangun_pagi', 'beribadah', 'berolahraga', 'makan_sehat', 'gemar_belajar', 'bermasyarakat',
            'tidur_cepat'
        ];

        const JADWAL_SHOLAT = {
            subuh: {
                start: "05:10",
                end: "06:24",
                msg: "Subuh dimulai jam 05:10 dan berakhir saat terbit matahari (06:24)."
            },
            dzuhur: {
                start: "12:40",
                end: "15:55",
                msg: "Dzuhur dimulai setelah matahari tergelincir (12:40)."
            },
            ashar: {
                start: "15:55",
                end: "18:49",
                msg: "Ashar dimulai jam 15:55 hingga matahari terbenam."
            },
            maghrib: {
                start: "18:49",
                end: "19:59",
                msg: "Maghrib sangat singkat, dari matahari terbenam sampai syafaq hilang."
            },
            isya: {
                start: "19:59",
                end: "23:59",
                msg: "Isya dimulai setelah hilangnya cahaya kemerahan di ufuk barat."
            }
        };

        /* ── Tab switching ─────────────────────────────────────── */
        function switchTab(id) {
            TAB_IDS.forEach(tid => {
                const panel = document.getElementById('panel_' + tid);
                const btn = document.getElementById('tab_' + tid);
                const isDone = btn?.classList.contains('done-pill');
                if (tid === id) {
                    panel?.classList.remove('hidden');
                    btn?.classList.add('active');
                } else {
                    panel?.classList.add('hidden');
                    btn?.classList.remove('active');
                }
            });
        }

        /* ── Show / hide helper ────────────────────────────────── */
        function toggleShow(id, show) {
            const el = document.getElementById(id);
            if (!el) return;
            show ? el.classList.remove('hidden-field') : el.classList.add('hidden-field');
        }

        /* ── Olahraga show helpers ────────────────────────────── */
        function toggleOlShow(isIya) {
            toggleShow('ol_detail_section', isIya);
            toggleShow('tambah_olahraga_btn', isIya);
            toggleShow('ol_catatan_umum_section', !isIya);
        }

        function tambahOlahraga() {
            const list = document.getElementById('olahragaList');
            const opsi = [
                'atletik', 'badminton', 'basket', 'bersepeda', 'futsal',
                'jalan santai', 'jogging', 'kasti', 'lari', 'memanah',
                'pencak silat', 'renang', 'senam', 'sepak bola', 'skipping',
                'tenis meja', 'voli', 'lainnya'
            ];
            const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);

            const div = document.createElement('div');
            div.className = 'olahraga-item space-y-2';
            div.innerHTML =
                `<div class="flex items-center gap-2">
            <select name="ol_jenis[]" class="fancy-select flex-1" style="padding:8px 12px;">
                ${opsi.map(o => `<option value="${o}">${capitalize(o)}</option>`).join('')}
            </select>
            <button type="button" onclick="hapusOlahraga(this)"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <textarea name="ol_catatan[]" rows="2" class="fancy-textarea" placeholder="Catatan olahraga ini..."></textarea>`;
            list.appendChild(div);
        }

        function hapusOlahraga(btn) {
            const list = document.getElementById('olahragaList');
            if (list.querySelectorAll('.olahraga-item').length > 1) btn.closest('.olahraga-item').remove();
        }

        /* ── Makan sehat checkbox ──────────────────────────────── */
        function toggleMakanRow(key, enabled) {
            const inp = document.getElementById('inp_mk_' + key);
            const cbox = document.getElementById('cbox_vis_mk_' + key);
            if (!inp) return;

            inp.disabled = !enabled;
            inp.placeholder = enabled ? 'Contoh: nasi goreng, roti...' : 'Centang untuk mengisi...';
            if (!enabled) inp.value = '';

            // Update Kotak Visual (Biru jika aktif)
            if (cbox) {
                if (enabled) {
                    cbox.style.backgroundColor = '#2563eb';
                    cbox.style.borderColor = '#2563eb';
                } else {
                    cbox.style.backgroundColor = 'white';
                    cbox.style.borderColor = '#d1d5db';
                }
            }

            // Update Centang (SVG)
            const svg = cbox?.querySelector('svg');
            if (svg) svg.classList.toggle('opacity-100', enabled);
            if (svg) svg.classList.toggle('opacity-0', !enabled);

            // Sync baris background & border
            const row = inp.closest('.makan-row');
            if (row) {
                row.style.borderColor = enabled ? '#bfdbfe' : '#f1f5f9';
                row.style.background = enabled ? '#eff6ff' : '#f8fafc';
            }

            if (enabled) inp.focus();
        }


        /* ── Toast ─────────────────────────────────────────────── */
        function tampilkanToast(pesan, warna = 'green') {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toastMsg');
            toast.style.background = warna === 'red' ?
                'linear-gradient(135deg,#dc2626,#b91c1c)' :
                'linear-gradient(135deg,#2563eb,#4f46e5)';
            msg.textContent = pesan;
            toast.classList.remove('opacity-0', '-translate-y-3', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-3', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 3200);
        }

        function cekBatasanJam(input) {
            const jamInput = input.value; // Formatnya HH:mm (24 jam)
            const min = "04:30";
            const max = "06:00";

            if (jamInput) {
                if (jamInput < min) {
                    tampilkanToast('Terlalu pagi! Minimal jam 04:30', 'red');
                    input.value = min;
                } else if (jamInput > max) {
                    tampilkanToast('Maksimal jam 06:00 untuk bangun pagi!', 'red');
                    input.value = max;
                }
            }
        }

        function validateSholatTime(sholat, input) {
            const jam = input.value;
            const batas = JADWAL_SHOLAT[sholat];

            if (jam < batas.start) {
                tampilkanToast(`Kenapa memilih jam ${jam}? Itu belum masuk waktu ${sholat}. ${batas.msg}`, 'red');
                input.value = batas.start;
            } else if (jam > batas.end && sholat !== 'isya') {
                tampilkanToast(`Jam ${jam} sudah melewati waktu ${sholat}! Pastikan mengisi waktu sholat yang tepat.`,
                    'red');
                input.value = batas.end;
            }
        }


        /* ── Kirim kebiasaan ───────────────────────────────────── */
        function kirimKebiasaan(section) {
            const data = {
                section,
                tanggal: '{{ $tanggal }}'
            };

            switch (section) {
                case 'bangun_pagi':
                    data.status = document.querySelector('input[name="bp_status"]:checked')?.value ?? null;
                    data.jam = document.getElementById('bp_jam')?.value || null;
                    data.catatan = document.getElementById('bp_catatan')?.value;

                    if (data.status === 'iya' && data.jam) {
                        // Memastikan jam dalam format 24 jam yang bersih
                        const [hour, minute] = data.jam.split(':').map(Number);
                        const totalMenit = (hour * 60) + minute;

                        const menitMin = (4 * 60) + 30; // 04:30 = 270 menit
                        const menitMax = (6 * 60) + 0; // 06:00 = 360 menit

                        if (totalMenit < menitMin || totalMenit > menitMax) {
                            tampilkanToast('Jam bangun harus antara 04:30 sampai 06:00!', 'red');
                            return;
                        }
                    }
                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('bp_catatan').focus();
                        return;
                    }
                    break;

                case 'beribadah':
                    data.sholat = {};
                    let isAllJamValid = true; // Jaring pengaman untuk validasi jam

                    ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'].forEach(w => {
                        const isChecked = document.getElementById('cb_sholat_' + w)?.checked;
                        const jamValue = document.getElementById('jam_' + w)?.value;

                        // Simpan status checkbox
                        data.sholat[w] = isChecked ? 1 : 0;
                        data['jam_' + w] = jamValue || null;

                        // JIKA DICENTANG, CEK APAKAH JAMNYA MASUK AKAL
                        if (isChecked && jamValue) {
                            const batas = JADWAL_SHOLAT[w];
                            // Khusus Isya, batas akhirnya bisa sampai lewat tengah malam (05:10 besok)
                            if (w !== 'isya') {
                                if (jamValue < batas.start || jamValue > batas.end) {
                                    tampilkanToast(`Jam ${jamValue} bukan waktu ${w}! ${batas.msg}`, 'red');
                                    document.getElementById('jam_' + w).focus();
                                    isAllJamValid = false;
                                }
                            } else {
                                // Logika Isya: Mulai 19:59 sampai 05:10 pagi
                                if (jamValue < batas.start && jamValue > "05:10") {
                                    tampilkanToast(`Jam ${jamValue} bukan waktu Isya! ${batas.msg}`, 'red');
                                    document.getElementById('jam_' + w).focus();
                                    isAllJamValid = false;
                                }
                            }
                        }
                    });

                    // Jika ada jam yang salah, berhenti di sini (tidak kirim ke database)
                    if (!isAllJamValid) return;

                    data.quran = document.querySelector('input[name="quran_status"]:checked')?.value ?? null;
                    data.surah = document.querySelector('select[name="quran_surah"]')?.value || null;
                    data.catatan = document.getElementById('ib_catatan')?.value;

                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('ib_catatan').focus();
                        return;
                    }
                    break;

                case 'berolahraga':
                    data.status = document.querySelector('input[name="ol_status"]:checked')?.value ?? null;
                    if (data.status === 'iya') {
                        const jenis = [...document.querySelectorAll('select[name="ol_jenis[]"]')].map(e => e.value);
                        const catatan = [...document.querySelectorAll('textarea[name="ol_catatan[]"]')].map(e => e.value);
                        for (let i = 0; i < catatan.length; i++) {
                            if (!catatan[i]?.trim()) {
                                tampilkanToast('Catatan olahraga wajib diisi!', 'red');
                                document.querySelectorAll('textarea[name="ol_catatan[]"]')[i].focus();
                                return;
                            }
                        }
                        data.jenis = jenis.map((j, i) => ({
                            jenis: j,
                            catatan: catatan[i] || ''
                        }));
                    } else {
                        data.catatan = document.getElementById('ol_catatan_umum')?.value;
                        if (!data.catatan?.trim()) {
                            tampilkanToast('Catatan wajib diisi!', 'red');
                            document.getElementById('ol_catatan_umum').focus();
                            return;
                        }
                    }
                    break;

                case 'makan_sehat':
                    data.status = document.querySelector('input[name="mk_status"]:checked')?.value ?? null;

                    if (data.status === 'iya') {
                        let isMakanValid = true;
                        ['pagi', 'siang', 'malam'].forEach(k => {
                            const isDone = document.getElementById('cb_mk_' + k)?.checked;
                            const val = document.getElementById('inp_mk_' + k)?.value;

                            data[k] = val || null;
                            data[k + '_done'] = isDone ? 1 : 0;

                            // Jika dicentang tapi teks kosong, beri peringatan
                            if (isDone && !val?.trim()) {
                                tampilkanToast(`Isi menu makan ${k} kamu!`, 'red');
                                document.getElementById('inp_mk_' + k).focus();
                                isMakanValid = false;
                            }
                        });
                        if (!isMakanValid) return;
                    }

                    data.catatan = document.getElementById('mk_catatan')?.value;
                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('mk_catatan').focus();
                        return;
                    }
                    break;

                case 'gemar_belajar':
                    data.status = document.querySelector('input[name="bl_status"]:checked')?.value ?? null;
                    data.pelajaran = document.querySelector('input[name="bl_pelajaran"]')?.value || null;
                    data.catatan = document.getElementById('bl_catatan')?.value;
                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('bl_catatan').focus();
                        return;
                    }
                    break;

                case 'bermasyarakat':
                    data.dengan = [...document.querySelectorAll('input[name="ms_dengan[]"]:checked')].map(e => e.value);
                    data.catatan = document.getElementById('ms_catatan')?.value;
                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('ms_catatan').focus();
                        return;
                    }
                    break;

                case 'tidur_cepat':
                    data.status = document.querySelector('input[name="tc_status"]:checked')?.value ?? null;
                    data.jam = document.getElementById('tc_jam')?.value || null;
                    data.catatan = document.getElementById('tc_catatan')?.value;
                    if (!data.catatan?.trim()) {
                        tampilkanToast('Catatan wajib diisi!', 'red');
                        document.getElementById('tc_catatan').focus();
                        return;
                    }
                    break;
            }

            fetch('{{ route('student.kebiasaan.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data),
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        tampilkanToast('Data berhasil disimpan! ✓', 'green');

                        // Mark tab pill as done
                        const btn = document.getElementById('tab_' + section);
                        if (btn) {
                            btn.classList.remove('active');
                            btn.classList.add('done-pill');
                            // Update pill-check to checkmark
                            const check = btn.querySelector('.pill-check');
                            if (check) {
                                check.innerHTML =
                                    `<svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>`;
                            }
                        }

                        // Auto-forward to next section
                        const idx = TAB_IDS.indexOf(section);
                        if (idx !== -1 && idx < TAB_IDS.length - 1) {
                            setTimeout(() => switchTab(TAB_IDS[idx + 1]), 500);
                        }
                    } else {
                        tampilkanToast('Gagal: ' + (res.message ?? 'Terjadi kesalahan'), 'red');
                    }
                })
                .catch(() => tampilkanToast('Gagal terhubung ke server.', 'red'));
        }

        /* ── Bangun Pagi Time Lock (04:30) ───────────────────────── */
        function checkBangunLock() {
            const now = new Date();
            const totalMinutesNow = (now.getHours() * 60) + now.getMinutes();
            const unlockTime = (4 * 60) + 30; // 04:30 = 270 menit

            const isUnlocked = totalMinutesNow >= unlockTime;

            const lockedMsg = document.getElementById('bangun_locked_message');
            const form = document.getElementById('bangun_form_content');
            const tabBtn = document.getElementById('tab_bangun_pagi');

            if (isUnlocked) {
                lockedMsg?.classList.add('hidden');
                form?.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                // Hanya tampilkan pesan kunci jika tab Bangun Pagi sedang aktif
                if (!document.getElementById('panel_bangun_pagi')?.classList.contains('hidden')) {
                    lockedMsg?.classList.remove('hidden');
                }
                form?.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        /* ── Tidur Cepat Time Lock ─────────────────────────────── */
        function checkTidurLock() {
            const hour = new Date().getHours();
            const isUnlocked = hour >= 20;
            const lockIcon = document.getElementById('tidur-lock-icon');
            const lockedMsg = document.getElementById('tidur_locked_message');
            const form = document.getElementById('tidur_form_content');
            const tabBtn = document.getElementById('tab_tidur_cepat');

            if (isUnlocked) {
                lockIcon?.classList.add('hidden');
                lockedMsg?.classList.add('hidden');
                form?.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                lockIcon?.classList.remove('hidden');
                if (tabBtn?.classList.contains('active') || document.getElementById('panel_tidur_cepat')?.classList
                    .contains('hidden') === false) {
                    lockedMsg?.classList.remove('hidden');
                }
                form?.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        // Override switchTab to also check lock for tidur_cepat
        const _origSwitchTab = switchTab;
        window.switchTab = function(id) {
            _origSwitchTab(id);
            if (id === 'bangun_pagi') checkBangunLock();
            if (id === 'tidur_cepat') checkTidurLock();
        };

        document.addEventListener('DOMContentLoaded', () => {
            checkBangunLock();
            checkTidurLock();

            setInterval(() => {
                checkTidurLock();
                checkBangunLock();
            }, 60000);
        });
    </script>

@endsection
