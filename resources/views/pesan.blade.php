@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | Pesan Guru Wali')

@section('content')

<div class="p-6 bg-gray-50 min-h-screen">

    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Pesan guru wali</h2>
            @if ($belumDibaca > 0)
                <p class="text-xs text-gray-500 mt-0.5">
                    <span class="inline-flex items-center gap-1">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        {{ $belumDibaca }} pesan belum dibaca
                    </span>
                </p>
            @else
                <p class="text-xs text-gray-500 mt-0.5">Semua pesan sudah dibaca</p>
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
                class="pesan-item flex items-center gap-4 w-full bg-white border rounded-xl px-5 py-4
                       cursor-pointer transition-all duration-200 select-none
                       {{ $sudahDibaca
                          ? 'border-gray-200 opacity-60 hover:opacity-80'
                          : 'border-gray-300 shadow-sm hover:shadow-md hover:border-blue-300' }}">

                {{-- Ikon pesan --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center
                            {{ $sudahDibaca ? 'bg-gray-100' : 'bg-blue-50' }}">
                    <svg class="w-5 h-5 {{ $sudahDibaca ? 'text-gray-400' : 'text-blue-500' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                {{-- Judul + guru --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate
                               {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-800' }}">
                        {{ $pesan->judul }}
                    </p>
                    <p class="text-xs mt-0.5 {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-500' }}">
                        {{ $pesan->guru->name ?? 'Guru Wali' }}
                    </p>
                </div>

                {{-- Waktu + badge unread --}}
                <div class="flex-shrink-0 flex flex-col items-end gap-1">
                    <span class="text-xs {{ $sudahDibaca ? 'text-gray-400' : 'text-gray-500' }}">
                        {{ $pesan->waktuRelatif() }}
                    </span>
                    @if (!$sudahDibaca)
                        <span class="unread-dot w-2 h-2 bg-blue-500 rounded-full"></span>
                    @endif
                </div>
            </div>

        @empty
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0
                                 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600">Belum ada pesan dari guru wali</p>
                <p class="text-xs text-gray-400 mt-1">Pesan akan muncul di sini saat guru wali mengirim pesan</p>
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
     class="fixed inset-0 bg-black/50 z-[9999] hidden items-center justify-center p-4"
     onclick="tutupModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden"
         onclick="event.stopPropagation()">

        {{-- Modal header --}}
        <div class="flex items-start justify-between px-6 pt-5 pb-3">
            <div class="flex-1 min-w-0 pr-4">
                <p id="modalJudul"
                   class="text-sm font-semibold text-gray-900 leading-snug"></p>
                <div class="flex items-center gap-3 mt-1">
                    <p id="modalGuru" class="text-xs text-gray-500"></p>
                    <span class="text-gray-300">·</span>
                    <p id="modalWaktu" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="tutupModal()"
                    class="flex-shrink-0 p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Divider --}}
        <div class="mx-6 border-t border-dashed border-gray-200"></div>

        {{-- Isi pesan --}}
        <div class="px-6 py-4">
            <div id="modalLoading"
                 class="flex items-center justify-center py-8 hidden">
                <svg class="w-6 h-6 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </div>
            <div id="modalIsi"
                 class="text-sm text-gray-700 leading-relaxed border border-dashed border-gray-300
                        rounded-xl px-4 py-4 bg-gray-50 min-h-[120px] whitespace-pre-wrap"></div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5 flex justify-center">
            <button onclick="tutupModal()"
                    class="px-8 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm
                           font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
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
        // Border & opacity
        elRow.classList.remove('border-gray-300', 'shadow-sm', 'hover:shadow-md', 'hover:border-blue-300');
        elRow.classList.add('border-gray-200', 'opacity-60', 'hover:opacity-80');

        // Ikon
        const icon = elRow.querySelector('svg');
        if (icon) {
            icon.classList.remove('text-blue-500');
            icon.classList.add('text-gray-400');
        }
        const iconWrap = elRow.querySelector('.w-9');
        if (iconWrap) {
            iconWrap.classList.remove('bg-blue-50');
            iconWrap.classList.add('bg-gray-100');
        }

        // Teks judul
        const judul = elRow.querySelector('.text-sm.font-medium');
        if (judul) {
            judul.classList.remove('text-gray-800');
            judul.classList.add('text-gray-400');
        }

        // Teks guru & waktu
        elRow.querySelectorAll('.text-xs').forEach(el => {
            el.classList.remove('text-gray-500');
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
