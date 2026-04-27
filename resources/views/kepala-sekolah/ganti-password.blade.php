@extends('layouts.kepala-sekolah')

@section('title', 'SMK N 5 Telkom Banda Aceh | Ganti Password')

@section('content')

    <style>
        /* ── Hero banner ─────────────────────────────────────── */
        .pw-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .pw-hero::before {
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

        /* ── Back button ─────────────────────────────────────── */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .28);
            color: white;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, .25);
            transform: translateX(-2px);
        }

        /* ── Fancy input ─────────────────────────────────────── */
        .fancy-input {
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

        .fancy-input:hover {
            border-color: #bfdbfe;
            background: #fff;
        }

        .fancy-input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .fancy-input::placeholder {
            color: #cbd5e1;
        }

        .fancy-input[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        .fancy-input.error {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, .12);
        }

        /* ── Password wrapper (eye toggle) ──────────────────── */
        .pw-wrap {
            position: relative;
        }

        .pw-wrap .fancy-input {
            padding-right: 42px;
        }

        .pw-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: color .18s;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .pw-eye:hover {
            color: #3b82f6;
        }

        /* ── Strength bar ────────────────────────────────────── */
        .strength-bar-wrap {
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width .35s ease, background .35s;
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

        /* ── Submit button ───────────────────────────────────── */
        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 11px 28px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
            font-family: inherit;
            width: 100%;
            justify-content: center;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* ── Data row (read-only) ────────────────────────────── */
        .data-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
        }

        .data-row-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            width: 110px;
            flex-shrink: 0;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .data-row-val {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
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
            margin-bottom: 16px;
        }

        .alert-error {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #fff1f2, #ffe4e6);
            border: 1.5px solid #fca5a5;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
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

        /* ── Checklist tips ──────────────────────────────────── */
        .tip-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            transition: color .2s;
        }

        .tip-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .2s;
        }

        .tip-item.ok .tip-dot {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-color: transparent;
        }

        .tip-item.ok {
            color: #1d4ed8;
        }

        .tip-item.ok svg {
            opacity: 1;
        }

        /* ── Avatar circle ───────────────────────────────────── */
        .avatar-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
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

        /* ── Shield icon decoration ──────────────────────────── */
        .shield-wrap {
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
    </style>

    @php $user = auth()->user(); @endphp

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════ --}}
        <div class="pw-hero rounded-2xl px-6 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>

            <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-4">
                    <div class="shield-wrap">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0
                                   01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332
                                   9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1">
                            Ganti Password 🔐
                        </h1>
                        <p class="text-white/72 text-[12.5px]">Pastikan passwordmu kuat dan tidak mudah ditebak</p>
                    </div>
                </div>

                <a href="{{ route('kepala-sekolah.dashboard') }}" class="back-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
         GRID: FORM + DATA KEPALA SEKOLAH
    ════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- ── FORM GANTI PASSWORD ──────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">
                <div class="section-header">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0
                                   01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Ganti Password</span>
                </div>

                <div class="p-5">

                    {{-- Alert sukses --}}
                    @if (session('success'))
                        <div class="alert-success">
                            <div class="alert-icon bg-green-500">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-[12.5px] font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Alert error --}}
                    @if (session('error'))
                        <div class="alert-error">
                            <div class="alert-icon bg-red-500">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                            </div>
                            <p class="text-[12.5px] font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kepala-sekolah.ganti-password.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- NIP/NIK read-only --}}
                        <div>
                            <label class="field-label">{{ !empty($user->nip) ? 'NIP' : 'NIK' }}</label>
                            <input type="text" value="{{ !empty($user->nip) ? ($user->nip ?? '') : ($user->nik ?? '') }}" readonly class="fancy-input" />
                        </div>

                        {{-- Password Lama --}}
                        <div>
                            <label class="field-label">Password Lama</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_lama" id="pw_lama"
                                    placeholder="Masukkan password lama..."
                                    class="fancy-input @error('password_lama') error @enderror" />
                                <button type="button" class="pw-eye" onclick="togglePw('pw_lama', this)">
                                    <svg class="w-4 h-4 eye-off" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0
                                               011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88
                                               9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112
                                               5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg class="w-4 h-4 eye-on hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password_lama')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label class="field-label">Password Baru</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_baru" id="pw_baru"
                                    placeholder="Masukkan password baru..." oninput="checkStrength(this.value)"
                                    class="fancy-input @error('password_baru') error @enderror" />
                                <button type="button" class="pw-eye" onclick="togglePw('pw_baru', this)">
                                    <svg class="w-4 h-4 eye-off" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0
                                               011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88
                                               9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112
                                               5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg class="w-4 h-4 eye-on hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Strength bar --}}
                            <div class="strength-bar-wrap mt-1.5">
                                <div id="strengthBar" class="strength-bar"></div>
                            </div>
                            <p id="strengthLabel" class="text-[11px] font-semibold mt-1 text-gray-400"></p>

                            @error('password_baru')
                                <p class="text-[11.5px] text-red-500 mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label class="field-label">Konfirmasi Password Baru</label>
                            <div class="pw-wrap">
                                <input type="password" name="password_baru_confirmation" id="pw_konfirm"
                                    placeholder="Ulangi password baru..." oninput="checkMatch()" class="fancy-input" />
                                <button type="button" class="pw-eye" onclick="togglePw('pw_konfirm', this)">
                                    <svg class="w-4 h-4 eye-off" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0
                                               011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88
                                               9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112
                                               5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <svg class="w-4 h-4 eye-on hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p id="matchLabel" class="text-[11px] font-semibold mt-1"></p>
                        </div>

                        {{-- Syarat password --}}
                        <div
                            class="bg-linear-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-3 space-y-1.5">
                            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Syarat Password
                            </p>
                            <div class="tip-item" id="tip_len">
                                <div class="tip-dot">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="opacity:0">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                Minimal 8 karakter
                            </div>
                            <div class="tip-item" id="tip_upper">
                                <div class="tip-dot">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="opacity:0">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                Mengandung huruf besar (A-Z)
                            </div>
                            <div class="tip-item" id="tip_num">
                                <div class="tip-dot">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="opacity:0">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                Mengandung angka (0-9)
                            </div>
                            <div class="tip-item" id="tip_sym">
                                <div class="tip-dot">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="opacity:0">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                Mengandung simbol (!@#$...)
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="submit-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0
                                       01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622
                                       5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Ganti Password
                        </button>

                    </form>
                </div>
            </div>

            {{-- ── DATA KEPALA SEKOLAH ─────────────────────────────── --}}
            <div class="flex flex-col gap-4">

                {{-- Data Kepala Sekolah Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-2">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Data Kepala Sekolah</span>
                    </div>

                    <div class="p-4 space-y-2.5">

                        {{-- Avatar + nama --}}
                        <div
                            class="flex items-center gap-3 p-3 bg-linear-to-r from-blue-50 to-indigo-50
                        border border-blue-100 rounded-xl mb-3">
                            <div class="avatar-circle">
                                @if (!empty($user->guru->foto))
                                    <img src="{{ asset('storage/' . $user->guru->foto) }}" alt="Foto"
                                        class="w-full h-full object-cover rounded-full" />
                                @elseif (!empty($user->profile_photo))
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto"
                                        class="w-full h-full object-cover rounded-full" />
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-[14px] font-extrabold text-gray-900">{{ $user->name ?? '-' }}</p>
                                <p class="text-[11.5px] text-indigo-600 font-semibold">Kepala Sekolah</p>
                            </div>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">{{ !empty($user->nip) ? 'NIP' : 'NIK' }}</span>
                            <span class="data-row-val">{{ !empty($user->nip) ? ($user->nip ?? '-') : ($user->nik ?? '-') }}</span>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Nama</span>
                            <span class="data-row-val">{{ $user->name ?? '-' }}</span>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Email</span>
                            <span class="data-row-val">{{ $user->email ?? '-' }}</span>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Status Pegawai</span>
                            <span class="data-row-val">{{ $user->guru->status_pegawai ?? '-' }}</span>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Unit Kerja</span>
                            <span class="data-row-val">{{ $user->guru->unit_kerja ?? '-' }}</span>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Tanggal Lahir</span>
                            <div class="flex items-center gap-2 flex-1">
                                <span
                                    class="data-row-val">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}</span>
                                <svg class="w-3.5 h-3.5 text-indigo-400 ml-auto" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="data-row">
                            <span class="data-row-label">Tempat Lahir</span>
                            <span class="data-row-val">{{ $user->tempat_lahir ?? '-' }}</span>
                        </div>

                    </div>
                </div>

                {{-- Tips Keamanan Card --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-3">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Tips Keamanan Akun</span>
                    </div>
                    <div class="p-4 space-y-2.5">
                        @foreach ([['Gunakan kombinasi huruf besar, kecil, angka, dan simbol', '🔡'], ['Jangan gunakan tanggal lahir atau nama sebagai password', '🚫'], ['Ganti password secara berkala setiap 3–6 bulan', '🔄'], ['Jangan bagikan password kepada siapapun', '🤫']] as $tip)
                            <div
                                class="flex items-start gap-2.5 p-2.5 rounded-xl bg-linear-to-r from-blue-50 to-indigo-50 border border-blue-100">
                                <span class="text-[16px] shrink-0 mt-0.5">{{ $tip[1] }}</span>
                                <p class="text-[12px] font-medium text-gray-700 leading-relaxed">{{ $tip[0] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            const eyeOff = btn.querySelector('.eye-off');
            const eyeOn = btn.querySelector('.eye-on');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOff.classList.add('hidden');
                eyeOn.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOff.classList.remove('hidden');
                eyeOn.classList.add('hidden');
            }
        }

        // Check password strength
        function checkStrength(pw) {
            const bar = document.getElementById('strengthBar');
            const label = document.getElementById('strengthLabel');
            const tipLen = document.getElementById('tip_len');
            const tipUpper = document.getElementById('tip_upper');
            const tipNum = document.getElementById('tip_num');
            const tipSym = document.getElementById('tip_sym');

            let score = 0;

            // Check length
            if (pw.length >= 8) {
                score++;
                tipLen.classList.add('ok');
            } else {
                tipLen.classList.remove('ok');
            }

            // Check uppercase
            if (/[A-Z]/.test(pw)) {
                score++;
                tipUpper.classList.add('ok');
            } else {
                tipUpper.classList.remove('ok');
            }

            // Check number
            if (/[0-9]/.test(pw)) {
                score++;
                tipNum.classList.add('ok');
            } else {
                tipNum.classList.remove('ok');
            }

            // Check symbol
            if (/[!@#$%^&*(),.?":{}|<>]/.test(pw)) {
                score++;
                tipSym.classList.add('ok');
            } else {
                tipSym.classList.remove('ok');
            }

            // Update bar
            const colors = ['#ef4444', '#f59e0b', '#f59e0b', '#22c55e'];
            const labels = ['Lemah', 'Sedang', 'Sedang', 'Kuat'];
            const widths = ['25%', '50%', '75%', '100%'];

            if (pw.length === 0) {
                bar.style.width = '0%';
                label.textContent = '';
            } else {
                bar.style.width = widths[score];
                bar.style.background = colors[score];
                label.textContent = labels[score];
                label.style.color = colors[score];
            }
        }

        // Check password match
        function checkMatch() {
            const pwBaru = document.getElementById('pw_baru').value;
            const pwKonfirm = document.getElementById('pw_konfirm').value;
            const label = document.getElementById('matchLabel');

            if (pwKonfirm.length === 0) {
                label.textContent = '';
            } else if (pwBaru === pwKonfirm) {
                label.textContent = '✓ Password cocok';
                label.style.color = '#22c55e';
            } else {
                label.textContent = '✗ Password tidak cocok';
                label.style.color = '#ef4444';
            }
        }
    </script>
@endsection
