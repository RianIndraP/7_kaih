@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pesan Guru Wali')

@section('content')

    <style>
        /* ── Hero banner ─────────────────────────────────────── */
        .pesan-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .pesan-hero::before {
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

        /* ── Pesan item card ──────────────────────────────────── */
        .pesan-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1.5px solid #bfdbfe;
            border-radius: 16px;
            background: linear-gradient(135deg, #f0f9ff 0%, #eef2ff 100%);
            cursor: pointer;
            transition: all .22s;
            position: relative;
            overflow: hidden;
        }

        .pesan-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #2563eb, #4f46e5);
            border-radius: 4px 0 0 4px;
            transition: width .2s;
        }

        .pesan-card:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, .14);
        }

        .pesan-card:hover::before {
            width: 6px;
        }

        .pesan-card.sudah-dibaca {
            background: #f8fafc;
            border-color: #e2e8f0;
            opacity: .72;
        }

        .pesan-card.sudah-dibaca::before {
            background: #cbd5e1;
        }

        .pesan-card.sudah-dibaca:hover {
            opacity: .9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
        }

        /* ── Pesan icon wrap ─────────────────────────────────── */
        .pesan-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 4px 14px rgba(37, 99, 235, .28);
            transition: all .2s;
        }

        .pesan-card:hover .pesan-icon {
            transform: scale(1.08);
        }

        .pesan-icon.dibaca {
            background: #f1f5f9;
            box-shadow: none;
        }

        /* ── Unread pulse dot ────────────────────────────────── */
        .unread-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .18);
            animation: pulse-dot 2s infinite;
            flex-shrink: 0;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 0 3px rgba(37, 99, 235, .18);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(37, 99, 235, .08);
            }
        }

        /* ── Badge chip ──────────────────────────────────────── */
        .badge-unread {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            border: 1.5px solid #bfdbfe;
            border-radius: 30px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 700;
            color: #1d4ed8;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            animation: pulse-dot 2s infinite;
        }

        /* ── Empty state ─────────────────────────────────────── */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
            text-align: center;
        }

        .empty-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff6ff, #eef2ff);
            border: 1.5px solid #bfdbfe;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(37, 99, 235, .10);
        }

        /* ── Modal ───────────────────────────────────────────── */
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
            max-width: 520px;
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
            padding: 20px 22px 16px;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .modal-header .deco-m1 {
            position: absolute;
            top: -30px;
            right: -10px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .modal-body {
            padding: 20px 22px;
        }

        .modal-isi {
            background: linear-gradient(135deg, #f0f9ff, #eef2ff);
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 16px;
            font-size: 13.5px;
            line-height: 1.7;
            color: #1e293b;
            white-space: pre-wrap;
            min-height: 110px;
        }

        .modal-footer {
            padding: 0 22px 20px;
            display: flex;
            justify-content: flex-end;
        }

        .modal-close-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
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

        .modal-close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, .36);
        }

        /* ── Spinner ─────────────────────────────────────────── */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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

        .fu-item {
            animation: fadeUp .36s ease both;
        }

        /* ── Pesan meta tag ──────────────────────────────────── */
        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: white;
            border: 1px solid #e0e7ff;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            color: #4f46e5;
        }
    </style>

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══════════════════════════════════════════
         HERO
    ════════════════════════════════════════════ --}}
        <div class="pesan-hero rounded-2xl px-6 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>
            <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1.5">
                        Pesan Guru Wali 💬
                    </h1>
                    @if ($belumDibaca > 0)
                        <div class="badge-unread">
                            <span class="badge-dot"></span>
                            {{ $belumDibaca }} pesan belum dibaca
                        </div>
                    @else
                        <div
                            class="inline-flex items-center gap-1.5 bg-white/15 border border-white/25 rounded-full px-3 py-1">
                            <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white/90 text-[12px] font-semibold">Semua pesan sudah dibaca</span>
                        </div>
                    @endif
                </div>

                {{-- Summary chip --}}
                <div class="flex items-center gap-3 bg-white/12 border border-white/20 rounded-2xl px-4 py-3 shrink-0">
                    <div class="w-10 h-10 rounded-[11px] flex items-center justify-center bg-white/20 shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-[15px] leading-tight">
                            {{ $pesanList->total() ?? $pesanList->count() }}
                        </p>
                        <p class="text-white/65 text-[11px]">Total pesan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
         DAFTAR PESAN CARD
    ════════════════════════════════════════════ --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">

            <div class="section-header">
                <div class="section-icon">
                    <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <span class="text-[13px] font-bold text-gray-900">Kotak Masuk</span>
                @if ($belumDibaca > 0)
                    <span
                        class="ml-auto inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-extrabold text-white"
                        style="background:linear-gradient(135deg,#2563eb,#4f46e5);">
                        {{ $belumDibaca }}
                    </span>
                @endif
            </div>

            <div class="p-4 space-y-2.5">

                @forelse ($pesanList as $i => $pesan)
                    @php $sudahDibaca = $pesan->reads->isNotEmpty(); @endphp

                    <div onclick="bukaPesan({{ $pesan->id }}, this)" data-id="{{ $pesan->id }}"
                        data-dibaca="{{ $sudahDibaca ? '1' : '0' }}"
                        class="pesan-card fu-item {{ $sudahDibaca ? 'sudah-dibaca' : '' }}"
                        style="animation-delay: {{ $i * 0.04 }}s">

                        {{-- Icon --}}
                        <div class="pesan-icon {{ $sudahDibaca ? 'dibaca' : '' }}">
                            @if ($sudahDibaca)
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[13.5px] font-bold truncate mb-0.5
                            {{ $sudahDibaca ? 'text-gray-500' : 'text-gray-900' }}">
                                {{ $pesan->judul }}
                            </p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    class="text-[11.5px] {{ $sudahDibaca ? 'text-gray-400' : 'text-indigo-600 font-semibold' }}">
                                    {{ $pesan->guru->name ?? 'Guru Wali' }}
                                </span>
                                @if (!$sudahDibaca)
                                    <span class="meta-tag">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Baru
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Time + dot --}}
                        <div class="shrink-0 flex flex-col items-end gap-1.5">
                            <span class="text-[11px] {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-500' }} font-medium">
                                {{ $pesan->waktuRelatif() }}
                            </span>
                            @if (!$sudahDibaca)
                                <span class="unread-dot"></span>
                            @else
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </div>
                    </div>

                @empty
                    <div class="empty-state">
                        <div class="empty-icon-wrap">
                            <svg class="w-9 h-9 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-gray-700 mb-1">Belum ada pesan</p>
                        <p class="text-[12.5px] text-gray-400">Pesan dari guru wali akan muncul di sini</p>
                    </div>
                @endforelse

            </div>

            {{-- Pagination --}}
            @if ($pesanList->hasPages())
                <div class="px-4 pb-4 border-t border-indigo-50 pt-3">
                    {{ $pesanList->links() }}
                </div>
            @endif

        </div>
    </div>{{-- end max-width --}}


    {{-- ═══════════════════════════════════════════
     MODAL BACA PESAN
════════════════════════════════════════════ --}}
    <div id="modalPesan" class="modal-backdrop" onclick="handleBackdropClick(event)">
        <div class="modal-box" onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="modal-header">
                <span class="deco-m1"></span>
                <div class="relative z-10 flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        {{-- Sender chip --}}
                        <div
                            class="inline-flex items-center gap-1.5 bg-white/18 border border-white/25
                        rounded-full px-3 py-1 mb-2">
                            <svg class="w-3 h-3 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span id="modalGuru" class="text-white/90 text-[11px] font-semibold"></span>
                            <span class="text-white/40 text-[11px]">·</span>
                            <span id="modalWaktu" class="text-white/70 text-[11px]"></span>
                        </div>
                        <p id="modalJudul" class="text-[15px] font-extrabold text-white leading-snug"></p>
                    </div>
                    <button onclick="tutupModal()"
                        class="shrink-0 w-8 h-8 flex items-center justify-center rounded-xl
                           bg-white/15 hover:bg-white/28 text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Divider strip --}}
            <div class="h-[3px]" style="background:linear-gradient(90deg,#3b82f6,#4f46e5,#7c3aed)"></div>

            {{-- Body --}}
            <div class="modal-body">

                {{-- Loading --}}
                <div id="modalLoading" class="hidden flex items-center justify-center py-10">
                    <svg class="w-8 h-8 text-blue-500 spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                </div>

                {{-- Isi pesan --}}
                <div id="modalIsiWrap" class="hidden">
                    {{-- Label --}}
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-5 h-5 rounded-[6px] flex items-center justify-center"
                            style="background:linear-gradient(135deg,#3b82f6,#4f46e5)">
                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Isi Pesan</span>
                    </div>
                    <div id="modalIsi" class="modal-isi"></div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button onclick="tutupModal()" class="modal-close-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Tandai Dibaca & Tutup
                </button>
            </div>

        </div>
    </div>


    <script>
        let belumDibacaCount = {{ $belumDibaca }};

        /* ── Buka pesan ──────────────────────────────────────── */
        function bukaPesan(id, elRow) {
            const modal = document.getElementById('modalPesan');
            modal.classList.add('flex');

            // Reset
            document.getElementById('modalJudul').textContent = '';
            document.getElementById('modalGuru').textContent = '';
            document.getElementById('modalWaktu').textContent = '';
            document.getElementById('modalIsi').textContent = '';
            document.getElementById('modalLoading').classList.remove('hidden');
            document.getElementById('modalIsiWrap').classList.add('hidden');

            fetch(`/student/pesan/${id}/baca`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(r => r.json())
                .then(res => {
                    if (!res.success) return;
                    const p = res.pesan;
                    document.getElementById('modalJudul').textContent = p.judul;
                    document.getElementById('modalGuru').textContent = p.guru;
                    document.getElementById('modalWaktu').textContent = p.dibuat;
                    document.getElementById('modalIsi').textContent = p.isi;
                    document.getElementById('modalLoading').classList.add('hidden');
                    document.getElementById('modalIsiWrap').classList.remove('hidden');

                    // Update row UI
                    if (elRow && elRow.dataset.dibaca === '0') {
                        elRow.dataset.dibaca = '1';
                        tandaiSudahDibaca(elRow);
                        kurangiCounter();
                    }
                })
                .catch(() => {
                    document.getElementById('modalLoading').classList.add('hidden');
                    document.getElementById('modalIsi').textContent = 'Gagal memuat pesan. Coba lagi.';
                    document.getElementById('modalIsiWrap').classList.remove('hidden');
                });
        }

        /* ── Tandai baris sudah dibaca ───────────────────────── */
        function tandaiSudahDibaca(elRow) {
            elRow.classList.add('sudah-dibaca');

            // Icon wrap
            const iconWrap = elRow.querySelector('.pesan-icon');
            if (iconWrap) {
                iconWrap.classList.add('dibaca');
                iconWrap.style.background = '#f1f5f9';
                iconWrap.style.boxShadow = 'none';
                const svg = iconWrap.querySelector('svg');
                if (svg) svg.classList.replace('text-white', 'text-gray-400');
            }

            // Judul
            const judul = elRow.querySelector('p.font-bold');
            if (judul) {
                judul.classList.remove('text-gray-900');
                judul.classList.add('text-gray-500');
            }

            // Guru name
            const guru = elRow.querySelector('.text-indigo-600');
            if (guru) {
                guru.classList.remove('text-indigo-600', 'font-semibold');
                guru.classList.add('text-gray-400');
            }

            // Badge "Baru"
            const badge = elRow.querySelector('.meta-tag');
            if (badge) badge.remove();

            // Unread dot → checkmark
            const dot = elRow.querySelector('.unread-dot');
            if (dot) {
                dot.outerHTML =
                    `<svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            }
        }

        /* ── Kurangi counter hero ────────────────────────────── */
        function kurangiCounter() {
            belumDibacaCount = Math.max(0, belumDibacaCount - 1);

            const hero = document.querySelector('.badge-unread');
            if (hero) {
                if (belumDibacaCount <= 0) {
                    hero.outerHTML = `<div class="inline-flex items-center gap-1.5 bg-white/15 border border-white/25 rounded-full px-3 py-1">
                    <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-white/90 text-[12px] font-semibold">Semua pesan sudah dibaca</span>
                </div>`;
                } else {
                    hero.querySelector('span:last-child').textContent = belumDibacaCount + ' pesan belum dibaca';
                }
            }

            // Section header badge
            const headerBadge = document.querySelector('.section-header span.font-extrabold');
            if (headerBadge) {
                if (belumDibacaCount <= 0) headerBadge.remove();
                else headerBadge.textContent = belumDibacaCount;
            }
        }

        /* ── Tutup modal ─────────────────────────────────────── */
        function tutupModal() {
            document.getElementById('modalPesan').classList.remove('flex');
        }

        function handleBackdropClick(e) {
            if (e.target === document.getElementById('modalPesan')) tutupModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') tutupModal();
        });
    </script>

@endsection