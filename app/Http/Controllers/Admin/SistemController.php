<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PengaturanSistem;

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

        $tahunAjaran = PengaturanSistem::getValue('tahun_ajaran', date('Y') . ' / ' . (date('Y') + 1));
        $mulaiTahunBaru = PengaturanSistem::getValue('mulai_tahun_baru', '');

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

        PengaturanSistem::setValue('tahun_ajaran', $request->tahun_ajaran);
        PengaturanSistem::setValue('mulai_tahun_baru', $request->mulai_tahun_baru);

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
            'kelas_id' => 'nullable|exists:kelas,id',
        ], [
            'konfirmasi.in' => 'Ketik "NAIK KELAS" untuk konfirmasi.',
        ]);

        $selectedKelasId = $request->input('kelas_id');

        DB::transaction(function () use ($selectedKelasId) {
            // Always get full class list for mapping
            $kelasList = Kelas::orderBy('nama_kelas')->get();

            // Log all class names for debugging
            \Log::info('Kenaikan Kelas - Daftar Kelas:', $kelasList->pluck('nama_kelas', 'id')->toArray());
            if ($selectedKelasId) {
                \Log::info('Kenaikan Kelas - Processing single class ID:', ['id' => $selectedKelasId]);
            }

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

            // Filter mapping if single class selected
            if ($selectedKelasId) {
                $mapping = array_filter($mapping, fn($v, $k) => $k == $selectedKelasId, ARRAY_FILTER_USE_BOTH);
                \Log::info('Kenaikan Kelas - Filtered Mapping for single class:', $mapping);
            }

            // Siswa kelas XII yang aktif → alumni
            $kelasXII = $kelasList->filter(fn($k) => str_starts_with($k->nama_kelas, 'XII'));
            $xiiQuery = User::whereIn('kelas_id', $kelasXII->pluck('id'))
                ->whereNotNull('nisn')
                ->where('status_akademik', 'aktif');
            if ($selectedKelasId) {
                $xiiQuery->where('kelas_id', $selectedKelasId);
            }
            $xiiQuery->update([
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

    public function updateModeIsiData(Request $request)
    {
        // Checkbox: jika dicentang, Laravel terima nilai "1"
        // Jika tidak dicentang, field tidak ada sama sekali di request
        // Hidden input trick tidak bekerja saat ada dua field sama nama — pakai has() saja
        PengaturanSistem::setValue('mode_isi_data_guru', $request->has('mode_guru') ? '1' : '0');
        PengaturanSistem::setValue('mode_isi_data_siswa', $request->has('mode_siswa') ? '1' : '0');

        PengaturanSistem::setValue('mode_isi_data_guru_deadline', $request->input('deadline_guru') ?: null);
        PengaturanSistem::setValue('mode_isi_data_siswa_deadline', $request->input('deadline_siswa') ?: null);

        PengaturanSistem::setValue('mode_isi_data_guru_fields', json_encode($request->input('fields_guru', [])));
        PengaturanSistem::setValue('mode_isi_data_siswa_fields', json_encode($request->input('fields_siswa', [])));

        return back()->with('success', 'Pengaturan mode pengisian data disimpan.');
    }

    public function getSiswaByKelas($id)
    {
        $siswa = User::where('kelas_id', $id)
            ->whereNotNull('nisn')
            ->where('is_alumni', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'nisn', 'kelas_id']);

        return response()->json($siswa);
    }

    public function pindahKelas(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:kelas,beberapa,satu',
            'ke_kelas_id' => 'required|exists:kelas,id',
            'dari_kelas_id' => 'nullable|exists:kelas,id',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:users,id',
            'siswa_id' => 'nullable|exists:users,id',
        ]);

        $keKelasId = $request->ke_kelas_id;
        $keKelas = Kelas::findOrFail($keKelasId);

        switch ($request->mode) {

            case 'kelas':
                $request->validate(['dari_kelas_id' => 'required|exists:kelas,id|different:ke_kelas_id']);
                $jumlah = User::where('kelas_id', $request->dari_kelas_id)
                    ->whereNotNull('nisn')
                    ->where('is_alumni', 0)
                    ->update(['kelas_id' => $keKelasId]);
                return response()->json([
                    'success' => true,
                    'message' => "{$jumlah} siswa berhasil dipindahkan ke {$keKelas->nama_kelas}.",
                ]);

            case 'beberapa':
                $request->validate(['siswa_ids' => 'required|array|min:1']);
                $jumlah = User::whereIn('id', $request->siswa_ids)
                    ->whereNotNull('nisn')
                    ->update(['kelas_id' => $keKelasId]);
                return response()->json([
                    'success' => true,
                    'message' => "{$jumlah} siswa berhasil dipindahkan ke {$keKelas->nama_kelas}.",
                ]);

            case 'satu':
                $request->validate(['siswa_id' => 'required|exists:users,id']);
                $siswa = User::findOrFail($request->siswa_id);
                $siswa->update(['kelas_id' => $keKelasId]);
                return response()->json([
                    'success' => true,
                    'message' => "{$siswa->name} berhasil dipindahkan ke {$keKelas->nama_kelas}.",
                ]);
        }
    }
}
