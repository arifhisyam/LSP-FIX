<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'dari'   => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        // Kalau filter kosong: awal bulan ini sampai hari ini
        $dari   = $request->dari ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? today()->toDateString();

        $penjualan = Penjualan::with(['pelanggan', 'user'])
            ->whereDate('tgl_penjualan', '>=', $dari)
            ->whereDate('tgl_penjualan', '<=', $sampai)
            ->orderBy('tgl_penjualan')
            ->orderBy('id_penjualan')
            ->get();

        $jumlahTransaksi = $penjualan->count();
        $totalDiskon     = $penjualan->sum('diskon');
        $totalPenjualan  = $penjualan->sum('total');

        return view('laporan.index', compact(
            'penjualan',
            'dari',
            'sampai',
            'jumlahTransaksi',
            'totalDiskon',
            'totalPenjualan'
        ));
    }
}