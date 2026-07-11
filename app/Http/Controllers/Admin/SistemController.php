<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $guruList = Guru::with('user')->get();

        $query = User::whereNotNull('nisn')
            ->with(['kelas', 'waliKelas.user'])
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('status')) {
            $query->where('status_akademik', $request->status);
        } else {
            $query->where('is_alumni', 0);
        }

        $siswaList = $query->paginate(30)->withQueryString();

        $kepalaSekolah = Guru::with('user')
            ->whereHas('user', fn($q) => $q->where('nip', '!=', null))
            ->where('status_pegawai', 'Kepala Sekolah')
            ->first();

        $tahunAjaran = \App\Models\PengaturanSistem::getValue('tahun_ajaran', date('Y') . ' / ' . (date('Y') + 1));
        $mulaiTahunBaru = \App\Models\PengaturanSistem::getValue('mulai_tahun_baru', '');

        $totalSiswaAktif = User::whereNotNull('nisn')->where('is_alumni', 0)->where('status_akademik', 'aktif')->count();
        $totalTinggalKelas = User::whereNotNull('nisn')->where('status_akademik', 'tinggal_kelas')->count();
        $totalDikeluarkan = User::whereNotNull('nisn')->where('status_akademik', 'dikeluarkan')->count();

        return view('admin.sistem.index', compact(
            'kelasList',
            'guruList',
            'siswaList',
            'kepalaSekolah',
            'tahunAjaran',
            'mulaiTahunBaru',
            'totalSiswaAktif',
            'totalTinggalKelas',
            'totalDikeluarkan'
        ));
    }

    public function updateTahunAjaran(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'mulai_tahun_baru' => 'required|date',
        ]);

        \App\Models\PengaturanSistem::setValue('tahun_ajaran', $request->tahun_ajaran);
        \App\Models\PengaturanSistem::setValue('mulai_tahun_baru', $request->mulai_tahun_baru);

        return back()->with('success', 'Pengaturan tahun ajaran disimpan.');
    }

    public function tandaiPengecualian(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'status' => 'required|in:tinggal_kelas,dikeluarkan,pindah_sekolah',
        ]);

        $siswa = User::findOrFail($request->siswa_id);
        $siswa->update(['status_akademik' => $request->status]);

        $label = match ($request->status) {
            'tinggal_kelas' => 'ditandai tinggal kelas',
            'dikeluarkan' => 'dikeluarkan dari sekolah',
            'pindah_sekolah' => 'ditandai pindah sekolah',
        };

        return back()->with('success', "{$siswa->name} berhasil {$label}.");
    }

    public function batalkanPengecualian(Request $request)
    {
        $request->validate(['siswa_id' => 'required|exists:users,id']);

        $siswa = User::findOrFail($request->siswa_id);
        $siswa->update(['status_akademik' => 'aktif']);

        return back()->with('success', "Status {$siswa->name} dikembalikan ke aktif.");
    }

    public function keluarkanSiswa(Request $request)
    {
        $request->validate(['siswa_id' => 'required|exists:users,id']);

        $siswa = User::findOrFail($request->siswa_id);
        $siswa->update(['status_akademik' => 'dikeluarkan']);

        return back()->with('success', "{$siswa->name} telah dikeluarkan dari sekolah.");
    }

    public function prosesKenaikanKelas(Request $request)
    {
        // Konfirmasi wajib diisi
        $request->validate([
            'konfirmasi' => 'required|in:NAIK KELAS',
        ], [
            'konfirmasi.in' => 'Ketik "NAIK KELAS" untuk konfirmasi.',
        ]);

        DB::transaction(function () {
            $kelasList = Kelas::orderBy('nama_kelas')->get();

            // Log all class names for debugging
            \Log::info('Kenaikan Kelas - Daftar Kelas:', $kelasList->pluck('nama_kelas', 'id')->toArray());

            // Mapping kelas X → XI dan XI → XII berdasarkan nama
            $mapping = [];
            foreach ($kelasList as $kelas) {
                $nama = $kelas->nama_kelas;

                if (str_starts_with($nama, 'X ') || preg_match('/^X\s/', $nama)) {
                    // Cari kelas XI yang namanya sama (ganti prefix X → XI)
                    $targetNama = 'XI ' . substr($nama, 2);
                    $target = $kelasList->firstWhere('nama_kelas', $targetNama);
                    if ($target) {
                        $mapping[$kelas->id] = $target->id;
                        \Log::info("Mapping: {$nama} → {$targetNama}");
                    } else {
                        \Log::warning("No target found for: {$nama} → {$targetNama}");
                    }
                }

                if (str_starts_with($nama, 'XI ') && !str_starts_with($nama, 'XII')) {
                    $targetNama = 'XII ' . substr($nama, 3);
                    $target = $kelasList->firstWhere('nama_kelas', $targetNama);
                    if ($target) {
                        $mapping[$kelas->id] = $target->id;
                        \Log::info("Mapping: {$nama} → {$targetNama}");
                    } else {
                        \Log::warning("No target found for: {$nama} → {$targetNama}");
                    }
                }
            }

            \Log::info('Kenaikan Kelas - Final Mapping:', $mapping);

            // Siswa kelas XII yang aktif → alumni
            $kelasXII = $kelasList->filter(fn($k) => str_starts_with($k->nama_kelas, 'XII'));
            User::whereIn('kelas_id', $kelasXII->pluck('id'))
                ->whereNotNull('nisn')
                ->where('status_akademik', 'aktif')
                ->update([
                    'is_alumni' => 1,
                    'kelas_id' => null,
                    'guru_wali_id' => null,
                ]);

            // Naik kelas: X → XI, XI → XII
            $totalMoved = 0;
            foreach ($mapping as $dariKelasId => $keKelasId) {
                $count = User::where('kelas_id', $dariKelasId)
                    ->whereNotNull('nisn')
                    ->where('status_akademik', 'aktif')
                    ->update(['kelas_id' => $keKelasId]);
                $totalMoved += $count;
                \Log::info("Moved {$count} students from class ID {$dariKelasId} to {$keKelasId}");
            }
            \Log::info("Total students moved: {$totalMoved}");

            // Siswa tinggal kelas: tetap di kelas yang sama, reset status ke aktif
            $tinggalKelasCount = User::whereNotNull('nisn')
                ->where('status_akademik', 'tinggal_kelas')
                ->update(['status_akademik' => 'aktif']);
            \Log::info("Students staying in same class: {$tinggalKelasCount}");

            // Siswa dikeluarkan/pindah: kosongkan kelas
            $dikeluarkanCount = User::whereNotNull('nisn')
                ->whereIn('status_akademik', ['dikeluarkan', 'pindah_sekolah'])
                ->update(['kelas_id' => null, 'guru_wali_id' => null]);
            \Log::info("Students expelled/transfered: {$dikeluarkanCount}");
        });

        return back()->with('success', 'Proses kenaikan kelas selesai. Data siswa telah diperbarui.');
    }

    public function pindahSiswa(Request $request)
    {
        $request->validate([
            'dari_guru_id' => 'required|exists:guru,id',
            'ke_guru_id' => 'required|exists:guru,id|different:dari_guru_id',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:users,id',
        ]);

        $query = User::where('guru_wali_id', $request->dari_guru_id)->whereNotNull('nisn');

        if ($request->filled('siswa_ids')) {
            $query->whereIn('id', $request->siswa_ids);
        }

        $jumlah = $query->count();
        $query->update(['guru_wali_id' => $request->ke_guru_id]);

        $guruBaru = Guru::with('user')->find($request->ke_guru_id);

        return back()->with('success', "{$jumlah} siswa berhasil dipindahkan ke {$guruBaru->user->name}.");
    }

    public function gantiKepalaSekolah(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
        ]);

        DB::transaction(function () use ($request) {
            // Reset kepala sekolah lama
            Guru::where('status_pegawai', 'Kepala Sekolah')
                ->update(['status_pegawai' => 'Guru']);

            // Set kepala sekolah baru
            Guru::where('id', $request->guru_id)
                ->update(['status_pegawai' => 'Kepala Sekolah']);
        });

        $guru = Guru::with('user')->find($request->guru_id);

        return back()->with('success', "{$guru->user->name} ditetapkan sebagai Kepala Sekolah.");
    }

    public function searchSiswa(Request $request)
    {
        $siswa = User::whereNotNull('nisn')
            ->where('is_alumni', 0)
            ->where('name', 'like', '%' . $request->q . '%')
            ->with('kelas')
            ->limit(10)
            ->get(['id', 'name', 'nisn', 'kelas_id', 'status_akademik']);

        return response()->json($siswa);
    }
}
