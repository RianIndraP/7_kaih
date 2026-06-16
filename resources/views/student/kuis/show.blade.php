@extends('layouts.app')

@section('title', $kuis->judul . ' | SMK N 5 Telkom')
@section('page_title', $kuis->judul)

@section('content')
    <div class="space-y-5 max-w-3xl mx-auto" oncontextmenu="return false">

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        <div
            class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between flex-wrap gap-3">
            <div>
                <span
                    class="inline-block text-xs font-medium px-2 py-0.5 rounded {{ $kuis->kategori === 'literasi' ? 'bg-teal-50 text-teal-700' : 'bg-purple-50 text-purple-700' }} mb-1">
                    {{ ucfirst($kuis->kategori) }}
                </span>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kuis->tema }}</h2>
            </div>
            @if(!($readonly ?? false))
                <div id="timerBox"
                    class="flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg bg-amber-50 text-amber-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <span id="timerText">--:--</span>
                </div>
            @else
                <span class="text-sm font-medium px-3 py-1.5 rounded-full bg-blue-50 text-blue-700">Sudah dikirim</span>
            @endif
        </div>

        {{-- Bacaan PDF dalam bentuk gambar, tidak bisa dicopy --}}
        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 select-none" style="user-select:none;-webkit-user-select:none;">
            @for($i = 1; $i <= $kuis->jumlah_halaman_pdf; $i++)
                @php
                    $imgPath = 'kuis-pages/' . pathinfo($kuis->file_pdf, PATHINFO_FILENAME) . "/page-{$i}.jpg";
                @endphp
                <div class="bg-white rounded-lg border border-gray-200 p-3 mb-3">
                    <img src="{{ Storage::url($imgPath) }}" alt="Halaman {{ $i }}" draggable="false"
                        class="w-full rounded pointer-events-none select-none" style="-webkit-user-drag:none;user-select:none;">
                </div>
            @endfor
            <p class="text-center text-xs text-gray-400 mt-2">Konten dilindungi — tidak dapat disalin atau diunduh</p>
        </div>

        {{-- Soal --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs text-gray-500 mb-2">Soal</p>
            <p class="text-sm font-medium text-gray-900 mb-4 leading-relaxed">{{ $kuis->soal }}</p>

            @if($readonly ?? false)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line">
                    {{ $jawaban->jawaban }}
                </div>
                <p class="text-xs text-gray-400 mt-2">Dikirim pada {{ $jawaban->waktu_kirim->format('d M Y, H:i') }}</p>
            @else
                <form action="{{ route('student.kuis.submit', $kuis->id) }}" method="POST" id="quizForm">
                    @csrf
                    <textarea name="jawaban" id="jawabanInput" rows="6" required minlength="10"
                        placeholder="Tulis kesimpulanmu di sini..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        oninput="document.getElementById('wordCount').textContent = this.value.trim() ? this.value.trim().split(/\s+/).length : 0">{{ $jawaban->jawaban }}</textarea>
                    <p class="text-xs text-gray-400 mt-1 text-right"><span id="wordCount">0</span> kata</p>
                    <div class="flex justify-end mt-3">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            Kirim Jawaban
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if(!($readonly ?? false))
        <script>
            const deadline = new Date("{{ $jawaban->mulai_dikerjakan->copy()->addMinutes($kuis->durasi_menit)->toIso8601String() }}");

            function updateTimer() {
                const now = new Date();
                let diff = Math.floor((deadline - now) / 1000);

                if (diff <= 0) {
                    document.getElementById('timerText').textContent = '00:00';
                    document.getElementById('timerBox').classList.add('bg-red-50', 'text-red-700');
                    document.getElementById('quizForm')?.querySelector('button[type=submit]')?.setAttribute('disabled', 'true');
                    alert('Waktu pengerjaan telah berakhir. Halaman akan dimuat ulang.');
                    window.location.reload();
                    return;
                }

                const m = Math.floor(diff / 60);
                const s = diff % 60;
                document.getElementById('timerText').textContent =
                    String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

                if (diff <= 300) {
                    document.getElementById('timerBox').classList.remove('bg-amber-50', 'text-amber-700');
                    document.getElementById('timerBox').classList.add('bg-red-50', 'text-red-700');
                }
            }

            updateTimer();
            setInterval(updateTimer, 1000);

            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && ['c', 'x', 'u', 's', 'p'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                }
            });
        </script>
    @endif
@endsection