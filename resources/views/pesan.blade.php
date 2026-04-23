@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pesan Guru Wali')

@section('content')

<div class="min-h-screen">

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">Pesan Guru Wali</h2>
            @if ($belumDibaca > 0)
                <p class="text-sm text-gray-600 mt-1">
                    <span class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-full px-3 py-1">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                        <span class="font-semibold text-blue-700">{{ $belumDibaca }} pesan belum dibaca</span>
                    </span>
                </p>
            @else
                <p class="text-sm text-gray-500 mt-1">Semua pesan sudah dibaca</p>
            @endif
        </div>
    </div>

    {{-- ===== DAFTAR PESAN ===== --}}
    <div class="space-y-3">

        @forelse ($pesanList as $pesan)
            @php
                $sudahDibaca = $pesan->reads->isNotEmpty();
            @endphp

            <div
                onclick="bukaPesan({{ $pesan->id }}, this)"
                data-id="{{ $pesan->id }}"
                data-dibaca="{{ $sudahDibaca ? '1' : '0' }}"
                class="pesan-item flex items-center gap-4 w-full bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl px-5 py-4
                       cursor-pointer transition-all duration-200 select-none shadow-md hover:shadow-lg hover:scale-[1.01]
                       {{ $sudahDibaca
                          ? 'opacity-60 hover:opacity-80'
                          : '' }}">

                {{-- Ikon pesan --}}
                <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center shadow-md
                            {{ $sudahDibaca ? 'bg-gray-200' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }}">
                    <svg class="w-6 h-6 {{ $sudahDibaca ? 'text-gray-500' : 'text-white' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                {{-- Judul + guru --}}
                <div class="flex-1 min-w-0">
                    <p class="text-base font-semibold truncate
                               {{ $sudahDibaca ? 'text-gray-500' : 'text-gray-900' }}">
                        {{ $pesan->judul }}
                    </p>
                    <p class="text-sm mt-0.5 {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $pesan->guru->name ?? 'Guru Wali' }}
                    </p>
                </div>

                {{-- Waktu + badge unread --}}
                <div class="flex-shrink-0 flex flex-col items-end gap-1">
                    <span class="text-xs {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $pesan->waktuRelatif() }}
                    </span>
                    @if (!$sudahDibaca)
                        <span class="unread-dot w-2.5 h-2.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-md"></span>
                    @endif
                </div>
            </div>

        @empty
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center mb-5 shadow-md">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0
                                 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-700">Belum ada pesan dari guru wali</p>
                <p class="text-sm text-gray-500 mt-1">Pesan akan muncul di sini saat guru wali mengirim pesan</p>
            </div>
        @endforelse

    </div>

    {{-- ===== PAGINATION ===== --}}
    @if ($pesanList->hasPages())
        <div class="mt-6">
            {{ $pesanList->links() }}
        </div>
    @endif

</div>{{-- end page --}}


