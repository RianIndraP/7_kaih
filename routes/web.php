<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManajemenGuruController as AdminManajemenGuruController;
use App\Http\Controllers\Admin\ManajemenSiswaController as AdminManajemenSiswaController;
use App\Http\Controllers\Admin\PesanBantuanController as AdminPesanBantuanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\GuruProfilController as GuruProfilController;
use App\Http\Controllers\Guru\ListMuridController;
use App\Http\Controllers\Guru\PelaporanController as GuruPelaporanController;
use App\Http\Controllers\NotifController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\GantiPasswordController;
use App\Http\Controllers\Student\KebiasaanController;
use App\Http\Controllers\Student\PesanBantuanController;
use App\Http\Controllers\Student\PesanController;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Root ──────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isSiswa()) {
            return redirect()->route('student.dashboard');
        } else if ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        } else if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    }
    return redirect()->route('login');
});

Route::post('/save-token', [NotifController::class, 'saveToken'])->middleware('auth');

Route::get('/test-notif', function () {

    $tokens = FcmToken::pluck('token');

    foreach ($tokens as $token) {
        app(App\Http\Controllers\NotifController::class)
            ->sendNotif(
                $token,
                'Test Laravel 🔥',
                'Notifikasi dari backend berhasil!'
            );
    }

    return 'Notif dikirim ke semua device';
});

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Forgot Password ───────────────────────────────────────────────────────────
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::get('/verify-data', [AuthController::class, 'showVerifyData'])->name('verify-data');
Route::post('/verify-data', [AuthController::class, 'verifyData']);

Route::get('/create-new-password', [AuthController::class, 'showCreateNewPassword'])->name('create-new-password');
Route::post('/create-new-password', [AuthController::class, 'createNewPassword']);

Route::get('/password-success', [AuthController::class, 'showPasswordSuccess'])->name('password-success');

// ── Student (protected) ───────────────────────────────────────────────────────
Route::middleware(['auth', 'siswa'])->prefix('student')->name('student.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/save', [ProfileController::class, 'save'])->name('profile.save');
    Route::post('/profile/delete-location', [ProfileController::class, 'deleteLocation'])->name('profile.delete-location');

    // 7 Kebiasaan
    Route::get('/kebiasaan', [KebiasaanController::class, 'index'])->name('kebiasaan');
    Route::post('/kebiasaan/store', [KebiasaanController::class, 'store'])->name('kebiasaan.store');
    Route::get('/kebiasaan/data', [KebiasaanController::class, 'show'])->name('kebiasaan.show');
    Route::get('/kebiasaan/riwayat', [KebiasaanController::class, 'riwayat'])->name('kebiasaan.riwayat');

    // Pesan dari Guru Wali
    Route::get('/pesan/unread-count', [PesanController::class, 'unreadCount'])->name('pesan.unread');
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan');
    Route::post('/pesan/{id}/baca', [PesanController::class, 'baca'])->name('pesan.baca');

    // Ganti Password
    Route::get('/ganti-password', [GantiPasswordController::class, 'index'])->name('ganti-password');
    Route::post('/ganti-password', [GantiPasswordController::class, 'update'])->name('ganti-password.update');

    // Kirim Pesan Bantuan (ikon ?)
    Route::get('/kirim-pesan-bantuan', [PesanBantuanController::class, 'index'])->name('kirim-pesan-bantuan');
    Route::post('/kirim-pesan-bantuan', [PesanBantuanController::class, 'store'])->name('kirim-pesan-bantuan.store');
});

