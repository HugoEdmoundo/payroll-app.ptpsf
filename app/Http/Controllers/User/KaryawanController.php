<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::paginate(10);
        return view('user.karyawan.index', compact('karyawan'));
    }
}