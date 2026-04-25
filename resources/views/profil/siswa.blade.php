@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Profil Siswa')

@section('content')

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ── Inline edit fields ──────────────────────────────── */
        .inline-edit {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            color: #1e293b;
            font-size: 0.8125rem;
            font-weight: 600;
            padding: 2px 4px;
            border-radius: 4px;
            transition: background 0.15s;
        }

        .inline-edit:hover {
            background: #eff6ff;
        }

        .inline-edit:focus {
            background: #eff6ff;
            color: #1d4ed8;
        }

        /* ── Maps ───────────────────────────────────────────── */
        #mapPreviewMap {
            width: 100%;
            height: 144px;
            pointer-events: none;
        }

        #mapFull {
            width: 100%;
            height: 380px;
        }

        #mapFull .leaflet-container,
        #mapPreviewMap .leaflet-container {
            z-index: 1 !important;
        }

        /* ── Hero banner inside profile card ────────────────── */
        .profile-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .profile-hero .deco-1 {
            position: absolute;
            top: -40px;
            right: -20px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            pointer-events: none;
        }

        .profile-hero .deco-2 {
            position: absolute;
            bottom: -30px;
            right: 80px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        /* ── Avatar ring ─────────────────────────────────────── */
        .avatar-ring {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            padding: 3px;
            border-radius: 50%;
            display: inline-flex;
        }

        .avatar-ring-inner {
            background: white;
            border-radius: 50%;
            padding: 2px;
        }

        /* ── Section header accent ───────────────────────────── */
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

        /* ── Field row (inside card body) ───────────────────── */
        .field-row {
            display: flex;
            align-items: center;
            padding: 8px 10px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
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

        .field-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            width: 108px;
            flex-shrink: 0;
        }

        .field-sep {
            font-size: 12px;
            color: #cbd5e1;
            margin: 0 8px;
        }

        /* ── Input inside field-row ──────────────────────────── */
        .field-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 12.5px;
            font-weight: 600;
            color: #1e293b;
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

        /* ── Fancy input (standalone) ────────────────────────── */
        .fancy-input {
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

        /* ── Teman terbaik badge ──────────────────────────────── */
        .teman-card {
            background: linear-gradient(135deg, #f0f9ff, #eef2ff);
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            padding: 12px 14px;
            position: relative;
        }

        /* ── Save button ──────────────────────────────────────── */
        .save-btn {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(37, 99, 235, .30);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, .38);
        }

        .save-btn:active {
            transform: translateY(0);
        }

        /* ── Add-friend button ───────────────────────────────── */
        .add-btn {
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s;
        }

        .add-btn:hover {
            opacity: .88;
        }

        /* ── Map preview wrapper ─────────────────────────────── */
        #mapPreviewWrapper {
            border-radius: 12px;
            border: 1.5px solid #bfdbfe;
            overflow: hidden;
            cursor: pointer;
            height: 144px;
            position: relative;
            transition: border-color .2s, box-shadow .2s;
        }

        #mapPreviewWrapper:hover {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        /* ── Tag chips ───────────────────────────────────────── */
        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: white;
            border: 1.5px solid #bfdbfe;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #374151;
        }

        /* ── Progress ring (small) ───────────────────────────── */
        .profile-progress-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
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

        .fu-4 {
            animation: fadeUp .36s .24s ease both;
        }

        /* ── Pulse on photo hover ────────────────────────────── */
        #photoCircle {
            transition: box-shadow .25s;
        }

        #photoCircle:hover {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, .25);
        }
    </style>

    {{-- ═══════════════════════════════════════════
     PAGE TITLE
    ════════════════════════════════════════════ --}}
    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ── HERO / IDENTITY CARD ──────────────────────────── --}}
        <div class="profile-hero rounded-2xl px-6 py-6 fu-0">
            <span class="deco-1"></span>
            <span class="deco-2"></span>

            <div class="relative z-10 flex items-center gap-5 flex-wrap">

                {{-- Avatar --}}
                <div class="avatar-ring shrink-0">
                    <div class="avatar-ring-inner">
                        <div id="photoCircle"
                            class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center
                                   bg-gradient-to-br from-blue-400 to-indigo-500 cursor-pointer"
                            onclick="document.getElementById('photoInput').click()" title="Klik untuk ubah foto">
                            @if (!empty($user->foto))
                                <img id="profilePreview" src="{{ asset('storage/' . $user->foto) }}"
                                    alt="Foto {{ $user->name }}" class="w-full h-full object-cover" />
                            @else
                                <svg id="placeholderSvg" class="w-10 h-10 text-white/80" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <img id="profilePreview" class="hidden w-full h-full object-cover" />
                            @endif
                        </div>
                    </div>
                </div>
                <input type="file" id="photoInput" name="foto" accept="image/*" class="hidden" />

                {{-- Name + meta --}}
                <div class="flex-1 min-w-0">
                    <h1 class="text-white font-extrabold text-xl leading-tight mb-1">
                        {{ $user->name ?? 'Nama Siswa' }}
                    </h1>
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="info-chip">
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $user->kelas?->nama_kelas ?? '-' }}
                        </span>
                        <span class="info-chip">
                            <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                            </svg>
                            NISN: {{ $user->nisn ?? '-' }}
                        </span>
                        @if ($user->isProfileComplete())
                            <span class="info-chip" style="border-color:#bbf7d0; color:#166534;">
                                <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Profil Lengkap
                            </span>
                        @else
                            <span class="info-chip" style="border-color:#fed7aa; color:#9a3412;">
                                <svg class="w-3 h-3 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Change photo button --}}
                <button type="button" onclick="document.getElementById('photoInput').click()"
                    class="shrink-0 flex items-center gap-2 bg-white/15 hover:bg-white/25
                           border border-white/30 text-white text-[12px] font-bold
                           px-4 py-2 rounded-xl transition-all duration-200 hover:-translate-y-0.5">
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

        {{-- ── DATA DIRI CARD ───────────────────────────────── --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">
            <div class="section-header">
                <div class="section-icon">
                    <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="text-[13px] font-bold text-gray-900">Data Diri</span>
            </div>

            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2.5">

                <div class="field-row">
                    <span class="field-label">Nama Lengkap</span>
                    <span class="field-sep">:</span>
                    <input type="text" id="p_nama" value="{{ $user->name ?? '' }}" class="field-input"
                        placeholder="Nama lengkap" />
                </div>

                <div class="field-row">
                    <span class="field-label">Tempat Lahir</span>
                    <span class="field-sep">:</span>
                    <input type="text" id="p_tempat_lahir" value="{{ $user->tempat_lahir ?? '' }}"
                        class="field-input" placeholder="Tempat lahir" />
                </div>

                <div class="field-row">
                    <span class="field-label">Tanggal Lahir</span>
                    <span class="field-sep">:</span>
                    <input type="date" id="p_tanggal_lahir" value="{{ $user->birth_date?->format('Y-m-d') ?? '' }}"
                        class="field-input" />
                </div>

                <div class="field-row">
                    <span class="field-label">Jenis Kelamin</span>
                    <span class="field-sep">:</span>
                    <select id="p_jk" class="field-input cursor-pointer">
                        <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>

                <div class="field-row" style="background:#f8fafc; opacity:.8;">
                    <span class="field-label">Kelas</span>
                    <span class="field-sep">:</span>
                    <input type="text" id="p_kelas" value="{{ $user->kelas?->nama_kelas ?? '-' }}"
                        class="field-input" readonly />
                </div>

                <div class="field-row" style="background:#f8fafc; opacity:.8;">
                    <span class="field-label">NISN</span>
                    <span class="field-sep">:</span>
                    <input type="text" id="p_nisn" value="{{ $user->nisn ?? '' }}" class="field-input"
                        readonly />
                </div>

            </div>
        </div>

        {{-- ── GRID: HOBI + KONTAK ─────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 fu-2">

            {{-- HOBI DAN MINAT ─────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="section-header">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Hobi & Minat</span>
                </div>

                <div class="p-4 space-y-3">
                    @foreach ([['id' => 'f_hobi', 'label' => 'Hobi', 'placeholder' => 'Hobi kamu...'], ['id' => 'f_cita', 'label' => 'Cita-cita', 'placeholder' => 'Cita-cita kamu...'], ['id' => 'f_makan', 'label' => 'Makanan kesukaan', 'placeholder' => 'Makanan favorit...'], ['id' => 'f_warna', 'label' => 'Warna kesukaan', 'placeholder' => 'Warna favorit...']] as $field)
                        <div>
                            <label
                                class="block text-[11px] font-semibold text-gray-400 mb-1">{{ $field['label'] }}</label>
                            @php
                                $fieldKey = match ($field['id']) {
                                    'f_hobi' => $user->hobi ?? '',
                                    'f_cita' => $user->cita_cita ?? '',
                                    'f_makan' => $user->makanan_kesukaan ?? '',
                                    'f_warna' => $user->warna_kesukaan ?? '',
                                    default => '',
                                };
                            @endphp
                            <input type="text" id="{{ $field['id'] }}" value="{{ $fieldKey }}"
                                placeholder="{{ $field['placeholder'] }}" class="fancy-input" />
                        </div>
                    @endforeach

                    {{-- TEMAN TERBAIK ──────────────────────── --}}
                    <div class="pt-3 border-t border-indigo-100 mt-1">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-[12px] font-bold text-gray-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Teman Terbaik
                            </span>
                            <button type="button" onclick="addTemanTerbaik()" class="add-btn">+ Tambah</button>
                        </div>
                        <div id="temanTerbaikContainer" class="space-y-3">
                            @php
                                $temanTerbaikJson = $user->teman_terbaik_json ?? [];
                                if (empty($temanTerbaikJson) && !empty($user->teman_terbaik)) {
                                    $temanTerbaikJson = [['nama' => $user->teman_terbaik, 'nomor' => '']];
                                }
                            @endphp
                            @if (empty($temanTerbaikJson))
                                <div class="teman-terbaik-item teman-card">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label
                                                class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nama</label>
                                            <input type="text" name="teman_nama[]" value=""
                                                placeholder="Nama teman..." class="fancy-input"
                                                style="font-size:12px; padding:7px 10px;" />
                                        </div>
                                        <div>
                                            <label class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nomor
                                                HP</label>
                                            <input type="tel" name="teman_nomor[]" value=""
                                                placeholder="0812..." class="fancy-input"
                                                style="font-size:12px; padding:7px 10px;" />
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($temanTerbaikJson as $teman)
                                    <div class="teman-terbaik-item teman-card">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label
                                                    class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nama</label>
                                                <input type="text" name="teman_nama[]"
                                                    value="{{ $teman['nama'] ?? '' }}" placeholder="Nama teman..."
                                                    class="fancy-input" style="font-size:12px; padding:7px 10px;" />
                                            </div>
                                            <div>
                                                <label class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nomor
                                                    HP</label>
                                                <input type="tel" name="teman_nomor[]"
                                                    value="{{ $teman['nomor'] ?? '' }}" placeholder="0812..."
                                                    class="fancy-input" style="font-size:12px; padding:7px 10px;" />
                                            </div>
                                        </div>
                                        @if (count($temanTerbaikJson) > 1)
                                            <button type="button" onclick="removeTemanTerbaik(this)"
                                                class="mt-2 text-[11px] text-red-400 hover:text-red-600 font-semibold">
                                                − Hapus
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTAK & KELUARGA ──────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="section-header">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <span class="text-[13px] font-bold text-gray-900">Kontak & Keluarga</span>
                </div>

                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 mb-1">Nomor siswa</label>
                        <input type="tel" id="f_hp" value="{{ $user->no_telepon ?? '' }}"
                            placeholder="Nomor HP siswa..." class="fancy-input" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 mb-1">Nomor orang tua</label>
                        <input type="tel" id="f_ortu" value="{{ $user->no_ortu ?? '' }}"
                            placeholder="Nomor HP orang tua..." class="fancy-input" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 mb-1">Email</label>
                        <input type="email" id="f_email" value="{{ $user->email ?? '' }}"
                            placeholder="Email aktif..." class="fancy-input" />
                        <p class="text-[11px] text-amber-600 mt-1.5 font-semibold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            Gunakan email aktif siswa
                        </p>
                    </div>

                    {{-- MAP PREVIEW ────────────────────────── --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-400 mb-1.5">Lokasi Rumah</label>
                        <div id="mapPreviewWrapper" onclick="openMapModal()">
                            <div id="mapPreviewMap"></div>
                            <div
                                class="absolute inset-x-0 bottom-0 bg-black/50 hover:bg-black/65
                                transition-colors px-3 py-2 flex items-center gap-2 z-10">
                                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-white text-xs font-semibold">Klik untuk ganti lokasi</span>
                            </div>
                        </div>

                        <div id="addressDisplay"
                            class="mt-2 text-[11.5px] text-gray-600 bg-blue-50 border border-blue-100
                                rounded-xl px-3 py-2 min-h-[30px] leading-relaxed">
                            {{ $user->alamat ?? 'Belum ada lokasi dipilih' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SAVE BUTTON ─────────────────────────────────── --}}
        <div class="flex justify-end fu-3">
            <button onclick="saveProfile()" class="save-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Profil
            </button>
        </div>

    </div>{{-- end max-width --}}


    {{-- ═══════════════════════════════════════════
     MAP MODAL
    ════════════════════════════════════════════ --}}
    <div id="mapModal" class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-5 py-4"
                style="background:linear-gradient(90deg,#eff6ff,#eef2ff); border-bottom:1px solid #e0e7ff;">
                <div class="flex items-center gap-2.5">
                    <div class="section-icon">
                        <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[14px] font-bold text-gray-900">Pilih Lokasi Rumah</h3>
                </div>
                <button onclick="closeMapModal()"
                    class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

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
                        class="bg-red-50 hover:bg-red-100 text-red-600 text-[12.5px] font-bold
                               px-4 py-2.5 rounded-xl border border-red-200 transition-colors
                               {{ empty($user->latitude) ? 'hidden' : '' }}">
                        Hapus Lokasi
                    </button>
                    <button onclick="confirmLocation()" class="save-btn"
                        style="padding:9px 20px; font-size:12.5px; border-radius:10px;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
     TOAST NOTIFICATION
    ════════════════════════════════════════════ --}}
    <div id="toast"
        class="fixed top-5 right-5 z-[99999] flex items-center gap-2.5
               text-white text-[13px] font-bold px-4 py-3 rounded-2xl shadow-2xl
               opacity-0 -translate-y-3 pointer-events-none transition-all duration-300"
        style="background:linear-gradient(135deg,#2563eb,#4f46e5); min-width:220px;">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span>Profil berhasil disimpan!</span>
    </div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* ── State ────────────────────────────────────────────── */
        let selLat = {{ $user->latitude ?? 5.5536 }};
        let selLng = {{ $user->longitude ?? 95.3177 }};
        let pendingLat = selLat,
            pendingLng = selLng;
        let mapPreview = null,
            markerPreview = null;
        let mapFull = null,
            markerFull = null;
        let geocodeTimer = null;

        /* ── Preview map ──────────────────────────────────────── */
        function initPreviewMap() {
            const el = document.getElementById('mapPreviewMap');
            if (!el || typeof L === 'undefined') return;
            mapPreview = L.map(el, {
                zoomControl: false,
                attributionControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                touchZoom: false,
                keyboard: false,
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapPreview);
            markerPreview = L.marker([selLat, selLng]).addTo(mapPreview);
            mapPreview.setView([selLat, selLng], 15);
        }

        function updatePreviewMap(lat, lng) {
            if (!mapPreview) return;
            markerPreview.setLatLng([lat, lng]);
            mapPreview.setView([lat, lng], 15);
        }

        /* ── Modal map ────────────────────────────────────────── */
        function openMapModal() {
            const modal = document.getElementById('mapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            pendingLat = selLat;
            pendingLng = selLng;
            document.getElementById('coordText').textContent =
                'Koordinat: ' + selLat.toFixed(6) + ', ' + selLng.toFixed(6);
            document.getElementById('coordAddr').textContent = '';
            setTimeout(() => {
                if (!mapFull) {
                    const el = document.getElementById('mapFull');
                    mapFull = L.map(el).setView([selLat, selLng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(mapFull);
                    markerFull = L.marker([selLat, selLng], {
                        draggable: true
                    }).addTo(mapFull);
                    markerFull.on('dragend', e => {
                        pendingLat = e.target.getLatLng().lat;
                        pendingLng = e.target.getLatLng().lng;
                        updateCoordDisplay(pendingLat, pendingLng);
                        reverseGeocode(pendingLat, pendingLng);
                    });
                    mapFull.on('click', e => {
                        pendingLat = e.latlng.lat;
                        pendingLng = e.latlng.lng;
                        markerFull.setLatLng([pendingLat, pendingLng]);
                        updateCoordDisplay(pendingLat, pendingLng);
                        reverseGeocode(pendingLat, pendingLng);
                    });
                } else {
                    mapFull.setView([selLat, selLng], 15);
                    markerFull.setLatLng([selLat, selLng]);
                    mapFull.invalidateSize();
                }
            }, 150);
        }

        function closeMapModal() {
            const modal = document.getElementById('mapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeMapModal();
        });

        function updateCoordDisplay(lat, lng) {
            document.getElementById('coordText').textContent =
                'Koordinat: ' + lat.toFixed(6) + ', ' + lng.toFixed(6);
        }

        function reverseGeocode(lat, lng) {
            document.getElementById('coordAddr').textContent = 'Mencari alamat...';
            clearTimeout(geocodeTimer);
            geocodeTimer = setTimeout(() => {
                fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng +
                        '&format=json&accept-language=id')
                    .then(r => r.json())
                    .then(data => {
                        const addr = data.display_name || (lat.toFixed(5) + ', ' + lng.toFixed(5));
                        const el = document.getElementById('coordAddr');
                        el.textContent = addr;
                        el.dataset.fullAddr = addr;
                    })
                    .catch(() => {
                        document.getElementById('coordAddr').textContent = lat.toFixed(5) + ', ' + lng.toFixed(
                            5);
                    });
            }, 600);
        }

        function confirmLocation() {
            selLat = pendingLat;
            selLng = pendingLng;
            updatePreviewMap(selLat, selLng);
            const addrEl = document.getElementById('coordAddr');
            const fullAddr = addrEl.dataset.fullAddr || addrEl.textContent;
            if (fullAddr && fullAddr !== 'Mencari alamat...') {
                document.getElementById('addressDisplay').textContent = fullAddr;
            }
            closeMapModal();
        }

        function clearLocation() {
            if (!confirm('Apakah Anda yakin ingin menghapus lokasi?')) return;
            fetch('{{ route('student.profile.delete-location') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        selLat = 5.5489;
                        selLng = 95.3238;
                        document.getElementById('addressDisplay').textContent = 'Belum ada lokasi dipilih';
                        if (markerFull) mapFull.removeLayer(markerFull);
                        if (markerPreview) mapPreview.removeLayer(markerPreview);
                        markerFull = null;
                        markerPreview = null;
                        closeMapModal();
                        showToast(result.message, true);
                    } else {
                        showToast(result.message || 'Gagal menghapus', false);
                    }
                })
                .catch(() => showToast('Kesalahan koneksi ke server!', false));
        }

        /* ── Photo upload ─────────────────────────────────────── */
        document.getElementById('photoInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar! Maksimal 5MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('photoCircle').innerHTML =
                    '<img src="' + e.target.result + '" alt="Foto" class="w-full h-full object-cover"/>';
            };
            reader.readAsDataURL(file);
        });

        /* ── Teman terbaik ────────────────────────────────────── */
        function addTemanTerbaik() {
            const container = document.getElementById('temanTerbaikContainer');
            const div = document.createElement('div');
            div.className = 'teman-terbaik-item teman-card';
            div.innerHTML = `
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nama</label>
                        <input type="text" name="teman_nama[]" placeholder="Nama teman..."
                            class="fancy-input" style="font-size:12px;padding:7px 10px;" />
                    </div>
                    <div>
                        <label class="block text-[10.5px] font-semibold text-blue-500 mb-1">Nomor HP</label>
                        <input type="tel" name="teman_nomor[]" placeholder="0812..."
                            class="fancy-input" style="font-size:12px;padding:7px 10px;" />
                    </div>
                </div>
                <button type="button" onclick="removeTemanTerbaik(this)"
                    class="mt-2 text-[11px] text-red-400 hover:text-red-600 font-semibold">
                    − Hapus
                </button>`;
            container.appendChild(div);
        }

        function removeTemanTerbaik(btn) {
            const container = document.getElementById('temanTerbaikContainer');
            if (container.children.length > 1) btn.parentElement.remove();
            else alert('Minimal harus ada 1 teman terbaik');
        }

        /* ── Toast ────────────────────────────────────────────── */
        function showToast(msg, success = true) {
            const toast = document.getElementById('toast');
            toast.style.background = success ?
                'linear-gradient(135deg,#2563eb,#4f46e5)' :
                'linear-gradient(135deg,#dc2626,#b91c1c)';
            toast.querySelector('span').textContent = msg;
            toast.classList.remove('opacity-0', '-translate-y-3', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-3', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 3000);
        }

        /* ── Save ─────────────────────────────────────────────── */
        function saveProfile() {
            const alamat = document.getElementById('addressDisplay').innerText.trim();
            if (!alamat || alamat === 'Belum ada lokasi dipilih') {
                showToast('Silakan pilih lokasi di peta!', false);
                return;
            }

            const formData = new FormData();
            formData.append('nama', document.getElementById('p_nama').value);
            formData.append('kelas', document.getElementById('p_kelas').value);
            formData.append('nisn', document.getElementById('p_nisn').value);
            formData.append('tempat_lahir', document.getElementById('p_tempat_lahir').value);
            formData.append('tanggal_lahir', document.getElementById('p_tanggal_lahir').value);
            formData.append('jk', document.getElementById('p_jk').value);
            formData.append('hobi', document.getElementById('f_hobi').value);
            formData.append('cita', document.getElementById('f_cita').value);
            formData.append('makan', document.getElementById('f_makan').value);
            formData.append('warna', document.getElementById('f_warna').value);
            formData.append('hp', document.getElementById('f_hp').value);
            formData.append('ortu', document.getElementById('f_ortu').value);
            formData.append('email', document.getElementById('f_email').value);
            formData.append('alamat', alamat);
            formData.append('latitude', selLat);
            formData.append('longitude', selLng);

            // Teman terbaik
            const namaInputs = document.querySelectorAll('input[name="teman_nama[]"]');
            const nomorInputs = document.querySelectorAll('input[name="teman_nomor[]"]');
            const temanJson = [];
            namaInputs.forEach((el, i) => {
                const nama = el.value.trim();
                const nomor = nomorInputs[i]?.value.trim() || '';
                if (nama || nomor) temanJson.push({
                    nama,
                    nomor
                });
            });
            formData.append('teman_terbaik_json', JSON.stringify(temanJson));

            const photoInput = document.getElementById('photoInput');
            if (photoInput.files[0]) formData.append('foto', photoInput.files[0]);

            fetch('{{ route('student.profile.save') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData,
                })
                .then(r => r.json())
                .then(result => showToast(result.success ? 'Profil berhasil disimpan!' : (result.message ||
                    'Terjadi kesalahan'), result.success))
                .catch(() => showToast('Terjadi kesalahan saat menyimpan.', false));
        }

        /* ── Init ──────────────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', () => setTimeout(initPreviewMap, 300));
    </script>

@endsection
