<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanBantuan;
use Illuminate\View\View;

class PesanBantuanController extends Controller
{
    public function index(): View
    {
        $pesanBantuan = PesanBantuan::latest()->get();

        return view('admin.pesan-bantuan', compact('pesanBantuan'));
    }
}
