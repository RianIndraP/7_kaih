<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PelaporanController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        return view('guru.pelaporan', compact('user'));
    }
}