{{-- ===== MODAL BACA PESAN ===== --}}
<div id="modalPesan"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4"
     onclick="tutupModal(event)">
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden border border-blue-100"
         onclick="event.stopPropagation()">

        {{-- Modal header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 pt-5 pb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1 min-w-0 pr-4">
                    <p id="modalJudul"
                       class="text-base font-bold text-white leading-snug"></p>
                    <div class="flex items-center gap-3 mt-1">
                        <p id="modalGuru" class="text-xs text-blue-100"></p>
                        <span class="text-blue-200">·</span>
                        <p id="modalWaktu" class="text-xs text-blue-200"></p>
                    </div>
                </div>
                <button onclick="tutupModal()"
                        class="flex-shrink-0 p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-1 bg-gradient-to-r from-blue-200 to-indigo-200"></div>

        {{-- Isi pesan --}}
        <div class="px-6 py-5">
            <div id="modalLoading"
                 class="flex items-center justify-center py-8 hidden">
                <svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </div>
            <div id="modalIsi"
                 class="text-sm text-gray-700 leading-relaxed border-2 border-blue-100
                        rounded-2xl px-5 py-4 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-[120px] whitespace-pre-wrap shadow-inner"></div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5 flex justify-center">
            <button onclick="tutupModal()"
                    class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-sm
                           font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                Tutup
            </button>
        </div>

    </div>
</div>


<script>
    /* ─────────────────────────────────────────────────────────────────────────
       Buka pesan: fetch isi + tandai dibaca
    ───────────────────────────────────────────────────────────────────────── */
    function bukaPesan(id, elRow) {
        // Tampilkan modal + loading
        const modal = document.getElementById('modalPesan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('modalJudul').textContent  = '';
        document.getElementById('modalGuru').textContent   = '';
        document.getElementById('modalWaktu').textContent  = '';
        document.getElementById('modalIsi').textContent    = '';
        document.getElementById('modalLoading').classList.remove('hidden');
        document.getElementById('modalIsi').classList.add('hidden');

        // Fetch + tandai dibaca
        fetch(`/student/pesan/${id}/baca`, {
            method : 'POST',
            headers: {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                'Accept'       : 'application/json',
                'Content-Type' : 'application/json',
            },
        })
        .then(r => r.json())
        .then(res => {
            if (! res.success) return;

            const p = res.pesan;

            document.getElementById('modalJudul').textContent = p.judul;
            document.getElementById('modalGuru').textContent  = p.guru;
            document.getElementById('modalWaktu').textContent = p.dibuat;
            document.getElementById('modalIsi').textContent   = p.isi;

            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalIsi').classList.remove('hidden');

            // Update UI baris pesan → jadi abu-abu (sudah dibaca)
            if (elRow && elRow.dataset.dibaca === '0') {
                elRow.dataset.dibaca = '1';
                tandaiSudahDibaca(elRow);
                kurangiCounterBelumDibaca();
            }
        })
        .catch(() => {
            document.getElementById('modalLoading').classList.add('hidden');
            document.getElementById('modalIsi').textContent = 'Gagal memuat pesan. Coba lagi.';
            document.getElementById('modalIsi').classList.remove('hidden');
        });
    }

    /* ─────────────────────────────────────────────────────────────────────────
       Update tampilan baris → abu-abu setelah dibaca
    ───────────────────────────────────────────────────────────────────────── */
    function tandaiSudahDibaca(elRow) {
        // Opacity
        elRow.classList.add('opacity-60', 'hover:opacity-80');

        // Ikon
        const icon = elRow.querySelector('svg');
        if (icon) {
            icon.classList.remove('text-white');
            icon.classList.add('text-gray-500');
        }
        const iconWrap = elRow.querySelector('.flex-shrink-0');
        if (iconWrap) {
            iconWrap.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600');
            iconWrap.classList.add('bg-gray-200');
        }

        // Teks judul
        const judul = elRow.querySelector('.text-base.font-semibold');
        if (judul) {
            judul.classList.remove('text-gray-900');
            judul.classList.add('text-gray-500');
        }

        // Teks guru & waktu
        elRow.querySelectorAll('.text-sm').forEach(el => {
            el.classList.remove('text-gray-600');
            el.classList.add('text-gray-400');
        });

        // Hapus dot biru
        const dot = elRow.querySelector('.unread-dot');
        if (dot) dot.remove();
    }

    /* ─────────────────────────────────────────────────────────────────────────
       Kurangi counter "X pesan belum dibaca"
    ───────────────────────────────────────────────────────────────────────── */
    let belumDibacaCount = {{ $belumDibaca }};

    function kurangiCounterBelumDibaca() {
        belumDibacaCount = Math.max(0, belumDibacaCount - 1);

        const badge = document.getElementById('unreadBadge');
        if (! badge) return;

        if (belumDibacaCount <= 0) {
            badge.textContent = 'Semua pesan sudah dibaca';
            // Sembunyikan dot biru di badge
            const dot = badge.previousElementSibling;
            if (dot) dot.remove();
        } else {
            badge.textContent = belumDibacaCount + ' pesan belum dibaca';
        }
    }

    /* ─────────────────────────────────────────────────────────────────────────
       Tutup modal
    ───────────────────────────────────────────────────────────────────────── */
    function tutupModal(event) {
        if (event && event.target !== document.getElementById('modalPesan') && event.type === 'click') {
            return;
        }
        const modal = document.getElementById('modalPesan');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Tutup dengan ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') tutupModal();
    });
</script>

@endsection
