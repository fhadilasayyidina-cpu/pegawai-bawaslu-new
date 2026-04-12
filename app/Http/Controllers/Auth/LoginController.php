<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\Pegawai\PegawaiService;

class LoginController
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }
    public function index()
    {
        // Ambil data lewat service
        $pegawai = $this->pegawaiService->getAllPegawai();

        return view('admin.pegawai.index', compact('pegawai'));
    }
}
