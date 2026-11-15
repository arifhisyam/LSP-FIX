<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::whereDate('tgl_penjualan', today());

        if (auth()->user()->level === 'kasir') {
            $penjualan->where('id_user', auth()->id());
        }

        $transaksiHariIni = $penjualan->count();
        $omzetHariIni     = $penjualan->sum('total');
        $jumlahProduk     = Produk::count();
        $stokMenipis      = Produk::where('stok', '<=', 5)->count();

        return view('dashboard', compact(
            'transaksiHariIni',
            'omzetHariIni',
            'jumlahProduk',
            'stokMenipis'
        ));
    }
}