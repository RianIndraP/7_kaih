<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KebiasaanHarian;
use App\Models\LampiranA;
use App\Models\LampiranB;
use App\Models\LampiranC;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            if (Auth::user()->isSiswa()) {
                return redirect()->route('student.dashboard');
            } elseif (Auth::user()->isGuru()) {
                return redirect()->route('guru.dashboard');
            } elseif (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get dashboard statistics
        $stats = [
            'total_guru' => Guru::count(),
            'total_siswa' => User::whereNotNull('nisn')->count(),
            'total_kelas' => Kelas::count(),
            'total_kebiasaan_hari_ini' => KebiasaanHarian::whereDate('created_at', today())->count(),
        ];

        // Get recent activities
        $recentActivities = [
            'kebiasaan_today' => KebiasaanHarian::whereDate('created_at', today())->with('user')->take(5)->get(),
            'lampiran_a_today' => LampiranA::whereDate('created_at', today())->with('murid')->take(5)->get(),
            'lampiran_b_today' => LampiranB::whereDate('created_at', today())->with('murid')->take(5)->get(),
            'lampiran_c_today' => LampiranC::whereDate('created_at', today())->with('murid')->take(5)->get(),
        ];

        // Get class statistics
        $kelasStats = Kelas::withCount('siswa')->get();

        // Get weekly kebiasaan statistics for chart
        $weeklyKebiasaan = KebiasaanHarian::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get guru by status
        $guruByStatus = Guru::selectRaw('status_pegawai, COUNT(*) as count')
            ->groupBy('status_pegawai')
            ->get();

        return view('kepala-sekolah.dashboard', compact(
            'user',
            'stats',
            'recentActivities',
            'kelasStats',
            'weeklyKebiasaan',
            'guruByStatus'
        ));
    }

    public function profil()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $user = Auth::user();
        $guru = $user->guru; // Use guru relation instead
        
        // Get statistics for profile page
        $stats = [
            'total_guru' => Guru::count(),
            'total_siswa' => User::whereNotNull('nisn')->count(),
            'total_kelas' => Kelas::count(),
        ];

        return view('kepala-sekolah.profil', compact('user', 'guru', 'stats'));
    }

    public function saveProfil(Request $request)
    {
        $request->validate([
            'nama'          => ['nullable', 'string', 'max:255'],
            'tempat_lahir'  => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status_kepala_sekolah' => ['nullable', 'string', 'max:100'],
            'gender'        => ['nullable', 'string', 'in:Laki-laki,Perempuan'],
            'unit_kerja'    => ['nullable', 'string', 'max:255'],
            'hp'            => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255'],
            'alamat'        => ['nullable', 'string', 'max:500'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
        ]);

        $user = Auth::user();
        $guru = $user->guru; // Use guru relation

        $pathFoto = $user->foto; // default pakai foto lama
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru
            $pathFoto = $request->file('foto')->store('profile_photos', 'public');
        }

        // Update data user
        /** @var \App\Models\User $user */
        $user->update([
            'name'          => $request->nama ?? $user->name,
            'foto'          => $pathFoto,
            'email'         => $request->email ?? $user->email,
            'gender'        => $request->gender ?? $user->gender,
            'no_telepon'    => $request->hp ?? $user->no_telepon,
            'tempat_lahir'  => $request->tempat_lahir ?? $user->tempat_lahir,
            'birth_date'    => $request->tanggal_lahir ?? $user->birth_date,
            'alamat'        => $request->alamat ?? $user->alamat,
            'latitude'      => $request->latitude ?? $user->latitude,
            'longitude'     => $request->longitude ?? $user->longitude,
        ]);

        // Update data guru jika ada
        if ($guru) {
            $guru->update([
                'status_pegawai' => $request->status_kepala_sekolah ?? $guru->status_pegawai,
                'unit_kerja'     => $request->unit_kerja ?? $guru->unit_kerja,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil kepala sekolah berhasil disimpan.',
        ]);
    }

    public function deleteLocation(Request $request)
    {
        try {
            $user = Auth::user();

            /** @var \App\Models\User $user */
            $user->update([
                'latitude' => null,
                'longitude' => null,
                'alamat' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi kepala sekolah berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus lokasi.'
            ], 500);
        }
    }

    public function dataKebiasaan()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $filter = request()->get('filter', 'daily'); // default to daily
        $date = request()->get('date');
        $week = request()->get('week');
        $month = request()->get('month');
        $monthly = request()->get('monthly');

        // Get all guru with their students and habit completion data
        // Structure: Guru table -> user (with nip/nik) -> User(siswa) with guru_wali_id = Guru.id
        $guruWaliData = Guru::with(['user', 'siswaWaliKelas'])
            ->whereHas('user', function($query) {
                $query->where(function($q) {
                    $q->whereNotNull('nip')->orWhereNotNull('nik');
                });
            })
            ->where('status_pegawai', '!=', 'Kepala Sekolah')
            ->get()
            ->map(function ($guru) use ($filter, $date, $week, $month, $monthly) {
                $totalSiswa = $guru->siswaWaliKelas->count();

                if ($totalSiswa === 0) {
                    return [
                        'guru' => $guru,
                        'total_siswa' => 0,
                        'presentase_penyelesaian' => 0,
                        'total_kebiasaan_selesai' => 0,
                        'total_kebiasaan_maksimal' => 0
                    ];
                }

                $siswaIds = $guru->siswaWaliKelas->pluck('id');
                
                if ($filter === 'daily') {
                    // Get habit completion for specific date
                    $targetDate = $date ? $date : now()->format('Y-m-d');
                    
                    $kebiasaanHariIni = KebiasaanHarian::whereIn('user_id', $siswaIds)
                        ->whereDate('tanggal', $targetDate)
                        ->get();
                    
                    $totalKebiasaanSelesai = $kebiasaanHariIni->sum(function($kebiasaan) {
                        if ($kebiasaan instanceof \App\Models\KebiasaanHarian) {
                            return $kebiasaan->hitungSelesai();
                        }
                        return 0;
                    });

                    $totalKebiasaanMaksimal = $totalSiswa * 7; // 7 habits per student
                } elseif ($filter === 'weekly') {
                    // Calculate dates based on week number and month
                    if ($month && $week) {
                        // Parse month (format: Y-m)
                        $monthDate = \Carbon\Carbon::parse($month . '-01');
                        $year = $monthDate->year;
                        $monthNum = $monthDate->month;
                        
                        // Calculate start and end date based on week number
                        // Week 1: 1-7, Week 2: 8-14, Week 3: 15-21, Week 4: 22-28, Week 5: 29-end
                        $startDay = ($week - 1) * 7 + 1;
                        $endDay = $week * 7;
                        
                        // Adjust end day for last week of month
                        $lastDayOfMonth = $monthDate->endOfMonth()->day;
                        if ($endDay > $lastDayOfMonth) {
                            $endDay = $lastDayOfMonth;
                        }
                        
                        $targetStartDate = \Carbon\Carbon::create($year, $monthNum, $startDay)->format('Y-m-d');
                        $targetEndDate = \Carbon\Carbon::create($year, $monthNum, $endDay)->format('Y-m-d');
                    } else {
                        // Default to current week
                        $targetStartDate = now()->startOfWeek()->format('Y-m-d');
                        $targetEndDate = now()->endOfWeek()->format('Y-m-d');
                    }
                    
                    $kebiasaanMingguIni = KebiasaanHarian::whereIn('user_id', $siswaIds)
                        ->whereBetween('tanggal', [$targetStartDate, $targetEndDate])
                        ->get();
                    
                    $totalKebiasaanSelesai = $kebiasaanMingguIni->sum(function($kebiasaan) {
                        if ($kebiasaan instanceof \App\Models\KebiasaanHarian) {
                            return $kebiasaan->hitungSelesai();
                        }
                        return 0;
                    });

                    // Calculate number of days in the range
                    $daysInRange = \Carbon\Carbon::parse($targetStartDate)->diffInDays(\Carbon\Carbon::parse($targetEndDate)) + 1;
                    
                    // For weekly: 7 habits per student per day × number of days
                    $totalKebiasaanMaksimal = $totalSiswa * 7 * $daysInRange;
                } elseif ($filter === 'monthly') {
                    // Get habit completion for entire month
                    if ($monthly) {
                        // Parse month (format: Y-m)
                        $monthDate = \Carbon\Carbon::parse($monthly . '-01');
                        $targetStartDate = $monthDate->startOfMonth()->format('Y-m-d');
                        $targetEndDate = $monthDate->endOfMonth()->format('Y-m-d');
                    } else {
                        // Default to current month
                        $targetStartDate = now()->startOfMonth()->format('Y-m-d');
                        $targetEndDate = now()->endOfMonth()->format('Y-m-d');
                    }
                    
                    $kebiasaanBulanIni = KebiasaanHarian::whereIn('user_id', $siswaIds)
                        ->whereBetween('tanggal', [$targetStartDate, $targetEndDate])
                        ->get();
                    
                    $totalKebiasaanSelesai = $kebiasaanBulanIni->sum(function($kebiasaan) {
                        if ($kebiasaan instanceof \App\Models\KebiasaanHarian) {
                            return $kebiasaan->hitungSelesai();
                        }
                        return 0;
                    });

                    // Calculate number of days in the month
                    $daysInRange = \Carbon\Carbon::parse($targetStartDate)->diffInDays(\Carbon\Carbon::parse($targetEndDate)) + 1;
                    
                    // For monthly: 7 habits per student per day × number of days in month
                    $totalKebiasaanMaksimal = $totalSiswa * 7 * $daysInRange;
                }
                
                $presentasePenyelesaian = $totalKebiasaanMaksimal > 0 
                    ? round(($totalKebiasaanSelesai / $totalKebiasaanMaksimal) * 100, 2)
                    : 0;

                return [
                    'guru' => $guru,
                    'total_siswa' => $totalSiswa,
                    'presentase_penyelesaian' => $presentasePenyelesaian,
                    'total_kebiasaan_selesai' => $totalKebiasaanSelesai,
                    'total_kebiasaan_maksimal' => $totalKebiasaanMaksimal
                ];
            })
            ->sortByDesc('presentase_penyelesaian')
            ->values();

        return view('kepala-sekolah.data-kebiasaan', compact('guruWaliData', 'filter', 'date', 'week', 'month', 'monthly'));
    }

    public function dataKebiasaanDetail($guruId)
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $guru = Guru::with('user')->find($guruId);
            
            if (!$guru) {
                return response()->json(['success' => false, 'message' => 'Guru tidak ditemukan'], 404);
            }

            $siswaList = $guru->siswaWaliKelas;
            $filter = request()->get('filter', 'daily');
            $date = request()->get('date');
            $week = request()->get('week');
            $month = request()->get('month');
            $monthly = request()->get('monthly');
            
            $siswaData = $siswaList->map(function($siswa) use ($filter, $date, $week, $month, $monthly) {
                if ($filter === 'daily') {
                    $targetDate = $date ? $date : now()->format('Y-m-d');
                    $kebiasaanData = KebiasaanHarian::where('user_id', $siswa->id)
                        ->whereDate('tanggal', $targetDate)
                        ->first();
                    
                    $kebiasaanSelesai = 0;
                    if ($kebiasaanData && $kebiasaanData instanceof \App\Models\KebiasaanHarian) {
                        $kebiasaanSelesai = $kebiasaanData->hitungSelesai();
                    }
                    
                    $kebiasaanMaksimal = 7;
                } elseif ($filter === 'weekly') {
                    // Calculate dates based on week number and month
                    if ($month && $week) {
                        // Parse month (format: Y-m)
                        $monthDate = \Carbon\Carbon::parse($month . '-01');
                        $year = $monthDate->year;
                        $monthNum = $monthDate->month;
                        
                        // Calculate start and end date based on week number
                        // Week 1: 1-7, Week 2: 8-14, Week 3: 15-21, Week 4: 22-28, Week 5: 29-end
                        $startDay = ($week - 1) * 7 + 1;
                        $endDay = $week * 7;
                        
                        // Adjust end day for last week of month
                        $lastDayOfMonth = $monthDate->endOfMonth()->day;
                        if ($endDay > $lastDayOfMonth) {
                            $endDay = $lastDayOfMonth;
                        }
                        
                        $targetStartDate = \Carbon\Carbon::create($year, $monthNum, $startDay)->format('Y-m-d');
                        $targetEndDate = \Carbon\Carbon::create($year, $monthNum, $endDay)->format('Y-m-d');
                    } else {
                        // Default to current week
                        $targetStartDate = now()->startOfWeek()->format('Y-m-d');
                        $targetEndDate = now()->endOfWeek()->format('Y-m-d');
                    }
                    
                    $kebiasaanMingguIni = KebiasaanHarian::where('user_id', $siswa->id)
                        ->whereBetween('tanggal', [$targetStartDate, $targetEndDate])
                        ->get();
                    
                    $kebiasaanSelesai = $kebiasaanMingguIni->sum(function($kebiasaan) {
                        if ($kebiasaan instanceof \App\Models\KebiasaanHarian) {
                            return $kebiasaan->hitungSelesai();
                        }
                        return 0;
                    });
                    
                    // Calculate number of days in the range
                    $daysInRange = \Carbon\Carbon::parse($targetStartDate)->diffInDays(\Carbon\Carbon::parse($targetEndDate)) + 1;
                    
                    // For weekly: 7 habits per day × number of days
                    $kebiasaanMaksimal = 7 * $daysInRange;
                } elseif ($filter === 'monthly') {
                    // Get habit completion for entire month
                    if ($monthly) {
                        // Parse month (format: Y-m)
                        $monthDate = \Carbon\Carbon::parse($monthly . '-01');
                        $targetStartDate = $monthDate->startOfMonth()->format('Y-m-d');
                        $targetEndDate = $monthDate->endOfMonth()->format('Y-m-d');
                    } else {
                        // Default to current month
                        $targetStartDate = now()->startOfMonth()->format('Y-m-d');
                        $targetEndDate = now()->endOfMonth()->format('Y-m-d');
                    }
                    
                    $kebiasaanBulanIni = KebiasaanHarian::where('user_id', $siswa->id)
                        ->whereBetween('tanggal', [$targetStartDate, $targetEndDate])
                        ->get();
                    
                    $kebiasaanSelesai = $kebiasaanBulanIni->sum(function($kebiasaan) {
                        if ($kebiasaan instanceof \App\Models\KebiasaanHarian) {
                            return $kebiasaan->hitungSelesai();
                        }
                        return 0;
                    });

                    // Calculate number of days in the month
                    $daysInRange = \Carbon\Carbon::parse($targetStartDate)->diffInDays(\Carbon\Carbon::parse($targetEndDate)) + 1;
                    
                    // For monthly: 7 habits per day × number of days in month
                    $kebiasaanMaksimal = 7 * $daysInRange;
                }
                
                $presentase = round(($kebiasaanSelesai / $kebiasaanMaksimal) * 100, 2);
                
                return [
                    'id' => $siswa->id,
                    'nama' => $siswa->name,
                    'kebiasaan_selesai' => $kebiasaanSelesai,
                    'kebiasaan_maksimal' => $kebiasaanMaksimal,
                    'presentase' => $presentase
                ];
            });

            // Build period text
            if ($filter === 'daily') {
                $targetDate = $date ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') : now()->locale('id')->translatedFormat('l, d F Y');
                $periodText = $targetDate;
            } elseif ($filter === 'weekly') {
                if ($month && $week) {
                    $monthDate = \Carbon\Carbon::parse($month . '-01');
                    $startDay = ($week - 1) * 7 + 1;
                    $endDay = $week * 7;
                    $lastDayOfMonth = $monthDate->endOfMonth()->day;
                    if ($endDay > $lastDayOfMonth) {
                        $endDay = $lastDayOfMonth;
                    }
                    $targetStartDate = \Carbon\Carbon::create($monthDate->year, $monthDate->month, $startDay)->locale('id')->translatedFormat('d F Y');
                    $targetEndDate = \Carbon\Carbon::create($monthDate->year, $monthDate->month, $endDay)->locale('id')->translatedFormat('d F Y');
                    $periodText = "Minggu {$week} ({$targetStartDate} - {$targetEndDate})";
                } else {
                    $targetStartDate = now()->startOfWeek()->locale('id')->translatedFormat('d F Y');
                    $targetEndDate = now()->endOfWeek()->locale('id')->translatedFormat('d F Y');
                    $periodText = "{$targetStartDate} - {$targetEndDate}";
                }
            } elseif ($filter === 'monthly') {
                if ($monthly) {
                    $monthDate = \Carbon\Carbon::parse($monthly . '-01');
                    $periodText = $monthDate->locale('id')->translatedFormat('F Y');
                } else {
                    $periodText = now()->locale('id')->translatedFormat('F Y');
                }
            }

            return response()->json([
                'success' => true,
                'guru_name' => $guru->user->name,
                'siswa_count' => $siswaList->count(),
                'period' => $periodText,
                'siswa' => $siswaData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getChartData(Request $request)
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $type = $request->get('type', 'kebiasaan');
        $period = $request->get('period', 'week');

        $data = [];

        switch ($type) {
            case 'kebiasaan':
                $query = KebiasaanHarian::selectRaw('DATE(created_at) as date, COUNT(*) as count');
                break;
            case 'lampiran_a':
                $query = LampiranA::selectRaw('DATE(created_at) as date, COUNT(*) as count');
                break;
            case 'lampiran_b':
                $query = LampiranB::selectRaw('DATE(created_at) as date, COUNT(*) as count');
                break;
            case 'lampiran_c':
                $query = LampiranC::selectRaw('DATE(created_at) as date, COUNT(*) as count');
                break;
        }

        switch ($period) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
                break;
        }

        $data = $query->groupBy('date')->orderBy('date')->get();

        return response()->json($data);
    }

    public function getSiswaByKelas()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $kelasData = Kelas::withCount('siswa')->get();
        
        return response()->json($kelasData);
    }

    public function getGuruByStatus()
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $guruData = Guru::selectRaw('status_pegawai, COUNT(*) as count')
            ->groupBy('status_pegawai')
            ->get();

        return response()->json($guruData);
    }
}
