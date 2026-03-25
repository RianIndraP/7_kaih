<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PesanGuru;
use App\Models\PesanGuruRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesanController extends Controller
{
    // ── Halaman daftar pesan ────────────────────────────────────────────────

    public function index(): View
    {
        $user = Auth::user();

        // Ambil semua pesan untuk siswa ini, urutkan terbaru
        $pesanList = PesanGuru::with(['guru', 'reads' => function ($q) use ($user) {
                $q->where('siswa_id', $user->id);
            }])
            ->where('siswa_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        // Hitung pesan belum dibaca
        $belumDibaca = PesanGuru::where('siswa_id', $user->id)
            ->whereDoesntHave('reads', fn ($q) => $q->where('siswa_id', $user->id))
            ->count();

        return view('pesan', compact('user', 'pesanList', 'belumDibaca'));
    }

    // ── Tandai dibaca + kembalikan isi pesan (AJAX) ─────────────────────────

    public function baca(int $id): JsonResponse
    {
        $user  = Auth::user();
        $pesan = PesanGuru::where('id', $id)
            ->where('siswa_id', $user->id)
            ->firstOrFail();

        // Upsert: tandai dibaca jika belum
        PesanGuruRead::firstOrCreate(
            ['pesan_id' => $pesan->id, 'siswa_id' => $user->id],
            ['dibaca_at' => now()]
        );

        return response()->json([
            'success' => true,
            'pesan'   => [
                'id'     => $pesan->id,
                'judul'  => $pesan->judul,
                'isi'    => $pesan->isi,
                'waktu'  => $pesan->waktuRelatif(),
                'guru'   => $pesan->guru->name ?? 'Guru Wali',
                'dibuat' => $pesan->created_at->translatedFormat('d F Y, H:i'),
            ],
        ]);
    }

    // ── Jumlah pesan belum dibaca (AJAX, untuk badge di topbar) ────────────

    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();

        $count = PesanGuru::where('siswa_id', $user->id)
            ->whereDoesntHave('reads', fn ($q) => $q->where('siswa_id', $user->id))
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }
}
