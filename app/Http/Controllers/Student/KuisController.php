<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\JawabanKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user();

        $kuisList = Kuis::where('waktu_mulai', '<=', now())
            ->orWhere('waktu_mulai', '>', now())
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        $kuisList = $kuisList->map(function ($kuis) use ($siswa) {
            /** @var \App\Models\Kuis $kuis */
            $jawaban = JawabanKuis::where('kuis_id', $kuis->id)
                ->where('siswa_id', $siswa->id)
                ->first();

            $status = 'belum_dikerjakan';

            if (!$kuis->isSudahDibuka()) {
                $status = 'belum_dibuka';
            } elseif ($jawaban) {
                $status = $jawaban->status;
            } elseif (
                now()->greaterThan($kuis->waktu_mulai->copy()->addMinutes($kuis->durasi_menit))
                && $kuis->durasi_menit
            ) {
                // belum pernah dikerjakan tapi sudah lewat (untuk kuis tanpa start-per-siswa)
                $status = 'kadaluarsa';
            }

            $kuis->status_siswa = $status;
            $kuis->jawaban_record = $jawaban;
            return $kuis;
        });

        return view('student.kuis.index', [
            'kuisAktif' => $kuisList->filter(fn($k) => in_array($k->status_siswa, ['sedang_berlangsung', 'belum_dikerjakan']) && $k->isSudahDibuka()),
            'kuisSelesai' => $kuisList->filter(fn($k) => $k->status_siswa === 'sudah_dikerjakan'),
            'kuisKadaluarsa' => $kuisList->filter(fn($k) => $k->status_siswa === 'kadaluarsa'),
            'kuisTerjadwal' => $kuisList->filter(fn($k) => $k->status_siswa === 'belum_dibuka'),
        ]);
    }

    public function show($id)
    {
        $kuis = Kuis::findOrFail($id);
        $siswa = Auth::user();

        if (!$kuis->isSudahDibuka()) {
            return redirect()->route('student.kuis')->with('error', 'Kuis belum dibuka.');
        }

        $jawaban = JawabanKuis::firstOrCreate(
            ['kuis_id' => $kuis->id, 'siswa_id' => $siswa->id],
            []
        );

        // Jika belum pernah mulai, set waktu mulai sekarang (timer dimulai)
        if (!$jawaban->mulai_dikerjakan && !$jawaban->waktu_kirim) {
            $jawaban->mulai_dikerjakan = now();
            $jawaban->save();
        }

        // Jika sudah submit, tampilkan hasil (read-only)
        if ($jawaban->waktu_kirim) {
            return view('student.kuis.show', compact('kuis', 'jawaban'))
                ->with('readonly', true);
        }

        // Jika sudah lewat deadline tanpa submit
        if ($jawaban->isKadaluarsa()) {
            return redirect()->route('student.kuis')->with('error', 'Waktu pengerjaan kuis ini sudah berakhir.');
        }

        return view('student.kuis.show', compact('kuis', 'jawaban'))
            ->with('readonly', false);
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|string|min:10',
        ]);

        $kuis = Kuis::findOrFail($id);
        $siswa = Auth::user();

        $jawaban = JawabanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($jawaban->waktu_kirim) {
            return redirect()->route('student.kuis')->with('error', 'Kuis sudah pernah dikirim.');
        }

        if ($jawaban->isKadaluarsa()) {
            return redirect()->route('student.kuis')->with('error', 'Waktu pengerjaan sudah berakhir, jawaban tidak dapat dikirim.');
        }

        $jawaban->update([
            'jawaban' => $request->jawaban,
            'waktu_kirim' => now(),
        ]);

        return redirect()->route('student.kuis')->with('success', 'Jawaban berhasil dikirim.');
    }

    // Endpoint untuk update timer realtime (opsional, dipanggil via AJAX)
    public function checkStatus($id)
    {
        $siswa = Auth::user();
        $jawaban = JawabanKuis::where('kuis_id', $id)->where('siswa_id', $siswa->id)->first();

        if (!$jawaban || !$jawaban->mulai_dikerjakan) {
            return response()->json(['expired' => false, 'remaining' => null]);
        }

        $deadline = $jawaban->deadline;
        $remaining = now()->diffInSeconds($deadline, false);

        return response()->json([
            'expired' => $remaining <= 0 && !$jawaban->waktu_kirim,
            'remaining' => max(0, $remaining),
        ]);
    }
}