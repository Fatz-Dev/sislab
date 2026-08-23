<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaNilaiController extends Controller
{
    public function index()
    {
        // Fitur akan diimplementasi nanti, kembalikan view sementara
        return view('pages.mahasiswa.nilai.nilai');
    }
}
