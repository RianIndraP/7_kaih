@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Profil Guru')

@section('content')

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
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

        .avatar-ring {
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            padding: 3px;
            border-radius: 50%;
            display: inline-flex;
        }

        .avatar-ring-inner {
            background: white;
            border-radius: 50%;
            padding: 2px;
        }

        .avatar-circle {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: box-shadow .25s;
        }

        .avatar-circle:hover {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .25);
        }

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

        .field-row {
            display: flex;
            align-items: center;
            padding: 8px 10px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            transition: border-color .2s, background .2s;
        }

        .field-row:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .field-row:focus-within {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .field-label-sm {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            width: 112px;
            flex-shrink: 0;
        }

        .field-sep {
            font-size: 12px;
            color: #cbd5e1;
            margin: 0 8px;
        }

        .field-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 12.5px;
            font-weight: 600;
            color: #1e293b;
            font-family: inherit;
        }

        .field-input::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }

        .field-input:focus {
            color: #1d4ed8;
        }

        .field-input[readonly] {
            color: #64748b;
            cursor: not-allowed;
        }

        .fancy-input,
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
            font-family: inherit;
        }

        .fancy-input:hover,
        .fancy-select:hover {
            border-color: #bfdbfe;
            background: #fff;
        }

        .fancy-input:focus,
        .fancy-select:focus {
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

        .fancy-select {
            cursor: pointer;
        }

        .field-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 5px;
            display: block;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .save-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
        }

        .save-btn:active {
            transform: translateY(0);
        }

        .save-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        #mapPreviewMap {
            width: 100%;
            height: 140px;
            pointer-events: none;
        }

        #mapFull {
            width: 100%;
            height: 380px;
        }

        #mapPreviewWrapper {
            border-radius: 12px;
            border: 1.5px solid #bfdbfe;
            overflow: hidden;
            cursor: pointer;
            height: 140px;
            position: relative;
            transition: border-color .2s, box-shadow .2s;
        }

        #mapPreviewWrapper:hover {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .52);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-backdrop.flex {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 22px;
            width: 100%;
            max-width: 560px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .22);
            animation: modal-pop .22s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        @keyframes modal-pop {
            from {
                opacity: 0;
                transform: scale(.92) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            padding: 18px 20px 14px;
            position: relative;
            overflow: hidden;
        }

        .modal-header .deco-m {
            position: absolute;
            top: -30px;
            right: -10px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        #toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 16px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
            opacity: 0;
            transform: translateY(-12px);
            pointer-events: none;
            transition: all .3s;
            min-width: 200px;
        }

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

        .confirm-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .28);
            transition: transform .2s, box-shadow .2s;
            font-family: inherit;
        }

        .confirm-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, .36);
        }

        .del-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff1f2;
            color: #dc2626;
            border: 1.5px solid #fca5a5;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s;
            font-family: inherit;
        }

        .del-btn:hover {
            background: #ffe4e6;
        }

        .photo-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .15);
            border: 1.5px solid rgba(255, 255, 255, .28);
            color: white;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s;
            font-family: inherit;
        }

        .photo-btn:hover {
            background: rgba(255, 255, 255, .25);
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin-anim {
            animation: spin 1s linear infinite;
        }
    </style>

    @php $user = auth()->user(); @endphp

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- HERO --}}
        <div class="guru-hero rounded-2xl px-5 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>
            <div class="relative z-10 flex flex-wrap items-center gap-4 justify-between">
                <div class="flex items-center gap-4">
                    <div class="avatar-ring shrink-0">
                        <div class="avatar-ring-inner">
                            <div class="avatar-circle" onclick="document.getElementById('photoInput').click()"
                                title="Klik untuk ubah foto">
                                @if (!empty($user->foto))
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto"
                                        class="w-full h-full object-cover" />
                                @else
                                    <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="file" id="photoInput" accept="image/*" class="hidden" />
                    <div>
                        <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1.5">{{ $user->name }}</h1>
                        <div class="flex flex-wrap gap-2">
                            @if ($user->nip)
                                <span class="hero-chip">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1" />
                                    </svg>
                                    NIP: {{ $user->nip }}
                                </span>
                            @endif
                            @if ($guru?->kelas_wali)
                                <span class="hero-chip">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                    </svg>
                                    Wali Kelas {{ $guru->kelas_wali }}
                                </span>
                            @endif
                            @if ($guru?->status_pegawai)
                                <span class="hero-chip">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $guru->status_pegawai }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('photoInput').click()" class="photo-btn shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ubah Foto
                </button>
            </div>
        </div>

        {{-- DATA DIRI --}}
        @php
            $modeAktif = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru') == '1';
            $deadline = \App\Models\PengaturanSistem::getValue('mode_isi_data_guru_deadline');
            $modeValid = $modeAktif && (!$deadline || now()->lte(\Carbon\Carbon::parse($deadline)->endOfDay()));
            $fieldsIzin = json_decode(\App\Models\PengaturanSistem::getValue('mode_isi_data_guru_fields', '[]'), true) ?? [];
            $boleh = fn(string $f) => $modeValid && in_array($f, $fieldsIzin);
        @endphp

    {{-- Banner mode aktif --}}
    @if($modeValid)
        <div class="mb-3 bg-purple-50 border border-purple-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <svg class="w-4 h-4 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-purple-800">Mode pengisian data sedang aktif</p>
                <p class="text-xs text-purple-600 mt-0.5">
                    Kamu dapat memperbaiki data yang biasanya terkunci.
                    @if($deadline)
                        Batas waktu: <strong>{{ \Carbon\Carbon::parse($deadline)->translatedFormat('d F Y') }}</strong>.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">
        <div class="section-header">
            <div class="section-icon">
                <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span class="text-[13px] font-bold text-gray-900">Data Diri</span>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2.5">

            {{-- Nama Lengkap --}}
            <div class="field-row" style="{{ $boleh('nama_lengkap') ? '' : 'opacity:.75;' }}">
                <span class="field-label-sm">
                    Nama Lengkap
                    @if(!$boleh('nama_lengkap'))
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </span>
                <span class="field-sep">:</span>
                <input type="text" id="p_nama" value="{{ $user->name }}" class="field-input"
                    placeholder="Nama lengkap" {{ $boleh('nama_lengkap') ? '' : 'readonly' }}/>
            </div>

            {{-- Tempat Lahir --}}
            <div class="field-row" style="{{ $boleh('tempat_lahir') ? '' : 'opacity:.75;' }}">
                <span class="field-label-sm">
                    Tempat Lahir
                    @if(!$boleh('tempat_lahir'))
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </span>
                <span class="field-sep">:</span>
                <input type="text" id="p_tempat_lahir" value="{{ $user->tempat_lahir ?? '' }}" class="field-input"
                    placeholder="Tempat lahir" {{ $boleh('tempat_lahir') ? '' : 'readonly' }}/>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="field-row" style="{{ $boleh('tanggal_lahir') ? '' : 'opacity:.75;' }}">
                <span class="field-label-sm">
                    Tanggal Lahir
                    @if(!$boleh('tanggal_lahir'))
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </span>
                <span class="field-sep">:</span>
                <input type="date" id="p_tanggal_lahir"
                    value="{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '' }}"
                    class="field-input" {{ $boleh('tanggal_lahir') ? '' : 'readonly' }}/>
            </div>

            {{-- NIP --}}
            <div class="field-row" style="{{ $boleh('nip') ? '' : 'opacity:.75;' }}">
                <span class="field-label-sm">
                    NIP
                    @if(!$boleh('nip'))
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </span>
                <span class="field-sep">:</span>
                <input type="text" id="p_nip" value="{{ $user->nip ?? '' }}" class="field-input"
                    {{ $boleh('nip') ? '' : 'readonly' }}/>
            </div>

            {{-- NIK --}}
            <div class="field-row" style="{{ $boleh('nik') ? '' : 'opacity:.75;' }}">
                <span class="field-label-sm">
                    NIK
                    @if(!$boleh('nik'))
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    @endif
                </span>
                <span class="field-sep">:</span>
                <input type="text" id="p_nik" value="{{ $user->nik ?? '' }}" class="field-input"
                    {{ $boleh('nik') ? '' : 'readonly' }}/>
            </div>

            {{-- Status Pegawai --}}
            @if($boleh('status_pegawai'))
                <div class="field-row">
                    <span class="field-label-sm">Status Pegawai</span>
                    <span class="field-sep">:</span>
                    <select id="p_status_pegawai" class="field-input">
                        @foreach(['PNS', 'PPPK', 'Honorer'] as $opt)
                            <option value="{{ $opt }}" {{ ($guru->status_pegawai ?? '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="field-row" style="opacity:.75;">
                    <span class="field-label-sm">
                        Status Pegawai
                        <svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <span class="field-sep">:</span>
                    <input type="text" value="{{ $guru->status_pegawai ?? '-' }}" class="field-input" readonly/>
                    <input type="hidden" id="p_status_pegawai" value="{{ $guru->status_pegawai ?? '' }}">
                </div>
            @endif

        </div>
    </div>

            {{-- GRID KEPEGAWAIAN + KONTAK --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 fu-2">

                {{-- KEPEGAWAIAN --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon" style="background:linear-gradient(135deg,#10b981,#059669)">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Data Kepegawaian</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="field-label">Status Pegawai</label>
                            <input type="text" id="f_status_guru" value="{{ $guru->status_pegawai ?? '' }}" class="fancy-input"
                                readonly placeholder="Status pegawai" />
                        </div>
                        <div>
                            <label class="field-label">Jenis Kelamin</label>
                            <select id="f_gender" class="fancy-select">
                                <option value="" disabled {{ empty($user->gender) ? 'selected' : '' }}>Pilih jenis kelamin
                                </option>
                                <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">Unit Kerja</label>
                            <input type="text" id="f_unit_kerja" value="{{ $guru->unit_kerja ?? '' }}" class="fancy-input"
                                placeholder="Unit kerja..." />
                        </div>
                        @if ($guru?->jabatan)
                            <div>
                                <label class="field-label">Jabatan</label>
                                <input type="text" value="{{ $guru->jabatan }}" class="fancy-input" readonly />
                            </div>
                        @endif
                    </div>
                </div>

                {{-- INFO KONTAK --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-900">Info Kontak</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="field-label">Nomor Telepon</label>
                            <input type="tel" id="f_hp" value="{{ $user->no_telepon ?? '' }}" class="fancy-input"
                                placeholder="Nomor telepon aktif..." />
                        </div>
                        <div>
                            <label class="field-label">Email</label>
                            <input type="email" id="f_email" value="{{ $user->email ?? '' }}" class="fancy-input"
                                placeholder="Email aktif..." />
                            <p class="text-[11px] text-amber-600 mt-1.5 font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                Gunakan email aktif Anda
                            </p>
                        </div>
                        <div>
                            <label class="field-label">Lokasi Rumah</label>
                            <div id="mapPreviewWrapper" onclick="openMapModal()">
                                <div id="mapPreviewMap"></div>
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-black/50 hover:bg-black/65 transition-colors px-3 py-2 flex items-center gap-2 z-10">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-white text-[11.5px] font-semibold">Klik untuk ganti lokasi</span>
                                </div>
                            </div>
                            <div id="addressDisplay"
                                class="mt-2 text-[11.5px] text-gray-600 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2 min-h-[30px] leading-relaxed">
                                {{ $user->alamat ?? 'Belum ada lokasi dipilih' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SAVE --}}
            <div class="fu-3">
                <button onclick="simpanProfil()" class="save-btn" id="saveBtn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Profil
                </button>
            </div>

        </div>

        {{-- MAP MODAL --}}
        <div id="mapModal" class="modal-backdrop" onclick="handleBackdrop(event)">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <span class="deco-m"></span>
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-[8px] flex items-center justify-center bg-white/20">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <h3 class="text-[14px] font-bold text-white">Pilih Lokasi Rumah</h3>
                        </div>
                        <button onclick="closeMapModal()"
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/15 hover:bg-white/28 text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="h-[3px]" style="background:linear-gradient(90deg,#3b82f6,#4f46e5,#7c3aed)"></div>
                <div class="p-4">
                    <div id="mapFull" class="rounded-xl border border-indigo-100 overflow-hidden"></div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 pb-5 pt-0">
                    <div class="flex-1 min-w-0">
                        <p id="coordText" class="text-[11.5px] text-gray-500">Klik pada peta untuk memilih lokasi</p>
                        <p id="coordAddr" class="text-[11.5px] text-gray-700 mt-1 leading-relaxed"></p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button id="btnHapusLokasi" onclick="clearLocation()"
                            class="del-btn {{ empty($user->latitude) ? 'hidden' : '' }}">Hapus Lokasi</button>
                        <button onclick="confirmLocation()" class="confirm-btn">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toast --}}
        <div id="toast" style="background:linear-gradient(135deg,#2563eb,#4f46e5);">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toastMsg">Profil berhasil disimpan!</span>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var selLat = {{ $user->latitude ?? 5.5536 }};
            var selLng = {{ $user->longitude ?? 95.3177 }};
            var pendingLat = selLat, pendingLng = selLng;
            var mapPreview = null, markerPreview = null, mapFull = null, markerFull = null, geocodeTimer = null;

            function initPreviewMap() {
                var el = document.getElementById('mapPreviewMap');
                if (!el || typeof L === 'undefined') return;
                mapPreview = L.map(el, { zoomControl: false, attributionControl: false, dragging: false, scrollWheelZoom: false, doubleClickZoom: false, touchZoom: false, keyboard: false });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapPreview);
                markerPreview = L.marker([selLat, selLng]).addTo(mapPreview);
                mapPreview.setView([selLat, selLng], 15);
            }

            function updatePreviewMap(lat, lng) { if (!mapPreview) return; markerPreview.setLatLng([lat, lng]); mapPreview.setView([lat, lng], 15); }

            function openMapModal() {
                document.getElementById('mapModal').classList.add('flex');
                pendingLat = selLat; pendingLng = selLng;
                document.getElementById('coordText').textContent = 'Koordinat: ' + selLat.toFixed(6) + ', ' + selLng.toFixed(6);
                document.getElementById('coordAddr').textContent = '';
                setTimeout(function () {
                    if (!mapFull) {
                        mapFull = L.map(document.getElementById('mapFull')).setView([selLat, selLng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(mapFull);
                        markerFull = L.marker([selLat, selLng], { draggable: true }).addTo(mapFull);
                        markerFull.on('dragend', function (e) { pendingLat = e.target.getLatLng().lat; pendingLng = e.target.getLatLng().lng; updateCoord(pendingLat, pendingLng); reverseGeocode(pendingLat, pendingLng); });
                        mapFull.on('click', function (e) { pendingLat = e.latlng.lat; pendingLng = e.latlng.lng; markerFull.setLatLng([pendingLat, pendingLng]); updateCoord(pendingLat, pendingLng); reverseGeocode(pendingLat, pendingLng); });
                    } else { mapFull.setView([selLat, selLng], 15); markerFull.setLatLng([selLat, selLng]); mapFull.invalidateSize(); }
                }, 150);
            }

            function closeMapModal() { document.getElementById('mapModal').classList.remove('flex'); }
            function handleBackdrop(e) { if (e.target === document.getElementById('mapModal')) closeMapModal(); }
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMapModal(); });
            function updateCoord(lat, lng) { document.getElementById('coordText').textContent = 'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6); }

            function reverseGeocode(lat, lng) {
                document.getElementById('coordAddr').textContent = 'Mencari alamat...';
                clearTimeout(geocodeTimer);
                geocodeTimer = setTimeout(function () {
                    fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json&accept-language=id')
                        .then(function (r) { return r.json(); })
                        .then(function (data) { var el = document.getElementById('coordAddr'); el.textContent = data.display_name || (lat.toFixed(5) + ', ' + lng.toFixed(5)); el.dataset.fullAddr = el.textContent; })
                        .catch(function () { document.getElementById('coordAddr').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5); });
                }, 600);
            }

            function confirmLocation() {
                selLat = pendingLat; selLng = pendingLng;
                updatePreviewMap(selLat, selLng);
                var addrEl = document.getElementById('coordAddr');
                var addr = addrEl.dataset.fullAddr || addrEl.textContent;
                if (addr && addr !== 'Mencari alamat...') document.getElementById('addressDisplay').textContent = addr;
                closeMapModal();
            }

            function clearLocation() {
                if (!confirm('Hapus lokasi dari profil?')) return;
                fetch('{{ route('guru.profil.delete-location') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (result) {
                        if (result.success) {
                            selLat = 5.5536; selLng = 95.3177;
                            document.getElementById('addressDisplay').textContent = 'Belum ada lokasi dipilih';
                            if (markerFull) mapFull.removeLayer(markerFull);
                            if (markerPreview) mapPreview.removeLayer(markerPreview);
                            markerFull = null; markerPreview = null;
                            closeMapModal(); tampilkanToast(result.message, 'green');
                        } else { tampilkanToast(result.message || 'Gagal', 'red'); }
                    })
                    .catch(function () { tampilkanToast('Kesalahan koneksi.', 'red'); });
            }

            document.getElementById('photoInput').addEventListener('change', function () {
                var file = this.files[0]; if (!file) return;
                if (file.size > 5 * 1024 * 1024) { alert('Ukuran foto maksimal 5MB.'); this.value = ''; return; }
                var reader = new FileReader();
                reader.onload = function (e) { document.querySelector('.avatar-circle').innerHTML = '<img src="' + e.target.result + '" alt="Foto" class="w-full h-full object-cover"/>'; };
                reader.readAsDataURL(file);
            });

            function simpanProfil() {
                var alamat = document.getElementById('addressDisplay').innerText.trim();
                if (alamat === 'Belum ada lokasi dipilih') alamat = '';
                var btn = document.getElementById('saveBtn');
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-4 h-4 spin-anim" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menyimpan...';
                var fd = new FormData();
                fd.append('nama', document.getElementById('p_nama').value);
                fd.append('tempat_lahir', document.getElementById('p_tempat_lahir').value);
                fd.append('tanggal_lahir', document.getElementById('p_tanggal_lahir').value);
                fd.append('nip', document.getElementById('p_nip').value);
                fd.append('nik', document.getElementById('p_nik').value);
                fd.append('status_pegawai', document.getElementById('p_status_pegawai').value);
                fd.append('status_guru', document.getElementById('f_status_guru').value);
                fd.append('gender', document.getElementById('f_gender').value);
                fd.append('unit_kerja', document.getElementById('f_unit_kerja').value);
                fd.append('hp', document.getElementById('f_hp').value);
                fd.append('email', document.getElementById('f_email').value);
                fd.append('alamat', alamat);
                fd.append('latitude', selLat);
                fd.append('longitude', selLng);
                var pi = document.getElementById('photoInput');
                if (pi.files[0]) fd.append('foto', pi.files[0]);
                fetch('{{ route('guru.profil.save') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: fd })
                    .then(function (r) { return r.json(); })
                    .then(function (res) { tampilkanToast(res.success ? 'Profil berhasil disimpan!' : (res.message || 'Terjadi kesalahan'), res.success ? 'green' : 'red'); })
                    .catch(function () { tampilkanToast('Gagal terhubung ke server.', 'red'); })
                    .finally(function () {
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Simpan Profil';
                    });
            }

            function tampilkanToast(pesan, warna) {
                var toast = document.getElementById('toast');
                toast.style.background = warna === 'red' ? 'linear-gradient(135deg,#dc2626,#b91c1c)' : 'linear-gradient(135deg,#2563eb,#4f46e5)';
                document.getElementById('toastMsg').textContent = pesan;
                toast.style.opacity = '1'; toast.style.transform = 'translateY(0)'; toast.style.pointerEvents = 'auto';
                setTimeout(function () { toast.style.opacity = '0'; toast.style.transform = 'translateY(-12px)'; toast.style.pointerEvents = 'none'; }, 3000);
            }

            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(initPreviewMap, 300);
                
                var statusPegawai = document.getElementById('p_status_pegawai');
    var statusGuru    = document.getElementById('f_status_guru');

    if (statusPegawai && statusGuru) {
        // Saat halaman load, sync nilai awal
        statusGuru.value = statusPegawai.value || statusPegawai.tagName === 'INPUT'
            ? statusPegawai.value
            : statusPegawai.options[statusPegawai.selectedIndex]?.value ?? '';

        // Kalau p_status_pegawai adalah select (mode aktif), sync ke f_status_guru saat berubah
        statusPegawai.addEventListener('change', function() {
            statusGuru.value = this.value;
        });
    }
            });
        </script>

@endsection