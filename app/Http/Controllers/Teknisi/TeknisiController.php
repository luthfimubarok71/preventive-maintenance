<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\InspeksiHeader;
use Illuminate\Support\Facades\Auth;
class TeknisiController extends Controller
{
    public function dashboard()
    {
$tugas = InspeksiHeader::where('nama_pelaksana', Auth::user()->name)->get();
        return view('teknisi.dashboard', [
            'totalTugas' => $tugas->count(),
            'tugasHariIni' => $tugas->where('tanggal', now()->toDateString())->count(),
            'belumSelesai' => $tugas->where('status', 'proses')->count(),
            'selesai' => $tugas->where('status', 'selesai')->count(),
            'tugas' => $tugas
        ]);
    }
}