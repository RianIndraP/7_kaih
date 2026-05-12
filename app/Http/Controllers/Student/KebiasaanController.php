<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKebiasaanRequest;
use App\Models\KebiasaanHarian;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KebiasaanController extends Controller
{
    public function index(): View
    {
        $user      = Auth::user();
        $tanggal = now('Asia/Jakarta')->toDateString();
        $kebiasaan = KebiasaanHarian::firstOrNew(
            ['user_id' => $user->id, 'tanggal' => $tanggal]
        );
        $riwayat = KebiasaanHarian::where('user_id', $user->id)
            ->orderByDesc('tanggal')->take(7)->get();

        return view('kebiasaan', compact('user', 'kebiasaan', 'tanggal', 'riwayat'));
    }

    public function store(StoreKebiasaanRequest $request): JsonResponse
    {
        $user      = Auth::user();
        $tanggal   = $request->input('tanggal', Carbon::today()->toDateString());
        $section   = $request->input('section');

        $kebiasaan = KebiasaanHarian::firstOrNew(
            ['user_id' => $user->id, 'tanggal' => $tanggal]
        );
        if (! $kebiasaan->exists) {
            $kebiasaan->user_id = $user->id;
        }

        match ($section) {
            'bangun_pagi'   => $this->fillBangunPagi($kebiasaan, $request),
            'beribadah'     => $this->fillBeribadah($kebiasaan, $request),
            'berolahraga'   => $this->fillBerolahraga($kebiasaan, $request),
            'makan_sehat'   => $this->fillMakanSehat($kebiasaan, $request),
            'gemar_belajar' => $this->fillGemarBelajar($kebiasaan, $request),
            'bermasyarakat' => $this->fillBermasyarakat($kebiasaan, $request),
            'tidur_cepat'   => $this->fillTidurCepat($kebiasaan, $request),
            default         => null,
        };

        $kebiasaan->save();

        // Update streak setelah menyimpan kebiasaan
        $user->updateStreak();

        return response()->json([
            'success'   => true,
            'message'   => 'Data ' . str_replace('_', ' ', $section) . ' berhasil disimpan.',
            'persen'    => $kebiasaan->persentaseSelesai(),
            'checklist' => $kebiasaan->statusChecklist(),
            'streak'    => $user->getStreakDisplay(),
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $user      = Auth::user();
        $tanggal   = $request->query('tanggal', Carbon::today()->toDateString());
        $kebiasaan = KebiasaanHarian::where('user_id', $user->id)
            ->where('tanggal', $tanggal)->first();

        return response()->json([
            'success' => true,
            'data'    => $kebiasaan,
            'persen'  => $kebiasaan?->persentaseSelesai() ?? 0,
        ]);
    }

    public function riwayat(Request $request): JsonResponse
    {
        $user  = Auth::user();
        $bulan = $request->query('bulan', Carbon::today()->month);
        $tahun = $request->query('tahun', Carbon::today()->year);

        $data = KebiasaanHarian::where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')->get()
            ->map(fn ($k) => [
                'tanggal' => $k->tanggal->toDateString(),
                'persen'  => $k->persentaseSelesai(),
                'selesai' => $k->hitungSelesai(),
            ]);

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function fillBangunPagi(KebiasaanHarian $k, Request $r): void
    {
        $iya = $r->input('status') === 'iya';
        $k->bangun_pagi    = $iya;
        $k->jam_bangun     = $iya ? ($r->input('jam') ?: null) : null;
        $k->bangun_catatan = $r->input('catatan') ?: null;
    }

    private function fillBeribadah(KebiasaanHarian $k, Request $r): void
    {
        /*
         * JS mengirim sholat sebagai OBJECT:
         *   { subuh: 1, dzuhur: 0, ashar: 1, maghrib: 1, isya: 0 }
         * bukan array string ["subuh","ashar"].
         * Kita ambil tiap key langsung dari request.
         */
        $sholat = (array) $r->input('sholat', []);

        foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $w) {
            $checked = ! empty($sholat[$w]);
            $k->{'sholat_' . $w}     = $checked;
            $k->{'jam_sholat_' . $w} = $checked ? ($r->input('jam_' . $w) ?: null) : null;
        }

        $iya = $r->input('quran') === 'iya';
        $k->baca_quran     = $iya;
        $k->quran_surah    = $iya ? ($r->input('surah') ?: null) : null;
        $k->ibadah_catatan = $r->input('catatan') ?: null;
    }

    private function fillBerolahraga(KebiasaanHarian $k, Request $r): void
    {
        $iya = $r->input('status') === 'iya';
        $k->berolahraga = $iya;

        if ($iya) {
            /*
             * JS mengirim jenis sebagai ARRAY OF OBJECTS:
             *   [ {jenis: 'lari', catatan: '...'}, {jenis: 'renang', catatan: '...'} ]
             * Simpan langsung sebagai JSON array.
             */
            $jenis = collect((array) $r->input('jenis', []))
                ->filter(fn ($item) => ! empty($item['jenis'] ?? $item))
                ->values()
                ->toArray();

            $k->jenis_olahraga   = $jenis;
            $k->olahraga_catatan = null;
        } else {
            $k->jenis_olahraga   = null;
            $k->olahraga_catatan = $r->input('catatan') ?: null;
        }
    }

    private function fillMakanSehat(KebiasaanHarian $k, Request $r): void
    {
        $iya = $r->input('status') === 'iya';
        $k->makan_sehat      = $iya;
        $k->makan_pagi       = $r->input('pagi') ?: null;
        $k->makan_pagi_done  = (bool) $r->input('pagi_done', false);
        $k->makan_siang      = $r->input('siang') ?: null;
        $k->makan_siang_done = (bool) $r->input('siang_done', false);
        $k->makan_malam      = $r->input('malam') ?: null;
        $k->makan_malam_done = (bool) $r->input('malam_done', false);
        $k->makan_catatan    = $r->input('catatan') ?: null;
    }

    private function fillGemarBelajar(KebiasaanHarian $k, Request $r): void
    {
        $iya = $r->input('status') === 'iya';
        $k->gemar_belajar   = $iya;
        $k->materi_belajar  = $iya ? ($r->input('pelajaran') ?: null) : null;
        $k->belajar_catatan = $r->input('catatan') ?: null;
    }

    private function fillBermasyarakat(KebiasaanHarian $k, Request $r): void
    {
        $k->bersama            = array_values(array_filter((array) $r->input('dengan', [])));
        $k->masyarakat_catatan = $r->input('catatan') ?: null;
    }

    private function fillTidurCepat(KebiasaanHarian $k, Request $r): void
    {
        $iya = $r->input('status') === 'iya';
        $k->tidur_cepat   = $iya;
        $k->jam_tidur     = $iya ? ($r->input('jam') ?: null) : null;
        $k->tidur_catatan = $r->input('catatan') ?: null;
    }
}