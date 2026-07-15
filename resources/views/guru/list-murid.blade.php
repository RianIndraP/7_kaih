@extends('layouts.layouts-guru')

@section('title', 'SMK N 5 Telkom Banda Aceh | Data Siswa')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.6.0/jspdf.plugin.autotable.min.js"></script>

    <style>
        /* ── Hero ─────────────────────────────────────────────── */
        .lm-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            position: relative;
            overflow: hidden;
        }

        .lm-hero::before {
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

        /* ── Filter inputs ───────────────────────────────────── */
        .fancy-select,
        .fancy-input {
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            color: #1e293b;
            background: white;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }

        .fancy-select {
            cursor: pointer;
            padding-right: 32px;
            appearance: none;
        }

        .fancy-select:focus,
        .fancy-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .fancy-select:hover,
        .fancy-input:hover {
            border-color: #bfdbfe;
        }

        .fancy-input::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }

        /* ── Filter select wrapper (chevron) ─────────────────── */
        .sel-wrap {
            position: relative;
        }

        .sel-wrap svg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
        }

        /* ── Buttons ─────────────────────────────────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .28);
            transition: transform .2s, box-shadow .2s;
            font-family: inherit;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, .36);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: #374151;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            font-family: inherit;
        }

        .btn-outline:hover {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #2563eb;
        }

        /* ── Search input ────────────────────────────────────── */
        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-wrap input {
            padding-left: 38px;
        }

        /* ── Table ───────────────────────────────────────────── */
        #tabelMurid thead th {
            font-size: 10.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 11px 14px;
            background: linear-gradient(90deg, #f8faff, #f5f3ff);
            border-bottom: 1.5px solid #e0e7ff;
            white-space: nowrap;
        }

        #tabelMurid tbody td {
            padding: 11px 14px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
        }

        #tabelMurid tbody tr:hover td {
            background: #f8faff;
        }

        #tabelMurid tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── Action icon buttons ─────────────────────────────── */
        .act-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all .18s;
            background: transparent;
        }

        .act-btn.blue {
            color: #3b82f6;
        }

        .act-btn.blue:hover {
            background: #eff6ff;
        }

        .act-btn.purple {
            color: #8b5cf6;
        }

        .act-btn.purple:hover {
            background: #f5f3ff;
        }

        .act-btn.red {
            color: #ef4444;
        }

        .act-btn.red:hover {
            background: #fff1f2;
        }

        /* ── Progress bar ────────────────────────────────────── */
        .prog-track {
            height: 6px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            min-width: 72px;
        }

        .prog-fill {
            height: 100%;
            border-radius: 99px;
            transition: width .4s;
        }

        /* ── Modal base ──────────────────────────────────────── */
        .modal-bd {
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

        .modal-bd.flex {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 20px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .22);
            animation: mpop .22s cubic-bezier(.34, 1.56, .64, 1) both;
        }

        @keyframes mpop {
            from {
                opacity: 0;
                transform: scale(.92) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-hdr {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
            padding: 16px 20px;
            position: relative;
            overflow: hidden;
        }

        .modal-hdr .deco {
            position: absolute;
            top: -30px;
            right: -10px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .modal-hdr .strip {
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* ── Modal tabs ──────────────────────────────────────── */
        .modal-tabs {
            display: flex;
            border-bottom: 1px solid #e0e7ff;
        }

        .modal-tab {
            flex: 1;
            padding: 10px;
            font-size: 12.5px;
            font-weight: 700;
            text-align: center;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: #64748b;
            transition: all .18s;
            font-family: inherit;
        }

        .modal-tab.active {
            border-color: #2563eb;
            color: #1d4ed8;
            background: #eff6ff;
        }

        /* ── Data grid inside modal ──────────────────────────── */
        .data-pair {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 8px 10px;
            background: #f8fafc;
            border-radius: 9px;
            border: 1px solid #f1f5f9;
        }

        .data-pair dt {
            font-size: 10.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .data-pair dd {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* ── Kebiasaan bar in detail ─────────────────────────── */
        .kb-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kb-label {
            font-size: 12px;
            color: #64748b;
            width: 110px;
            flex-shrink: 0;
        }

        .kb-track {
            flex: 1;
            height: 6px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
        }

        .kb-fill {
            height: 100%;
            border-radius: 99px;
        }

        /* ── Stats card ──────────────────────────────────────── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .stat-chip {
            border-radius: 8px;
            padding: 6px 8px;
            text-align: center;
        }

        /* ── Toast ───────────────────────────────────────────── */
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

        /* ── Map ─────────────────────────────────────────────── */
        #profilMap {
            height: 220px !important;
            width: 100%;
            display: block;
            border-radius: 12px;
            border: 1.5px solid #bfdbfe;
            overflow: hidden;
        }

        /* ── Stagger ─────────────────────────────────────────── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
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

        /* ── Scrollable modal body ───────────────────────────── */
        .modal-scroll {
            max-height: calc(90vh - 120px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .modal-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .modal-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* ── Responsive table scroll ─────────────────────────── */
        .tbl-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <div class="max-w-[1100px] mx-auto space-y-4">

        {{-- ═══ HERO ═══ --}}
        <div class="lm-hero rounded-2xl px-5 py-5 fu-0">
            <span class="hero-deco-1"></span>
            <span class="hero-deco-2"></span>
            <div class="relative z-10 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-white font-extrabold text-[18px] leading-tight mb-1">List Murid 👥</h1>
                    <p class="text-white/72 text-[12.5px]">Pantau perkembangan dan kebiasaan siswa asuh</p>
                </div>
                <div class="flex items-center gap-2 bg-white/12 border border-white/20 rounded-2xl px-4 py-3 shrink-0">
                    <div class="w-9 h-9 rounded-[10px] bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-extrabold text-[15px] leading-tight" id="totalSiswaCount">-</p>
                        <p class="text-white/65 text-[11px]">Siswa Asuh</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ FILTER CARD ═══ --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-1">
            <div class="section-header">
                <div class="section-icon">
                    <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                </div>
                <span class="text-[13px] font-bold text-gray-900">Filter & Pencarian</span>
            </div>

            <div class="p-4 space-y-3">
                {{-- Search --}}
                <div class="search-wrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama atau NISN siswa..."
                        class="fancy-input w-full" />
                </div>

                {{-- Filters row --}}
                <div class="flex flex-wrap gap-2 items-center">
                    {{-- Periode --}}
                    <div class="sel-wrap">
                        <select id="selectPeriode" class="fancy-select">
                            <option value="">Semua Periode</option>
                            <option value="harian">Per Hari</option>
                            <option value="mingguan">Per Minggu</option>
                            <option value="pertemuan">Per Pertemuan</option>
                            <option value="bulanan">Per Bulan</option>
                        </select>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    {{-- Harian --}}
                    <div id="filter_harian" class="hidden">
                        <input type="date" id="input_tanggal" class="fancy-input" />
                    </div>

                    {{-- Mingguan --}}
                    <div id="filter_mingguan" class="hidden sel-wrap">
                        <select id="select_minggu" class="fancy-select" style="min-width:200px;">
                            <option value="">-- Pilih Minggu --</option>
                        </select>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    {{-- Pertemuan --}}
                    <div id="filter_pertemuan" class="hidden sel-wrap">
                        <select id="select_pertemuan" class="fancy-select" style="min-width:210px;">
                            <option value="">-- Pilih Pertemuan --</option>
                        </select>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    {{-- Bulanan --}}
                    <div id="filter_bulanan" class="hidden sel-wrap">
                        <select id="select_bulan" class="fancy-select" style="min-width:160px;">
                            <option value="">-- Pilih Bulan --</option>
                        </select>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div class="flex items-center gap-2 ml-auto">
                        <button onclick="cariData()" class="btn-primary">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                        <button onclick="downloadPDF()" class="btn-outline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ TABLE CARD ═══ --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden fu-2">
            <div class="section-header">
                <div class="section-icon">
                    <svg class="w-[14px] h-[14px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[13px] font-bold text-gray-900">Data Murid</span>
                <span id="rowCount" class="ml-auto text-[11px] text-gray-400 font-semibold"></span>
            </div>

            <div class="tbl-wrap">
                <table class="w-full" id="tabelMurid" style="min-width:680px;">
                    <thead>
                        <tr>
                            <th class="text-left" style="width:44px;">No</th>
                            <th class="text-left">Nama</th>
                            <th class="text-left">Kelas</th>
                            <th class="text-left">NISN</th>
                            <th class="text-left">Streak</th>
                            <th class="text-left">Penyelesaian</th>
                            <th class="text-left">Umpan Balik</th>
                            <th class="text-center" style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBody">
                        <tr id="emptyRow">
                            <td colspan="8" style="padding:48px 20px; text-align:center;">
                                <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                                    <div
                                        style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#eff6ff,#eef2ff);border:1.5px solid #bfdbfe;display:flex;align-items:center;justify-content:center;">
                                        <svg style="width:24px;height:24px;color:#3b82f6;" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <p style="font-size:13px;font-weight:700;color:#374151;margin:0;">Pilih periode lalu
                                        klik Cari</p>
                                    <p style="font-size:12px;color:#94a3b8;margin:0;">Data murid akan tampil di sini</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end max-width --}}


    {{-- ═══ MODAL PROFIL SISWA ═══ --}}
    <div id="modalProfil" class="modal-bd" onclick="if(event.target===this)tutupModal('modalProfil')">
        <div class="modal-box" style="max-width:680px;" onclick="event.stopPropagation()">

            <div class="modal-hdr">
                <span class="deco"></span><span class="strip"></span>
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <div
                            class="inline-flex items-center gap-1.5 bg-white/15 border border-white/25 rounded-full px-3 py-1 mb-2">
                            <svg class="w-3 h-3 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-white/90 text-[11px] font-semibold">Profil Siswa</span>
                        </div>
                        <p id="profilNama" class="text-[15px] font-extrabold text-white"></p>
                        <p id="profilNisn" class="text-[11.5px] text-white/70 mt-0.5"></p>
                    </div>
                    <button onclick="tutupModal('modalProfil')"
                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/15 hover:bg-white/28 text-white transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-[3px]" style="background:linear-gradient(90deg,#3b82f6,#4f46e5,#7c3aed)"></div>

            <div class="modal-scroll p-5">
                <div class="flex flex-col sm:flex-row gap-5">
                    {{-- Photo --}}
                    <div class="flex flex-col items-center gap-2 shrink-0">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden flex items-center justify-center"
                            style="background:linear-gradient(135deg,#eff6ff,#eef2ff);border:1.5px solid #bfdbfe;">
                            <img id="profilFoto" src="" alt="Foto" class="w-full h-full object-cover hidden"
                                onerror="this.classList.add('hidden');document.getElementById('profilFotoPlaceholder').classList.remove('hidden');">
                            <div id="profilFotoPlaceholder" class="hidden">
                                <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 grid grid-cols-2 gap-2">
                        <div class="data-pair">
                            <dt>Jenis Kelamin</dt>
                            <dd id="profilGender">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Kelas</dt>
                            <dd id="profilKelas">-</dd>
                        </div>
                        <div class="data-pair col-span-2">
                            <dt>Tempat, Tanggal Lahir</dt>
                            <dd id="profilTtl">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Tahun Masuk</dt>
                            <dd id="profilAngkatan">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Hobi</dt>
                            <dd id="profilHobi">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Cita-Cita</dt>
                            <dd id="profilCitaCita">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Teman Terbaik</dt>
                            <dd id="profilTemanTerbaik">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Makanan Kesukaan</dt>
                            <dd id="profilMakanan">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>Warna Kesukaan</dt>
                            <dd id="profilWarna">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>No Telepon</dt>
                            <dd id="profilNoTelepon">-</dd>
                        </div>
                        <div class="data-pair">
                            <dt>No Orang Tua</dt>
                            <dd id="profilNoOrtu">-</dd>
                        </div>
                        <div class="data-pair col-span-2">
                            <dt>Alamat</dt>
                            <dd id="profilAlamat">-</dd>
                        </div>
                    </div>
                </div>

                {{-- Map --}}
                <div class="mt-4">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">Lokasi Rumah</p>
                    <div id="profilMap"></div>
                </div>
            </div>

            <div class="flex justify-end gap-2 px-5 py-3 border-t border-indigo-50">
                <button id="btnDapatkanRute" onclick="bukaGoogleMapsRute()" class="btn-primary hidden"
                    style="padding:8px 16px;font-size:12.5px;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Buka Maps
                </button>
                <button onclick="tutupModal('modalProfil')" class="btn-outline"
                    style="padding:8px 20px;font-size:12.5px;">Tutup</button>
            </div>
        </div>
    </div>


    {{-- ═══ MODAL DETAIL MURID ═══ --}}
    <div id="modalDetail" class="modal-bd" onclick="if(event.target===this)tutupModal('modalDetail')">
        <div class="modal-box" style="max-width:760px;" onclick="event.stopPropagation()">

            <div class="modal-hdr">
                <span class="deco"></span><span class="strip"></span>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-white/70 font-semibold mb-0.5">Detail Info</p>
                        <p id="detailNamaHdr" class="text-[15px] font-extrabold text-white">-</p>
                    </div>
                    <button onclick="tutupModal('modalDetail')"
                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/15 hover:bg-white/28 text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-[3px]" style="background:linear-gradient(90deg,#3b82f6,#4f46e5,#7c3aed)"></div>

            {{-- Tabs --}}
            <div id="detailTabSwitcher" class="modal-tabs hidden">
                <button id="tabPenyelesaian" class="modal-tab active" onclick="switchDetailTab('penyelesaian')">Penyelesaian
                    Form</button>
                <button id="tabDetail" class="modal-tab" onclick="switchDetailTab('detail')">Detail Form</button>
            </div>

            <div class="modal-scroll">

                {{-- Panel Penyelesaian --}}
                <div id="panelPenyelesaian" class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Aspek --}}
                        <div
                            style="background:linear-gradient(135deg,#f0f9ff,#eef2ff);border:1.5px solid #bfdbfe;border-radius:14px;padding:14px;">
                            <p class="text-[12px] font-bold text-gray-700 mb-1">Penyelesaian Form</p>
                            <p class="text-[11px] text-blue-500 font-semibold mb-3" id="detailAspekLabel">Aspek Harian</p>
                            <div class="space-y-2" id="detailAspekList"></div>
                            <div class="mt-3 pt-3 border-t border-blue-100">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[11px] font-bold text-gray-500">Total Rata-rata</span>
                                    <span id="detailProgressText" class="text-[11px] font-extrabold text-blue-600">0%</span>
                                </div>
                                <div class="prog-track">
                                    <div id="detailProgressBar" class="prog-fill"
                                        style="background:linear-gradient(90deg,#2563eb,#4f46e5);width:0%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Identitas + umpan balik --}}
                        <div class="space-y-3">
                            <div style="background:#f8fafc;border:1.5px solid #f1f5f9;border-radius:14px;padding:14px;">
                                <p class="text-[12px] font-bold text-gray-700 mb-2">Identitas</p>
                                <div class="space-y-1">
                                    <p class="text-[13px] font-extrabold text-gray-900" id="detailNama">-</p>
                                    <p class="text-[12px] text-gray-500" id="detailKelas">-</p>
                                    <p class="text-[12px] text-gray-500" id="detailNisn">-</p>
                                    <p class="text-[12px] text-gray-500" id="detailTanggal">-</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Umpan Balik
                                </p>
                                <div id="detailUmpanBalik"
                                    style="font-size:12.5px;color:#374151;background:linear-gradient(135deg,#eff6ff,#eef2ff);border:1.5px solid #bfdbfe;border-radius:12px;padding:12px;max-height:120px;overflow-y:auto;line-height:1.6;">
                                    -</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Detail Form --}}
                <div id="panelDetail" class="hidden p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="detailFormContent">

                        @php
                            $formItems = [
                                ['id' => 'BangunPagi', 'label' => 'Bangun Pagi', 'color' => 'from-amber-400 to-orange-500', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                                ['id' => 'Beribadah', 'label' => 'Beribadah', 'color' => 'from-emerald-400 to-green-500', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                                ['id' => 'Berolahraga', 'label' => 'Berolahraga', 'color' => 'from-orange-400 to-red-500', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                ['id' => 'MakanSehat', 'label' => 'Makan Sehat', 'color' => 'from-yellow-400 to-amber-500', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                ['id' => 'GemarBelajar', 'label' => 'Gemar Belajar', 'color' => 'from-violet-400 to-purple-500', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['id' => 'Bermasyarakat', 'label' => 'Bermasyarakat', 'color' => 'from-cyan-400 to-blue-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                                ['id' => 'TidurCepat', 'label' => 'Tidur Cepat', 'color' => 'from-indigo-400 to-violet-500', 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
                            ];
                        @endphp

                        @foreach ($formItems as $fi)
                            <div style="background:white;border:1.5px solid #f1f5f9;border-radius:14px;padding:14px;">
                                <div class="flex items-center gap-2 mb-3">
                                    <div
                                        class="w-7 h-7 rounded-[8px] bg-gradient-to-br {{ $fi['color'] }} flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $fi['icon'] }}" />
                                        </svg>
                                    </div>
                                    <p class="text-[12.5px] font-bold text-gray-800">{{ $fi['label'] }}</p>
                                </div>
                                @if ($fi['id'] === 'BangunPagi')
                                    <div class="grid grid-cols-2 gap-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Status</dt>
                                            <dd id="detailBangunPagiStatus">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Jam</dt>
                                            <dd id="detailBangunPagiJam">-</dd>
                                        </div>
                                        <div class="data-pair col-span-2">
                                            <dt>Catatan</dt>
                                            <dd id="detailBangunPagiCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'Beribadah')
                                    <div class="space-y-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Sholat</dt>
                                            <dd id="detailBeribadahSholatList">-</dd>
                                        </div>
                                        <div class="grid grid-cols-2 gap-1.5">
                                            <div class="data-pair">
                                                <dt>Quran</dt>
                                                <dd id="detailBeribadahQuran">-</dd>
                                            </div>
                                            <div class="data-pair">
                                                <dt>Surah</dt>
                                                <dd id="detailBeribadahSurah">-</dd>
                                            </div>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Catatan</dt>
                                            <dd id="detailBeribadahCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'Berolahraga')
                                    <div class="space-y-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Status</dt>
                                            <dd id="detailBerolahragaStatus">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Jenis</dt>
                                            <dd id="detailBerolahragaList">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Catatan</dt>
                                            <dd id="detailBerolahragaCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'MakanSehat')
                                    <div class="grid grid-cols-2 gap-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Status</dt>
                                            <dd id="detailMakanSehatStatus">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Pagi</dt>
                                            <dd id="detailMakanPagi">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Siang</dt>
                                            <dd id="detailMakanSiang">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Malam</dt>
                                            <dd id="detailMakanMalam">-</dd>
                                        </div>
                                        <div class="data-pair col-span-2">
                                            <dt>Catatan</dt>
                                            <dd id="detailMakanSehatCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'GemarBelajar')
                                    <div class="space-y-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Status</dt>
                                            <dd id="detailGemarBelajarStatus">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Materi</dt>
                                            <dd id="detailGemarBelajarJenis">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Catatan</dt>
                                            <dd id="detailGemarBelajarCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'Bermasyarakat')
                                    <div class="space-y-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Bersama</dt>
                                            <dd id="detailBermasyarakatDengan">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Catatan</dt>
                                            <dd id="detailBermasyarakatCatatan">-</dd>
                                        </div>
                                    </div>
                                @elseif ($fi['id'] === 'TidurCepat')
                                    <div class="grid grid-cols-2 gap-1.5 text-[12px]">
                                        <div class="data-pair">
                                            <dt>Status</dt>
                                            <dd id="detailTidurCepatStatus">-</dd>
                                        </div>
                                        <div class="data-pair">
                                            <dt>Jam</dt>
                                            <dd id="detailTidurCepatJam">-</dd>
                                        </div>
                                        <div class="data-pair col-span-2">
                                            <dt>Catatan</dt>
                                            <dd id="detailTidurCepatCatatan">-</dd>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Panel Stats --}}
                <div id="panelStats" class="hidden p-4">
                    <div
                        style="background:linear-gradient(135deg,#f0f9ff,#eef2ff);border:1.5px solid #bfdbfe;border-radius:14px;padding:14px;">
                        <p class="text-[12.5px] font-bold text-gray-700 mb-1">Statistik Kebiasaan</p>
                        <p class="text-[11.5px] text-blue-500 font-semibold mb-3" id="statsPeriod">-</p>
                        <div class="space-y-3" id="statsContent"></div>
                    </div>
                </div>

            </div>

            <div class="flex justify-end px-5 py-3 border-t border-indigo-50">
                <button onclick="tutupModal('modalDetail')" class="btn-outline"
                    style="padding:8px 20px;font-size:12.5px;">Tutup</button>
            </div>
        </div>
    </div>


    {{-- ═══ MODAL KIRIM PESAN ═══ --}}
    <div id="modalPesan" class="modal-bd" onclick="if(event.target===this)tutupModal('modalPesan')">
        <div class="modal-box" style="max-width:520px;" onclick="event.stopPropagation()">

            <div class="modal-hdr">
                <span class="deco"></span><span class="strip"></span>
                <div class="relative z-10 flex items-center justify-between">
                    <p class="text-[15px] font-extrabold text-white">💬 Kirim Pesan</p>
                    <button onclick="tutupModal('modalPesan')"
                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-white/15 hover:bg-white/28 text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="h-[3px]" style="background:linear-gradient(90deg,#3b82f6,#4f46e5,#7c3aed)"></div>

            <div class="modal-tabs">
                <button id="tabKirim" class="modal-tab active" onclick="switchTabPesan('kirim')">Kirim Pesan</button>
                <button id="tabHistory" class="modal-tab" onclick="switchTabPesan('history')">History</button>
            </div>

            {{-- Panel Kirim --}}
            <div id="panelKirim" class="p-4 space-y-3">
                <div class="flex items-center justify-between p-3 rounded-xl"
                    style="background:linear-gradient(135deg,#eff6ff,#eef2ff);border:1.5px solid #bfdbfe;">
                    <div>
                        <p class="text-[11px] text-blue-400 font-bold uppercase tracking-widest">Kepada</p>
                        <p id="pesanNamaMurid" class="text-[13px] font-extrabold text-blue-900">-</p>
                    </div>
                    <span class="text-[11px] text-gray-400" id="pesanTanggal"></span>
                </div>

                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Judul Pesan</p>
                    <div class="p-3 rounded-xl text-[12.5px] font-semibold text-gray-700"
                        style="background:#f8fafc;border:1.5px solid #e2e8f0;">
                        <span id="pesanJudul">-</span>
                    </div>
                    <input type="hidden" id="pesanSiswaId" />
                </div>

                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Isi Pesan</p>
                    <textarea id="pesanIsi" rows="5"
                        style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:12.5px;font-weight:500;color:#1e293b;background:#f8fafc;outline:none;resize:none;font-family:inherit;box-sizing:border-box;"
                        placeholder="Tuliskan umpan balik untuk siswa ini..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button onclick="kirimPesan()" class="btn-primary">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim Pesan
                    </button>
                </div>
            </div>

            {{-- Panel History --}}
            <div id="panelHistory" class="hidden p-4">
                <div class="tbl-wrap">
                    <table style="width:100%;font-size:11.5px;border-collapse:collapse;">
                        <thead>
                            <tr style="background:linear-gradient(90deg,#f8faff,#f5f3ff);">
                                <th
                                    style="padding:8px 10px;text-align:left;font-weight:700;color:#64748b;border-bottom:1px solid #e0e7ff;">
                                    Siswa</th>
                                <th
                                    style="padding:8px 10px;text-align:left;font-weight:700;color:#64748b;border-bottom:1px solid #e0e7ff;">
                                    Judul</th>
                                <th
                                    style="padding:8px 10px;text-align:left;font-weight:700;color:#64748b;border-bottom:1px solid #e0e7ff;">
                                    Isi</th>
                                <th
                                    style="padding:8px 10px;text-align:left;font-weight:700;color:#64748b;border-bottom:1px solid #e0e7ff;">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="historyBody">
                            <tr>
                                <td colspan="4" style="padding:24px;text-align:center;color:#94a3b8;">Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end mt-3">
                    <button onclick="tutupModal('modalPesan')" class="btn-outline"
                        style="padding:8px 20px;font-size:12.5px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ MODAL HAPUS ═══ --}}
    <div id="modalHapus" class="modal-bd" onclick="if(event.target===this)tutupModal('modalHapus')">
        <div class="modal-box" style="max-width:380px;" onclick="event.stopPropagation()">
            <div class="p-5">
                <div class="flex items-start gap-3 mb-4">
                    <div
                        style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fff1f2,#ffe4e6);border:1.5px solid #fca5a5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:18px;height:18px;color:#ef4444;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-gray-900 mb-0.5">Hapus Umpan Balik</p>
                        <p class="text-[12px] text-gray-500">Apakah kamu yakin ingin menghapus semua umpan balik untuk siswa
                            ini?</p>
                    </div>
                </div>
                <div
                    style="background:linear-gradient(135deg,#eff6ff,#eef2ff);border:1.5px solid #bfdbfe;border-radius:10px;padding:10px 12px;margin-bottom:16px;">
                    <p class="text-[12px] text-gray-600">Nama: <span id="hapusNamaSiswa"
                            class="font-bold text-gray-900">-</span></p>
                </div>
                <div class="flex gap-2 justify-end">
                    <button onclick="tutupModal('modalHapus')" class="btn-outline"
                        style="padding:8px 16px;font-size:12.5px;">Batal</button>
                    <button onclick="konfirmasiHapus()"
                        style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;border:none;border-radius:10px;padding:8px 16px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:inherit;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Toast --}}
    <div id="toast" style="background:linear-gradient(135deg,#2563eb,#4f46e5);">
        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <span id="toastMsg"></span>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        /* ── semua logika JS dari file asli, tidak diubah ── */
        var semuaSiswa = @json($siswaList);
        var currentPeriode = '';
        var currentFilter = '';
        var hapusSiswaId = null;
        var hapusUmpanBalikId = null;
        var pesanSiswaData = null;
        var currentSiswaLat = null;
        var currentSiswaLng = null;
        var NAMA_BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        // Academic year settings
        var tahunAjaran = '{{ $tahunAjaran }}';
        var mulaiTahunBaru = '{{ $mulaiTahunBaru }}';
        var tanggalMulaiAjaran = new Date(mulaiTahunBaru);
        var tanggalSekarang = new Date();

        document.getElementById('selectPeriode').addEventListener('change', function () {
            currentPeriode = this.value;
            ['harian', 'mingguan', 'pertemuan', 'bulanan'].forEach(function (p) { document.getElementById('filter_' + p).classList.add('hidden'); });
            if (!currentPeriode) return;
            document.getElementById('filter_' + currentPeriode).classList.remove('hidden');
            if (currentPeriode === 'mingguan') generateMinggu();
            if (currentPeriode === 'pertemuan') generatePertemuan();
            if (currentPeriode === 'bulanan') generateBulan();
        });

        function generateMinggu() {
            var year = new Date().getFullYear();
            var sel = document.getElementById('select_minggu');
            sel.innerHTML = '<option value="">-- Pilih Minggu --</option>';
            
            // Generate weeks only from academic year start date
            for (var m = 0; m < 12; m++) { 
                var d = new Date(year, m, 1), w = 1; 
                while (d.getMonth() === m) { 
                    // Check if this week is after or equal to academic year start
                    if (d >= tanggalMulaiAjaran && d <= tanggalSekarang) {
                        var opt = document.createElement('option'); 
                        opt.value = year + '-' + String(m + 1).padStart(2, '0') + '-W' + w; 
                        opt.textContent = 'Minggu ' + w + ' - ' + d.getDate() + ' ' + NAMA_BULAN[m] + ' ' + year; 
                        sel.appendChild(opt); 
                    }
                    d.setDate(d.getDate() + 7); 
                    w++; 
                } 
            }
        }
        
        function generatePertemuan() {
            var year = new Date().getFullYear(), sel = document.getElementById('select_pertemuan');
            sel.innerHTML = '<option value="">-- Pilih Pertemuan --</option>';
            var d = new Date(year, 0, 1), p = 1;
            
            // Generate meetings only from academic year start date
            while (d.getFullYear() === year) { 
                // Check if this meeting is after or equal to academic year start
                if (d >= tanggalMulaiAjaran && d <= tanggalSekarang) {
                    var opt = document.createElement('option'); 
                    opt.value = year + '-P' + p; 
                    opt.textContent = 'Pertemuan ' + p + ' - ' + d.getDate() + ' ' + NAMA_BULAN[d.getMonth()] + ' ' + year; 
                    sel.appendChild(opt); 
                }
                d.setDate(d.getDate() + 14); 
                p++; 
            }
        }
        
        function generateBulan() {
            var year = new Date().getFullYear(), sel = document.getElementById('select_bulan');
            sel.innerHTML = '<option value="">-- Pilih Bulan --</option>';
            
            // Generate months only from academic year start date
            NAMA_BULAN.forEach(function (nama, i) { 
                var bulanDate = new Date(year, i, 1);
                // Check if this month is after or equal to academic year start
                if (bulanDate >= tanggalMulaiAjaran && bulanDate <= tanggalSekarang) {
                    var opt = document.createElement('option'); 
                    opt.value = (i + 1) + '|' + year; 
                    opt.textContent = nama + ' ' + year; 
                    sel.appendChild(opt); 
                }
            });
        }

        function cariData() {
            var periode = document.getElementById('selectPeriode').value;
            var filter = '';
            if (periode === 'harian') filter = document.getElementById('input_tanggal').value;
            if (periode === 'mingguan') filter = document.getElementById('select_minggu').value;
            if (periode === 'pertemuan') filter = document.getElementById('select_pertemuan').value;
            if (periode === 'bulanan') { var raw = document.getElementById('select_bulan').value; if (raw) { var parts = raw.split('|'); filter = parts[0] + '|' + (parts[1] || new Date().getFullYear()); } }
            currentFilter = filter;

            var url = '/guru/list-murid/get-siswa';
            if (periode) url += '?periode=' + periode + '&filter=' + encodeURIComponent(filter);

            fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        // Update hero count
                        if (res.data) document.getElementById('totalSiswaCount').textContent = res.data.length;
                        if (!res.data || !res.data.length) {
                            document.getElementById('tabelBody').innerHTML = '<tr><td colspan="8" style="padding:40px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada siswa untuk periode ini</td></tr>';
                            document.getElementById('rowCount').textContent = '';
                        } else {
                            renderTabel(res.data, periode, res.no_periode);
                            document.getElementById('rowCount').textContent = res.data.length + ' siswa';
                        }
                    } else { tampilkanToast('Gagal: ' + (res.message || 'Terjadi kesalahan'), 'red'); }
                })
                .catch(function () { tampilkanToast('Gagal terhubung ke server', 'red'); });
        }

        function renderTabel(data, periode, noPeriode) {
            var tbody = document.getElementById('tabelBody');
            var keyword = document.getElementById('searchInput').value.toLowerCase();
            var filtered = data.filter(function (s) { return !keyword || s.nama.toLowerCase().includes(keyword) || s.nisn.includes(keyword); });

            if (!filtered.length) { tbody.innerHTML = '<tr><td colspan="8" style="padding:32px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada data</td></tr>'; return; }

            tbody.innerHTML = filtered.map(function (s, i) {
                var persen = s.persen || 0;
                var barColor = persen >= 80 ? '#22c55e' : persen >= 50 ? '#3b82f6' : '#f59e0b';
                var umpan = noPeriode ? '<span style="color:#94a3b8;font-style:italic;font-size:11.5px;">Pilih Periode</span>'
                    : (s.umpan_balik ? (s.umpan_balik.substring(0, 22) + (s.umpan_balik.length > 22 ? '…' : '')) : '<span style="color:#cbd5e1;">-</span>');

                var profBtn = '<button onclick="bukaProfil(' + s.id + ')" title="Lihat Profil" class="act-btn blue"><svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>';
                var aksiHtml = noPeriode ? profBtn
                    : '<div style="display:flex;align-items:center;justify-content:center;gap:2px;">'
                    + profBtn
                    + '<button onclick="bukaDetail(' + JSON.stringify(s).replace(/"/g, '&quot;') + ',\'' + periode + '\')" title="Detail" class="act-btn blue"><svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/></svg></button>'
                    + '<button onclick="bukaPesan(' + JSON.stringify(s).replace(/"/g, '&quot;') + ',\'' + periode + '\')" title="Kirim Pesan" class="act-btn purple"><svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg></button>'
                    + '<button onclick="bukaHapus(' + s.id + ',\'' + s.nama + '\')" title="Hapus" class="act-btn red"><svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>'
                    + '</div>';

                return '<tr>'
                    + '<td style="color:#64748b;font-size:12.5px;">' + (i + 1) + '</td>'
                    + '<td style="font-weight:700;color:#1e293b;">' + s.nama + '</td>'
                    + '<td style="color:#64748b;">' + s.kelas + '</td>'
                    + '<td style="color:#64748b;font-size:12px;">' + s.nisn + '</td>'
                    + '<td style="font-weight:800;color:#f97316;">🔥 ' + (s.streak_count || 0) + '</td>'
                    + '<td style="min-width:90px;">'
                    + '<div class="prog-track"><div class="prog-fill" style="background:' + barColor + ';width:' + persen + '%;"></div></div>'
                    + '<span style="font-size:11px;color:#64748b;">' + (noPeriode ? '-' : persen + '%') + '</span>'
                    + '</td>'
                    + '<td style="font-size:12px;max-width:140px;">' + umpan + '</td>'
                    + '<td style="text-align:center;">' + aksiHtml + '</td>'
                    + '</tr>';
            }).join('');
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            if (document.getElementById('tabelBody').querySelector('[colspan]')) return;
            cariData();
        });

        function bukaDetail(siswa, periode) {
            document.getElementById('detailNamaHdr').textContent = siswa.nama;
            document.getElementById('detailNama').textContent = siswa.nama;
            document.getElementById('detailKelas').textContent = 'Kelas: ' + siswa.kelas;
            document.getElementById('detailNisn').textContent = 'NISN: ' + siswa.nisn;
            document.getElementById('detailTanggal').textContent = 'TTL: ' + (siswa.tanggal_lahir || '-');
            document.getElementById('detailAspekLabel').textContent = { harian: 'Aspek Harian', mingguan: 'Aspek Mingguan', pertemuan: 'Aspek Per Pertemuan', bulanan: 'Aspek Bulanan' }[periode] || 'Aspek';

            var aspek = [{ label: 'Bangun Pagi', val: siswa.detail?.bangun_pagi ?? 0 }, { label: 'Beribadah', val: siswa.detail?.beribadah ?? 0 }, { label: 'Berolahraga', val: siswa.detail?.berolahraga ?? 0 }, { label: 'Makan Sehat', val: siswa.detail?.makan_sehat ?? 0 }, { label: 'Gemar Belajar', val: siswa.detail?.gemar_belajar ?? 0 }, { label: 'Bermasyarakat', val: siswa.detail?.bermasyarakat ?? 0 }, { label: 'Tidur Cepat', val: siswa.detail?.tidur_cepat ?? 0 }];
            var html = '<div class="space-y-2">';
            aspek.forEach(function (a) {
                var c = a.val >= 80 ? 'linear-gradient(90deg,#2563eb,#4f46e5)' : a.val >= 50 ? '#60a5fa' : '#e2e8f0';
                html += '<div class="kb-row"><span class="kb-label">' + a.label + '</span><div class="kb-track"><div class="kb-fill" style="background:' + c + ';width:' + a.val + '%;"></div></div><span style="font-size:11px;color:#64748b;width:28px;text-align:right;">' + a.val + '%</span></div>';
            });
            html += '</div>';
            document.getElementById('detailAspekList').innerHTML = html;

            var persen = siswa.persen || 0;
            document.getElementById('detailProgressBar').style.width = persen + '%';
            document.getElementById('detailProgressText').textContent = persen + '% Selesai';
            document.getElementById('detailUmpanBalik').textContent = siswa.umpan_balik || '-';

            var tabSwitcher = document.getElementById('detailTabSwitcher');
            var panelStats = document.getElementById('panelStats');
            if (periode === 'harian') {
                tabSwitcher.classList.remove('hidden'); tabSwitcher.classList.add('flex');
                panelStats.classList.add('hidden');
                switchDetailTab('penyelesaian');
            } else if (['mingguan', 'pertemuan', 'bulanan'].includes(periode)) {
                tabSwitcher.classList.add('hidden'); tabSwitcher.classList.remove('flex');
                panelStats.classList.remove('hidden');
                document.getElementById('panelPenyelesaian').classList.remove('hidden');
                document.getElementById('panelDetail').classList.add('hidden');
                loadStats(siswa.id, periode);
            } else {
                tabSwitcher.classList.add('hidden'); tabSwitcher.classList.remove('flex');
                panelStats.classList.add('hidden');
                document.getElementById('panelPenyelesaian').classList.remove('hidden');
                document.getElementById('panelDetail').classList.add('hidden');
            }
            window.currentDetailSiswa = siswa;
            window.currentDetailPeriode = periode;
            bukaModal('modalDetail');
        }

        function loadStats(siswaId, periode) {
            var filter = '', endpoint = '';
            if (periode === 'mingguan') { filter = document.getElementById('select_minggu').value; endpoint = '/guru/list-murid/weekly-stats'; }
            if (periode === 'pertemuan') { filter = document.getElementById('select_pertemuan').value; endpoint = '/guru/list-murid/meeting-stats'; }
            if (periode === 'bulanan') { filter = document.getElementById('select_bulan').value; endpoint = '/guru/list-murid/monthly-stats'; }
            if (!filter) { document.getElementById('statsContent').innerHTML = '<p style="color:#94a3b8;font-size:13px;">Pilih filter terlebih dahulu.</p>'; return; }
            fetch(endpoint + '?siswa_id=' + siswaId + '&filter=' + filter)
                .then(r => r.json()).then(data => { if (data.success) displayStats(data.data, periode); else document.getElementById('statsContent').innerHTML = '<p style="color:#ef4444;font-size:13px;">' + data.message + '</p>'; })
                .catch(() => { document.getElementById('statsContent').innerHTML = '<p style="color:#ef4444;font-size:13px;">Gagal memuat statistik.</p>'; });
        }

        function displayStats(data, periode) {
            var pt = '';
            if (periode === 'mingguan') pt = 'Minggu: ' + data.periode + ' (' + data.rentang.mulai + ' s/d ' + data.rentang.selesai + ') — ' + data.total_hari + ' hari';
            if (periode === 'pertemuan') pt = 'Pertemuan: ' + data.periode + ' (' + data.rentang.mulai + ' s/d ' + data.rentang.selesai + ') — ' + data.total_hari + ' hari';
            if (periode === 'bulanan') pt = 'Bulan: ' + data.periode + ' (' + data.rentang.mulai + ' s/d ' + data.rentang.selesai + ') — ' + data.total_hari + ' hari';
            document.getElementById('statsPeriod').textContent = pt;

            var lbl = { bangun_pagi: 'Bangun Pagi', beribadah: 'Beribadah', berolahraga: 'Berolahraga', makan_sehat: 'Makan Sehat', gemar_belajar: 'Gemar Belajar', bermasyarakat: 'Bermasyarakat', tidur_cepat: 'Tidur Cepat' };
            var html = '';
            for (var key in data.statistik) {
                var s = data.statistik[key];
                html += '<div style="background:white;border:1.5px solid #e0e7ff;border-radius:10px;padding:10px;">'
                    + '<p style="font-size:12.5px;font-weight:700;color:#374151;margin-bottom:8px;">' + (lbl[key] || key) + '</p>'
                    + '<div class="stat-grid">'
                    + '<div class="stat-chip" style="background:#f0fdf4;"><p style="font-size:10px;font-weight:700;color:#16a34a;">Ya</p><p style="font-size:14px;font-weight:800;color:#15803d;">' + s.ya + '</p></div>'
                    + '<div class="stat-chip" style="background:#fff1f2;"><p style="font-size:10px;font-weight:700;color:#dc2626;">Tidak</p><p style="font-size:14px;font-weight:800;color:#b91c1c;">' + s.tidak + '</p></div>'
                    + '<div class="stat-chip" style="background:#f8fafc;"><p style="font-size:10px;font-weight:700;color:#64748b;">Kosong</p><p style="font-size:14px;font-weight:800;color:#475569;">' + s.tidak_mengisi + '</p></div>'
                    + '</div></div>';
            }
            document.getElementById('statsContent').innerHTML = html;
        }

        function switchDetailTab(tab) {
            var isP = tab === 'penyelesaian';
            document.getElementById('tabPenyelesaian').className = 'modal-tab' + (isP ? ' active' : '');
            document.getElementById('tabDetail').className = 'modal-tab' + (!isP ? ' active' : '');
            document.getElementById('panelPenyelesaian').classList.toggle('hidden', !isP);
            document.getElementById('panelDetail').classList.toggle('hidden', isP);
            if (!isP) populateDetailForm();
        }

        function populateDetailForm() {
            var siswa = window.currentDetailSiswa; if (!siswa || !siswa.kebiasaan) return;
            var k = siswa.kebiasaan;
            function yatidak(v) { return v === true ? 'Iya' : v === false ? 'Tidak' : '-'; }
            document.getElementById('detailBangunPagiStatus').textContent = yatidak(k.bangun_pagi);
            document.getElementById('detailBangunPagiJam').textContent = k.bangun_pagi_jam || '-';
            document.getElementById('detailBangunPagiCatatan').textContent = k.bangun_pagi_catatan || '-';
            var sholatEl = document.getElementById('detailBeribadahSholatList');
            sholatEl.innerHTML = '';
            if (k.beribadah_sholat_list && k.beribadah_sholat_list.length) {
                var ul = document.createElement('div'); ul.style.cssText = 'display:flex;flex-wrap:wrap;gap:4px;';
                k.beribadah_sholat_list.forEach(function (item) { var span = document.createElement('span'); span.style.cssText = 'background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:2px 7px;font-size:11px;font-weight:600;color:#1d4ed8;'; span.textContent = item.nama + (item.jam ? ' (' + item.jam + ')' : ''); ul.appendChild(span); });
                sholatEl.appendChild(ul);
            } else { sholatEl.textContent = '-'; }
            document.getElementById('detailBeribadahQuran').textContent = yatidak(k.beribadah_quran);
            document.getElementById('detailBeribadahSurah').textContent = k.beribadah_surah || '-';
            document.getElementById('detailBeribadahCatatan').textContent = k.beribadah_catatan || '-';
            document.getElementById('detailBerolahragaStatus').textContent = yatidak(k.berolahraga);
            var olEl = document.getElementById('detailBerolahragaList');
            olEl.innerHTML = '';
            if (k.berolahraga_list && k.berolahraga_list.length) { var olDiv = document.createElement('div'); olDiv.style.cssText = 'display:flex;flex-wrap:wrap;gap:4px;'; k.berolahraga_list.forEach(function (item) { var span = document.createElement('span'); span.style.cssText = 'background:#fff7ed;border:1px solid #fed7aa;border-radius:6px;padding:2px 7px;font-size:11px;font-weight:600;color:#c2410c;'; span.textContent = item.jenis; olDiv.appendChild(span); }); olEl.appendChild(olDiv); } else { olEl.textContent = '-'; }
            document.getElementById('detailBerolahragaCatatan').textContent = k.berolahraga_catatan || '-';
            document.getElementById('detailMakanSehatStatus').textContent = yatidak(k.makan_sehat);
            document.getElementById('detailMakanPagi').textContent = k.makan_pagi || '-';
            document.getElementById('detailMakanSiang').textContent = k.makan_siang || '-';
            document.getElementById('detailMakanMalam').textContent = k.makan_malam || '-';
            document.getElementById('detailMakanSehatCatatan').textContent = k.makan_catatan || '-';
            document.getElementById('detailGemarBelajarStatus').textContent = yatidak(k.gemar_belajar);
            document.getElementById('detailGemarBelajarJenis').textContent = k.gemar_belajar_jenis || '-';
            document.getElementById('detailGemarBelajarCatatan').textContent = k.gemar_belajar_catatan || '-';
            document.getElementById('detailBermasyarakatDengan').textContent = k.bermasyarakat_dengan || '-';
            document.getElementById('detailBermasyarakatCatatan').textContent = k.bermasyarakat_catatan || '-';
            document.getElementById('detailTidurCepatStatus').textContent = yatidak(k.tidur_cepat);
            document.getElementById('detailTidurCepatJam').textContent = k.tidur_cepat_jam || '-';
            document.getElementById('detailTidurCepatCatatan').textContent = k.tidur_cepat_catatan || '-';
        }

        var judulMap = { harian: 'Umpan Balik Dari Guru Wali Perhari', mingguan: 'Umpan Balik Dari Guru Wali Perminggu', pertemuan: 'Umpan Balik Dari Guru Wali Perpertemuan', bulanan: 'Umpan Balik Dari Guru Wali Perbulan' };
        function bukaPesan(siswa, periode) {
            pesanSiswaData = siswa;
            document.getElementById('pesanNamaMurid').textContent = siswa.nama;
            document.getElementById('pesanJudul').textContent = judulMap[periode] || 'Umpan Balik Dari Guru Wali';
            document.getElementById('pesanSiswaId').value = siswa.id;
            document.getElementById('pesanIsi').value = '';
            var now = new Date(); document.getElementById('pesanTanggal').textContent = now.getDate() + ' ' + NAMA_BULAN[now.getMonth()] + ' ' + now.getFullYear();
            switchTabPesan('kirim'); bukaModal('modalPesan');
        }

        function switchTabPesan(tab) {
            var isK = tab === 'kirim';
            document.getElementById('panelKirim').classList.toggle('hidden', !isK);
            document.getElementById('panelHistory').classList.toggle('hidden', isK);
            document.getElementById('tabKirim').className = 'modal-tab' + (isK ? ' active' : '');
            document.getElementById('tabHistory').className = 'modal-tab' + (!isK ? ' active' : '');
            if (!isK && pesanSiswaData) loadHistory(pesanSiswaData.id);
        }

        function loadHistory(siswaId) {
            document.getElementById('historyBody').innerHTML =
                '<tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Memuat...</td></tr>';

            fetch('/guru/list-murid/pesan-history/' + siswaId, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(function (r) {
                    return r.json();
                })
                .then(function (res) {
                    if (!res.data || !res.data.length) {
                        document.getElementById('historyBody').innerHTML =
                            '<tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">Belum ada history pesan</td></tr>';
                        return;
                    }
                    document.getElementById('historyBody').innerHTML = res.data.map(function (p) {
                        return '<tr class="border-b border-gray-100">' +
                            '<td class="px-3 py-2 font-medium text-gray-800">' + p.nama_siswa + '</td>' +
                            '<td class="px-3 py-2 text-gray-700">' + p.judul + '</td>' +
                            '<td class="px-3 py-2 text-gray-600 max-w-[160px] truncate">' + p.isi + '</td>' +
                            '<td class="px-3 py-2 text-gray-500 whitespace-nowrap">' + p.tanggal + '</td>' +
                            '</tr>';
                    }).join('');
                })
                .catch(function () {
                    document.getElementById('historyBody').innerHTML =
                        '<tr><td colspan="4" class="px-3 py-4 text-center text-red-400">Gagal memuat history</td></tr>';
                });
        }

        function kirimPesan() {
            var siswaId = document.getElementById('pesanSiswaId').value;
            var judul = document.getElementById('pesanJudul').textContent;
            var isi = document.getElementById('pesanIsi').value.trim();

            if (!isi) {
                tampilkanToast('Isi pesan tidak boleh kosong', 'red');
                return;
            }

            fetch('/guru/list-murid/kirim-pesan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    siswa_id: siswaId,
                    judul: judul,
                    isi: isi,
                    periode: currentPeriode,
                    filter: currentFilter
                }),
            })
                .then(function (r) {
                    return r.json();
                })
                .then(function (res) {
                    if (res.success) {
                        tampilkanToast('Pesan berhasil dikirim!', 'green');
                        document.getElementById('pesanIsi').value = '';
                        tutupModal('modalPesan');
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || 'Terjadi kesalahan'), 'red');
                    }
                })
                .catch(function () {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        /* ── Hapus umpan balik ────────────────────────────────── */
        function bukaHapus(id, nama, umpanId) {
            hapusSiswaId = id;
            hapusUmpanBalikId = umpanId || null;
            document.getElementById('hapusNamaSiswa').textContent = nama;
            bukaModal('modalHapus');
        }

        function konfirmasiHapus() {
            if (!hapusSiswaId) return;

            // Kirim periode + filter agar controller bisa hapus berdasarkan periode aktif
            // jika umpan_balik_id tidak tersedia
            var payload = {
                siswa_id: hapusSiswaId,
                periode: currentPeriode,
                filter: currentFilter,
            };
            // Jika ada id spesifik, sertakan juga
            if (hapusUmpanBalikId) {
                payload.umpan_balik_id = hapusUmpanBalikId;
            }

            fetch('/guru/list-murid/hapus-umpan-balik', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload),
            })
                .then(function (r) {
                    return r.json();
                })
                .then(function (res) {
                    tutupModal('modalHapus');
                    if (res.success) {
                        tampilkanToast(res.message || 'Umpan balik berhasil dihapus', 'green');
                        cariData();
                    } else {
                        tampilkanToast('Gagal: ' + (res.message || ''), 'red');
                    }
                })
                .catch(function () {
                    tampilkanToast('Gagal terhubung ke server', 'red');
                });
        }

        /* ── Download PDF ─────────────────────────────────────── */
        function downloadPDF() {
            var rows = document.querySelectorAll('#tabelBody tr:not([id])');
            if (!rows.length) {
                tampilkanToast('Tidak ada data untuk didownload', 'red');
                return;
            }

            var {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();

            doc.setFontSize(14);
            doc.text('List Murid Guru Wali', 14, 15);
            doc.setFontSize(9);
            doc.text('Periode: ' + (document.getElementById('selectPeriode').value || '-') +
                '  Filter: ' + currentFilter, 14, 22);

            var tableData = [];
            rows.forEach(function (row, i) {
                var cells = row.querySelectorAll('td');
                var persen = row.querySelector('.h-3.rounded-full + span');
                tableData.push([
                    cells[0]?.textContent.trim() || '',
                    cells[1]?.textContent.trim() || '',
                    cells[2]?.textContent.trim() || '',
                    cells[3]?.textContent.trim() || '',
                    (persen?.textContent.trim() || '0%'),
                    cells[5]?.textContent.trim() || '-',
                ]);
            });

            doc.autoTable({
                startY: 28,
                head: [
                    ['No', 'Nama', 'Kelas', 'NISN', 'Penyelesaian Form', 'Umpan Balik']
                ],
                body: tableData,
                styles: {
                    fontSize: 8,
                    cellPadding: 3
                },
                headStyles: {
                    fillColor: [37, 99, 235]
                },
            });

            doc.save('list-murid-' + (currentFilter || 'semua') + '.pdf');
        }

        /* ── Modal helpers ────────────────────────────────────── */
        function bukaModal(id) {
            var el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function tutupModal(id) {
            var el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
        document.querySelectorAll('#modalDetail,#modalPesan,#modalHapus,#modalProfil').forEach(function (m) {
            m.addEventListener('click', function (e) {
                if (e.target === this) tutupModal(this.id);
            });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') ['modalDetail', 'modalPesan', 'modalHapus', 'modalProfil'].forEach(tutupModal);
        });

        /* ── Profil Siswa Popup ───────────────────────────────── */
        var mapInstance = null;
        var mapMarker = null;

        function bukaProfil(siswaId) {
            // Fetch student profile data
            fetch('/guru/list-murid/siswa-profile/' + siswaId, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(function (r) {
                    return r.json();
                })
                .then(function (res) {
                    if (!res.success) {
                        tampilkanToast(res.message || 'Gagal memuat profil siswa', 'red');
                        return;
                    }

                    var data = res.data;

                    // Fill profile data
                    document.getElementById('profilNama').textContent = data.nama;
                    document.getElementById('profilNisn').textContent = 'NISN: ' + data.nisn;
                    document.getElementById('profilGender').textContent = data.gender;
                    document.getElementById('profilKelas').textContent = data.kelas;
                    document.getElementById('profilTtl').textContent = data.tempat_lahir + ', ' + data.tanggal_lahir;
                    document.getElementById('profilAngkatan').textContent = data.angkatan;
                    document.getElementById('profilHobi').textContent = data.hobi;
                    document.getElementById('profilCitaCita').textContent = data.cita_cita;
                    document.getElementById('profilTemanTerbaik').textContent = data.teman_terbaik;
                    document.getElementById('profilMakanan').textContent = data.makanan_kesukaan;
                    document.getElementById('profilWarna').textContent = data.warna_kesukaan;
                    document.getElementById('profilNoTelepon').textContent = data.no_telepon;
                    document.getElementById('profilNoOrtu').textContent = data.no_ortu;
                    document.getElementById('profilAlamat').textContent = data.alamat;

                    // Set foto
                    var fotoEl = document.getElementById('profilFoto');
                    var placeholderEl = document.getElementById('profilFotoPlaceholder');
                    if (data.foto) {
                        fotoEl.src = '/storage/' + data.foto;
                        fotoEl.classList.remove('hidden');
                        placeholderEl.classList.add('hidden');
                    } else {
                        fotoEl.classList.add('hidden');
                        placeholderEl.classList.remove('hidden');
                    }

                    // Simpan koordinat ke variabel global
                    currentSiswaLat = data.latitude;
                    currentSiswaLng = data.longitude;

                    // Tampilkan tombol rute jika koordinat tersedia
                    var btnRute = document.getElementById('btnDapatkanRute');
                    if (currentSiswaLat && currentSiswaLng && currentSiswaLat !== '0') {
                        btnRute.classList.remove('hidden');
                    } else {
                        btnRute.classList.add('hidden');
                    }

                    // Open modal
                    bukaModal('modalProfil');

                    // Initialize map after modal is visible
                    setTimeout(function () {
                        initMap(data.latitude, data.longitude);
                        if (typeof mapInstance !== 'undefined' && mapInstance !== null) {
                            mapInstance.invalidateSize();
                        }
                    }, 400);


                })
                .catch(function (err) {
                    console.error('Error fetching profile:', err);
                    tampilkanToast('Gagal memuat profil siswa', 'red');
                });
        }

        function bukaGoogleMapsRute() {
            // Pastikan variabel global sudah terisi angka koordinat
            if (currentSiswaLat && currentSiswaLng && currentSiswaLat !== '0') {

                // FORMAT URL YANG BENAR:
                // 'dir' untuk rute (direction), 'api=1' untuk mode modern, 'destination' untuk tujuan
                var url = "https://www.google.com/maps/search/?api=1&query=" + currentSiswaLat + "," + currentSiswaLng;

                // Membuka tab baru
                window.open(url, '_blank');

            } else {
                alert("Koordinat lokasi tidak ditemukan atau tidak valid.");
            }
        }

        function initMap(latitude, longitude) {
            var mapEl = document.getElementById('profilMap');

            mapEl.innerHTML = '';

            // Clear previous map instance if exists
            if (mapInstance) {
                mapInstance.remove();
                mapInstance = null;
            }

            var hasLocation = (latitude && longitude && latitude !== '0');
            // Default coordinates (Indonesia center) if no location data
            var lat = latitude ? parseFloat(latitude) : 5.5536;
            var lng = longitude ? parseFloat(longitude) : 95.3177;

            // Initialize Leaflet map
            mapInstance = L.map('profilMap').setView([lat, lng], hasLocation ? 15 : 15);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapInstance);

            // Add marker if location exists
            if (hasLocation) {
                L.marker([lat, lng]).addTo(mapInstance).bindPopup('Lokasi Rumah Siswa').openPopup();
            } else {
                L.popup()
                    .setLatLng([lat, lng])
                    .setContent('<p class="text-gray-500 text-xs">Lokasi rumah belum diatur oleh siswa.</p>')
                    .openOn(mapInstance);
            }
            // SOLUSI UTAMA: Paksa Leaflet menghitung ulang ukuran setelah modal terbuka
            setTimeout(function () {
                if (mapInstance) {
                    mapInstance.invalidateSize();
                }
            }, 400); // Jeda 400ms memastikan animasi modal selesai
        }

        /* ── Toast ────────────────────────────────────────────── */
        function tampilkanToast(pesan, warna) {
            var toast = document.getElementById('toast');
            toast.classList.remove('bg-green-600', 'bg-red-600');
            toast.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
            document.getElementById('toastMsg').textContent = pesan;
            toast.classList.remove('opacity-0', '-translate-y-2', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(function () {
                toast.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 3000);
        }

        /* ── Load all students on page load ───────────────────── */
        document.addEventListener('DOMContentLoaded', function () {
            // Load all students without period filter
            cariData();
        });
    </script>

@endsection