// ── Guru (protected) ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'guru'])->prefix('guru')->name('guru.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Profil Guru
    Route::get('/profil', [GuruProfilController::class, 'index'])->name('profil');
    Route::post('/profil/save', [GuruProfilController::class, 'save'])->name('profil.save');
    Route::post('/profil/delete-location', [GuruProfilController::class, 'deleteLocation'])->name('profil.delete-location');

    // ── List Murid ─────────────────────────────────────────────────────────
    // PENTING: semua route static harus SEBELUM route dengan parameter {id}
    Route::get('/list-murid', [ListMuridController::class, 'index'])->name('list-murid');
    Route::get('/list-murid/get-siswa', [ListMuridController::class, 'getSiswa'])->name('list-murid.get-siswa');
    Route::get('/list-murid/get-minggu-options', [ListMuridController::class, 'getMingguOptions'])->name('list-murid.get-minggu-options');
    Route::post('/list-murid/kirim-pesan', [ListMuridController::class, 'kirimPesan'])->name('list-murid.kirim-pesan');
    Route::post('/list-murid/hapus-umpan-balik', [ListMuridController::class, 'hapusUmpanBalik'])->name('list-murid.hapus-umpan-balik');
    Route::get('/list-murid/siswa-profile/{siswaId}', [ListMuridController::class, 'getSiswaProfile'])->name('list-murid.siswa-profile');
    // Route dengan parameter — HARUS di bawah route static
    Route::get('/list-murid/pesan-history/{siswaId}', [ListMuridController::class, 'getPesanHistory'])->name('list-murid.pesan-history');
    Route::get('/list-murid/detail/{id}', [ListMuridController::class, 'getDetailSiswa'])->name('list-murid.detail');

    // Absensi Murid
    Route::get('/absensi-murid', [GuruAbsensiController::class, 'index'])->name('absensi-murid');
    Route::post('/absensi-murid/store', [GuruAbsensiController::class, 'store'])->name('absensi-murid.store');
    Route::get('/absensi-murid/by-pertemuan', [GuruAbsensiController::class, 'getByPertemuan'])->name('absensi-murid.by-pertemuan');
    Route::post('/absensi-murid/tidak-ada-pertemuan', [GuruAbsensiController::class, 'tidakAdaPertemuan'])->name('absensi-murid.tidak-ada-pertemuan');

    // Pelaporan
    Route::get('/pelaporan', [GuruPelaporanController::class, 'index'])->name('pelaporan');
    Route::post('/pelaporan/lampiran-a', [GuruPelaporanController::class, 'storeLampiranA'])->name('lampiran-a.store');  // Lampiran A
    Route::post('/pelaporan/lampiran-b', [GuruPelaporanController::class, 'storeLampiranB'])->name('lampiran-b.store');  // Lampiran B


    // Kirim Pesan ke Siswa
    // Route::get('/kirim-pesan', [GuruKirimPesanController::class, 'index'])->name('kirim-pesan');
    // Route::post('/kirim-pesan', [GuruKirimPesanController::class, 'store'])->name('kirim-pesan.store');

    // Pesan Bantuan dari Siswa
    // Route::get('/pesan-bantuan', [GuruPesanBantuanController::class, 'index'])->name('pesan-bantuan');
    // Route::post('/pesan-bantuan/{id}/balas', [GuruPesanBantuanController::class, 'balas'])->name('pesan-bantuan.balas');
    // Route::patch('/pesan-bantuan/{id}/status', [GuruPesanBantuanController::class, 'updateStatus'])->name('pesan-bantuan.status');

    // Ganti Password
    // Route::get('/ganti-password', [GuruGantiPasswordController::class, 'index'])->name('ganti-password');
    // Route::post('/ganti-password', [GuruGantiPasswordController::class, 'update'])->name('ganti-password.update');

});

// ── Admin (protected) ─────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profil Operator
    Route::get('/profil', [AdminDashboardController::class, 'profil'])->name('profil');
    Route::put('/profil', [AdminDashboardController::class, 'updateProfil'])->name('profil.update');

    // Pesan Bantuan
    Route::get('/pesan-bantuan', [AdminPesanBantuanController::class, 'index'])->name('pesan-bantuan');

    // Data 7 Kebiasaan
    Route::get('/kebiasaan', [AdminDashboardController::class, 'kebiasaan'])->name('kebiasaan');

    // Pengaturan
    Route::get('/pengaturan', [AdminDashboardController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan/password', [AdminDashboardController::class, 'updatePassword'])->name('pengaturan.password');

    // Manajemen Siswa
    Route::get('/siswa', [AdminManajemenSiswaController::class, 'index'])->name('siswa');
    Route::post('/siswa/import', [AdminManajemenSiswaController::class, 'import'])->name('siswa.import');
    Route::post('/siswa/kelas', [AdminManajemenSiswaController::class, 'addKelas'])->name('siswa.kelas.store');
    Route::post('/siswa', [AdminManajemenSiswaController::class, 'store'])->name('siswa.store');
    Route::post('/siswa/{id}', [AdminManajemenSiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [AdminManajemenSiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/{id}/data', [AdminManajemenSiswaController::class, 'getData'])->name('siswa.data');

    // Manajemen Guru
    Route::get('/guru', [AdminManajemenGuruController::class, 'index'])->name('guru');
    Route::post('/guru', [AdminManajemenGuruController::class, 'store'])->name('guru.store');
    Route::post('/guru/import', [AdminManajemenGuruController::class, 'import'])->name('guru.import');
    Route::post('/guru/{id}', [AdminManajemenGuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{id}', [AdminManajemenGuruController::class, 'destroy'])->name('guru.destroy');
    Route::get('/guru/{id}/data', [AdminManajemenGuruController::class, 'getData'])->name('guru.data');
});
