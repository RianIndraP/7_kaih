@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Dashboard Guru')

@section('content')

    <style>
        /* ── Hero banner ─────────────────────────────────────── */
        .guru-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .guru-hero::before {
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

        /* ── Stat chips in hero ──────────────────────────────── */
        .hero-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .14);
            border: 1.5px solid rgba(255, 255, 255, .22);
            border-radius: 14px;
            padding: 10px 14px;
        }

        .hero-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Info chip (white badge on hero) ─────────────────── */
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 30px;
            padding: 4px 10px;
            font-size: 11.5px;
            font-weight: 700;
            color: white;
        }

        /* ── Profile photo ───────────────────────────────────── */
        .profile-photo {
            width: 78px;
            height: 78px;
            border-radius: 20px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            border: 3px solid #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* ── Info row ────────────────────────────────────────── */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 10px;
            background: #f8fafc;
            border-radius: 9px;
            border: 1px solid #f1f5f9;
            transition: border-color .18s, background .18s;
        }

        .info-row:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .info-row-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
        }

        .info-row-val {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        .info-row.accent {
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            border-color: #bfdbfe;
        }

        .info-row.accent .info-row-label {
            color: #3b82f6;
        }

        .info-row.accent .info-row-val {
            color: #1d4ed8;
        }

        /* ── Quick action item ───────────────────────────────── */
        .quick-action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background: white;
            text-decoration: none;
            transition: all .22s;
            cursor: pointer;
        }

        .quick-action:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, .10);
        }

        .qa-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .22);
        }

        .quick-action:hover .qa-icon {
            transform: scale(1.1);
        }

        .qa-arrow {
            color: #d1d5db;
            transition: all .2s;
        }

        .quick-action:hover .qa-arrow {
            color: #3b82f6;
            transform: translateX(2px);
        }

        /* ── Alert cards ─────────────────────────────────────── */
        .alert-red {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            border: 1.5px solid #fca5a5;
            border-radius: 16px;
            padding: 13px 16px;
        }

        .alert-orange {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #fffbeb, #fff7ed);
            border: 1.5px solid #fcd34d;
            border-radius: 16px;
            padding: 13px 16px;
        }

        .alert-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-link {
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all .18s;
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        /* ── Date row ────────────────────────────────────────── */
        .date-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 12px 18px;
            transition: border-color .2s;
        }

        .date-row:hover {
            border-color: #bfdbfe;
        }

        .date-icon-wrap {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .22);
        }

        /* ── Status badge pill ───────────────────────────────── */
        .pill-blue {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 30px;
        }

        .pill-green {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dcfce7;
            color: #15803d;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 30px;
        }

        .pill-orange {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffedd5;
            color: #c2410c;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 30px;
        }

        /* ── Progress bar ────────────────────────────────────── */
        .progress-track {
            height: 8px;
            background: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill-blue {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #2563eb, #4f46e5);
            transition: width .7s ease;
        }

        .progress-fill-green {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #16a34a, #15803d);
            transition: width .7s ease;
        }

        /* ── Siswa belum mengisi list item ──────────────────── */
        .siswa-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 13px;
            border-radius: 12px;
            background: linear-gradient(135deg, #fffbeb, #fff7ed);
            border: 1.5px solid #fde68a;
            transition: all .18s;
        }

        .siswa-item:hover {
            border-color: #fbbf24;
            background: #fef9c3;
            transform: translateX(2px);
        }

        .siswa-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .siswa-item.done {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-color: #86efac;
        }

        .siswa-item.done:hover {
            border-color: #4ade80;
            background: #bbf7d0;
        }

        .siswa-item.done .siswa-avatar {
            background: linear-gradient(135deg, #22c55e, #16a34a);
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
            animation: fadeUp .36s .05s ease both;
        }

        .fu-2 {
            animation: fadeUp .36s .10s ease both;
        }

        .fu-3 {
            animation: fadeUp .36s .15s ease both;
        }

        .fu-4 {
            animation: fadeUp .36s .20s ease both;
        }

        /* ── Update message included component ───────────────── */
        .update-msg-wrap {
            animation: fadeUp .36s .04s ease both;
        }
    </style>

    @php
        $total = $totalSiswaAsuh ?? 0;
        $belum = $siswaBlumMengisi->count();
        $sudah = max(0, $total - $belum);
        $persen = $total > 0 ? round(($sudah / $total) * 100) : 100;
    @endphp

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══════════════════════════════════════════
        HERO
        ════════════════════════════════════════════ --}}
        <div class="guru-hero rounded-2xl px-6 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>

            <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                {{-- Left: greeting --}}
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/18 border-2 border-white/30 flex items-center justify-center shrink-0 overflow-hidden">
                        @if (!empty($user->foto))
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" class="w-full h-full object-cover" />
                        @else
                            <svg class="w-7 h-7 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1">
                            Selamat Datang, {{ auth()->user()->name }} 👋
                        </h1>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="hero-chip">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                            </span>
                            @if ($guru?->kelas_wali)
                                <span class="hero-chip">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                    </svg>
                                    Wali Kelas {{ $guru->kelas_wali }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: stat chips --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="hero-stat">
                        <div class="hero-stat-icon">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-extrabold text-[15px] leading-tight">{{ $total }}</p>
                            <p class="text-white/65 text-[11px]">Siswa Asuh</p>
                        </div>
                    </div>

                    <div class="hero-stat">
                        <div class="hero-stat-icon"
                            style="background:{{ $persen >= 100 ? 'rgba(34,197,94,.35)' : 'rgba(251,146,60,.3)' }}">
                            @if($persen >= 100)
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-white font-extrabold text-[15px] leading-tight">{{ $persen }}%</p>
                            <p class="text-white/65 text-[11px]">{{ $sudah }}/{{ $total }} mengisi</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress bar in hero --}}
            <div class="relative z-10 mt-4">
                <div class="h-1.5 rounded-full bg-white/15 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                        style="width:{{ $persen }}%; background:{{ $persen >= 100 ? '#4ade80' : 'white' }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Message --}}
        @php $updateMessage = \App\Models\WebsiteManagement::getUpdateMessage('guru'); @endphp
        <div class="update-msg-wrap">
            @include('guru._update_message', ['updateMessage' => $updateMessage])
        </div>

        {{-- ═══════════════════════════════════════════
        ALERTS
        ════════════════════════════════════════════ --}}
        @if (empty(auth()->user()->email) || empty(auth()->user()->no_telepon))
            <div class="alert-red fu-1">
                <div class="alert-icon bg-red-500">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">Profil Belum Lengkap</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Silakan lengkapi email dan nomor telepon untuk mengakses semua
                        fitur.</p>
                </div>
                <a href="{{ route('guru.profil') }}" class="alert-link text-red-600 hover:text-red-800">
                    Lengkapi Sekarang →
                </a>
            </div>
        @endif

        @if ($belum > 0)
            <div class="alert-orange fu-1">
                <div class="alert-icon bg-orange-500">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-gray-900">{{ $belum }} Siswa Belum Mengisi Form</p>
                    <p class="text-[12px] text-gray-500 mt-0.5">Beberapa siswa asuh belum melengkapi data kebiasaan hari ini.
                    </p>
                </div>
                <button onclick="scrollToSiswa()" class="alert-link text-orange-600 hover:text-orange-800">
                    Lihat Siswa →
                </button>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════
        DATE ROW
        ════════════════════════════════════════════ --}}
        <div class="date-row fu-2">
            <div class="flex items-center gap-3">
                <div class="date-icon-wrap">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10.5px] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Hari Ini</p>
                    <p class="text-[15px] font-extrabold text-gray-900">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap justify-end">
                <span class="pill-blue">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $total }} Siswa Asuh
                </span>
                @if ($belum === 0)
                    <span class="pill-green">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Semua Mengisi
                    </span>
                @else
                    <span class="pill-orange">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $belum }} Belum Mengisi
                    </span>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
        MAIN GRID
        ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 fu-3">

            {{-- ── KOLOM KIRI ──────────────────────────────── --}}
            <div class="flex flex-col gap-4">

                {{-- PROFIL GURU ──────────────────────────── --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Profil Guru</span>
                    </div>

                    <div class="flex items-start gap-4 p-4">
                        {{-- Photo --}}
                        <div class="profile-photo">
                            @if (!empty($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto"
                                    class="w-full h-full object-cover" />
                            @else
                                <svg class="w-9 h-9 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Rows --}}
                        <div class="flex flex-col gap-2 flex-1">
                            <div class="info-row">
                                <span class="info-row-label">Nama</span>
                                <span class="info-row-val">{{ auth()->user()->name }}</span>
                            </div>
                            @if (auth()->user()->nip)
                                <div class="info-row">
                                    <span class="info-row-label">NIP</span>
                                    <span class="info-row-val">{{ auth()->user()->nip }}</span>
                                </div>
                            @elseif(auth()->user()->nik)
                                <div class="info-row">
                                    <span class="info-row-label">NIK</span>
                                    <span class="info-row-val">{{ auth()->user()->nik }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-row-label">Status</span>
                                <span class="info-row-val">{{ $guru?->status_pegawai ?? '-' }}</span>
                            </div>
                            @if ($guru?->kelas_wali)
                                <div class="info-row">
                                    <span class="info-row-label">Kelas Wali</span>
                                    <span class="info-row-val">{{ $guru->kelas_wali }}</span>
                                </div>
                            @endif
                            @if ($guru?->jabatan)
                                <div class="info-row">
                                    <span class="info-row-label">Jabatan</span>
                                    <span class="info-row-val">{{ $guru->jabatan }}</span>
                                </div>
                            @endif
                            @if ($guru?->unit_kerja)
                                <div class="info-row">
                                    <span class="info-row-label">Unit Kerja</span>
                                    <span class="info-row-val">{{ $guru->unit_kerja }}</span>
                                </div>
                            @endif
                            <div class="info-row accent">
                                <span class="info-row-label">Total Siswa Asuh</span>
                                <span class="info-row-val">{{ $total }} Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AKSI CEPAT ────────────────────────────── --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Aksi Cepat</span>
                    </div>

                    <div class="p-3 flex flex-col gap-2.5">

                        <a href="{{ route('guru.pelaporan') }}" class="quick-action">
                            <div class="qa-icon">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                           01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-blue-700">Pelaporan Guru</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Buat dan kelola laporan siswa</p>
                            </div>
                            <svg class="qa-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <a href="{{ route('guru.absensi-murid') }}" class="quick-action">
                            <div class="qa-icon">
                                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900">Absen Siswa</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Rekap kehadiran siswa hari ini</p>
                            </div>
                            <svg class="qa-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                    </div>
                </div>

            </div>{{-- end left --}}

            {{-- ── KOLOM KANAN: Status Siswa ───────────────── --}}
            <div id="siswa-section" class="bg-white border border-gray-200 rounded-2xl overflow-hidden flex flex-col">

                <div class="section-header">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Status Pengisian Siswa</span>
                    @if ($belum > 0)
                        <span
                            class="ml-auto inline-flex items-center justify-center h-5 px-2 rounded-full text-[10px] font-extrabold text-white"
                            style="background:linear-gradient(135deg,#f97316,#ea580c);">
                            {{ $belum }} belum
                        </span>
                    @else
                        <span
                            class="ml-auto inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold text-green-700"
                            style="background:#dcfce7; border:1px solid #86efac;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Semua ✓
                        </span>
                    @endif
                </div>

                {{-- Progress --}}
                <div class="px-4 pt-3 pb-2 shrink-0">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Progres Pengisian</span>
                        <span class="text-[12px] font-extrabold {{ $persen >= 100 ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $persen }}% · {{ $sudah }}/{{ $total }} siswa
                        </span>
                    </div>
                    <div class="progress-track">
                        <div class="{{ $persen >= 100 ? 'progress-fill-green' : 'progress-fill-blue' }}"
                            style="width:{{ $persen }}%"></div>
                    </div>
                </div>

                {{-- List --}}
                <div class="flex flex-col gap-2 p-3 overflow-y-auto flex-1" style="max-height:360px;">
                    @forelse ($siswaBlumMengisi as $siswa)
                        <div class="siswa-item">
                            <div class="siswa-avatar">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 truncate">{{ $siswa->name }}</p>
                                <p class="text-[11px] text-orange-500 font-semibold mt-0.5">Belum melengkapi form hari ini</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1 text-[10.5px] font-bold
                                    bg-orange-100 text-orange-600 px-2.5 py-1 rounded-full border border-orange-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center flex-1">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                style="background:linear-gradient(135deg,#dcfce7,#bbf7d0); border:1.5px solid #86efac;">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-[14px] font-extrabold text-gray-800 mb-1">Semua Sudah Mengisi! 🎉</p>
                            <p class="text-[12px] text-gray-400">Seluruh siswa asuh telah melengkapi form hari ini.</p>
                        </div>
                    @endforelse
                </div>

            </div>{{-- end right --}}

        </div>{{-- end grid --}}

    </div>{{-- end max-width --}}

@endsection

@section('scripts')
    <script>
        function scrollToSiswa() {
            document.getElementById('siswa-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    </script>
@endsection