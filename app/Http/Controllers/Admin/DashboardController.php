<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KebiasaanHarian;
use App\Models\PesanBantuan;
use App\Models\User;
use App\Models\Guru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Total Siswa aktif ─────────────────────────────────────────────
        // Siswa = user yang punya NISN, belum alumni, dan masih punya kelas
        $totalSiswa = User::whereNotNull('nisn')
            ->where('is_alumni', false)
            ->whereNotNull('kelas_id')
            ->count();

        // ── Total Guru aktif ──────────────────────────────────────────────
        // Guru = ada record di tabel guru
        $totalGuru = Guru::count();

        // ── Total Alumni ──────────────────────────────────────────────────
        // Alumni = user dengan is_alumni = true
        $totalAlumni = User::where('is_alumni', true)->count();

        // Auto-update alumni status for students who should be alumni
        $this->updateAlumniStatus();

        // ── Total pengisian kebiasaan bulan ini ───────────────────────────
        $totalKebiasaanBulanIni = KebiasaanHarian::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();

        // ── Total pesan bantuan hari ini ──────────────────────────────────
        $totalPesanHariIni = PesanBantuan::whereDate('created_at', today())->count();

        // ── Pesan bantuan terbaru (5) ─────────────────────────────────────
        $pesanBantuanTerbaru = PesanBantuan::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalAlumni',
            'totalKebiasaanBulanIni',
            'totalPesanHariIni',
            'pesanBantuanTerbaru'
        ));
    }

    /**
     * Auto-update alumni status for students who have been enrolled for 3 years
     */
    private function updateAlumniStatus(): void
    {
        $students = User::whereNotNull('nisn')
            ->where('is_alumni', false)
            ->whereNotNull('tanggal_masuk')
            ->get();

        foreach ($students as $student) {
            if ($student->shouldBeAlumni()) {
                $student->update(['is_alumni' => true, 'kelas_id' => null]);
            }
        }
    }

    public function profil(): View
    {
        $user = Auth::user();
        return view('admin.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'no_telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'nip', 'email', 'tempat_lahir', 'birth_date', 'no_telepon']);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('profile-photos', 'public');
            $data['foto'] = $fotoPath;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function kebiasaan(Request $request): View
    {
        // Ambil data guru untuk dropdown
        $guruList = Guru::with('user')->get();

        // Default tanggal hari ini
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        // Query data kebiasaan
        $query = KebiasaanHarian::with(['user.kelas', 'user.waliKelas.user'])
            ->whereDate('tanggal', $tanggal);

        // Filter berdasarkan guru wali
        if ($request->filled('guru_wali_id')) {
            $guruWaliId = $request->input('guru_wali_id');
            $query->whereHas('user', function ($q) use ($guruWaliId) {
                $q->where('guru_wali_id', $guruWaliId);
            });
        }

        // Search nama siswa
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Pagination
        $kebiasaan = $query->paginate(10);

        return view('admin.kebiasaan', compact('guruList', 'tanggal', 'kebiasaan'));
    }

    public function pengaturan(): View
    {
        $user = Auth::user();
        return view('admin.pengaturan', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}