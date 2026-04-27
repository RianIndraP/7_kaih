<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataGuruWaliController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is Kepala Sekolah
        if (!Auth::user()->isKepalaSekolah()) {
            abort(403);
        }

        $search = $request->get('search', '');

        // Get all guru with NIP or NIK, excluding Kepala Sekolah
        $query = Guru::with(['user'])
            ->whereHas('user', function($query) {
                $query->where(function($q) {
                    $q->whereNotNull('nip')->orWhereNotNull('nik');
                });
            })
            ->where('status_pegawai', '!=', 'Kepala Sekolah');

        // Filter by name if search is provided
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $guruWaliData = $query->get()->sortBy('user.name');

        return view('kepala-sekolah.data-guru-wali', compact('guruWaliData', 'search'));
    }
}
